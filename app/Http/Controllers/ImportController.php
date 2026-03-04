<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Finance;
use App\Models\Department;
use App\Models\Hu_reksumber;
use App\Models\Payableto;
use App\Models\Bank;
use App\Models\Matauang;
use DB;

class ImportController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:finance-import', ['only' => ['index','upload']]);
    }

    public function index()
    {
        return view('import');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');

        $sheets = Excel::toArray([], $file);
        $rows   = $sheets[0] ?? [];

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File Excel kosong / tidak ada data.']);
        }

        // Build header index: normalized_header => column_index
        $idx = $this->buildHeaderIndex($rows[0]);

        // Helper ambil cell by header (bisa pakai key excel asli atau snake_case)
        $cell = function(array $row, string $key) use ($idx) {
            $k = $this->normHeader($key);          // normalize key yang diminta
            if (!isset($idx[$k])) return null;     // header tidak ada
            return $row[$idx[$k]] ?? null;         // value cell
        };

        $now     = now();
        $inserts = [];

        foreach (array_slice($rows, 1) as $r) {
            // skip row kosong
            if (count(array_filter($r, fn($v) => $v !== null && $v !== '')) === 0) continue;

            $typeRaw = $cell($r, 'type'); // aman kalau null
            $typeNorm = $typeRaw ? strtolower(str_replace(' ', '', (string)$typeRaw)) : null;

            $inserts[] = [
                'type' => $typeNorm,
                'po_no' => $cell($r, 'PO Number'),
                'id_category' => null,

                'form_submission_time' => $this->parseDate($cell($r, 'Form Submission Time')),
                'final_validation_time' => $this->parseDate($cell($r, 'Final Validation Time')),
                'email' => $cell($r, 'email'),

                'id_dept' => $this->findIdByName(Department::class, $cell($r, 'Requesting Department')),
                'id_rek_sumber' => $this->findIdByName(Hu_reksumber::class, $cell($r, 'Hospital Unit & Rekening Sumber')),
                'id_payable' => $this->findPayableIdByNameAndType(
                    $cell($r, 'Payable To'),
                    $typeNorm
                ),

                'nama_rekening_tujuan' => $cell($r, 'Nama Rekening Tujuan'),
                'id_bank' => $this->findIdByName(Bank::class, $cell($r, 'Bank Tujuan')),

                'no_rek_tujuan' => $cell($r, 'VA Number (no rekening tujuan)'),

                'invoice_date' => $this->parseDate($cell($r, 'Invoice Date')),
                'doc_no' => $this->normDocNo($cell($r, 'Document number(s)')),

                'description' => $cell($r, 'description'),
                'id_currency' => $this->findIdByName(Matauang::class, $cell($r, 'currency')),

                'dpp' => $this->toNumber($cell($r, 'dpp')),
                'persen_ppn' => 0,
                'nilai_ppn' => $this->toNumber($cell($r, 'ppn')),
                'pph' => $this->toNumber($cell($r, 'pph')),
                'total_amount' => $this->toNumber($cell($r, 'Total Amount')),

                'input_file' => $cell($r, 'Input file'),
                'send_email' => filter_var($cell($r, 'is_sent'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),

                'payment_term' => $cell($r, 'Payment Term'),
                'journal_no' => $cell($r, 'Journal Number'),

                'status' => $cell($r, 'status'),
                'payment_date' => $this->parseDate($cell($r, 'Due Date')),

                'user_payment_entry' => null,
                'user_entry' => auth()->id(),

                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (count($inserts) === 0) {
            return back()->withErrors(['file' => 'Tidak ada row valid untuk diimport.']);
        }

        DB::transaction(function () use ($inserts) {
            Finance::insert($inserts);
        });

        return back()->with('status', 'Import berhasil. Total row: ' . count($inserts));
    }

    private function buildHeaderIndex(array $headerRow): array
    {
        $idx = [];
        foreach ($headerRow as $i => $h) {
            $key = $this->normHeader($h);
            if ($key === '') continue;

            // kalau ada duplicate header setelah normalisasi, yang terakhir menang
            $idx[$key] = $i;
        }
        return $idx;
    }

    private function normHeader($h): string
    {
        $h = trim((string) $h);
        $h = preg_replace('/\s+/', ' ', $h);
        $h = str_replace(['/', '(', ')', '-', '&'], ' ', $h);
        $h = strtolower($h);
        $h = preg_replace('/[^a-z0-9 ]/', '', $h);
        $h = trim(preg_replace('/\s+/', '_', $h));
        return $h;
    }

    private function toNumber($value)
    {
        if ($value === null || $value === '') return null;

        if (is_numeric($value)) return $value + 0;

        $v = (string) $value;
        // hilangkan pemisah ribuan umum
        $v = str_replace([' ', ','], ['', ''], $v);
        // kalau format "1.234.567" -> "1234567"
        if (substr_count($v, '.') > 1) $v = str_replace('.', '', $v);

        return is_numeric($v) ? $v + 0 : null;
    }

    private function parseDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Kalau Excel kirim angka serial date
        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                    ->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normDocNo(?string $text, bool $asArray = false, bool $newlineAsSeparator = true, bool $dropDashOnly = true)
    {
        if ($text === null) {
            return $asArray ? [] : null;
        }

        $text = trim($text);
        if ($text === '') {
            return $asArray ? [] : null;
        }

        // buang quote pembungkus
        $text = trim($text, "\"'");

        // samakan line ending
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // jadikan separator utama: TAB dan koma => ;
        $text = str_replace(["\t", ","], ";", $text);

        // newline dianggap separator juga
        if ($newlineAsSeparator) {
            $text = str_replace("\n", ";", $text);
        }

        // pecah berdasarkan ;
        $parts = explode(";", $text);

        $out = [];
        foreach ($parts as $p) {
            $p = trim($p);
            $p = trim($p, "\"'");

            if ($p === '') continue;
            if ($dropDashOnly && $p === '-') continue;

            $out[] = $p;
        }

        // gabungkan pakai ;
        return $asArray ? $out : implode(";", $out);
    }

    private function findIdByName($modelClass, $name)
    {
        if (!$name) return null;

        $name = trim($name);

        $row = $modelClass::whereRaw('LOWER(nama) = ?', [strtolower($name)])
            ->where('valid', 1)
            ->first();

        return $row?->id;
    }

    private function findPayableIdByNameAndType($name, ?string $typeNorm)
    {
        if (!$name) return null;

        $name = trim((string) $name);
        if ($name === '') return null;

        if (!$typeNorm) return null;

        $row = Payableto::query()
            ->whereRaw('LOWER(nama) = ?', [strtolower($name)])
            ->whereRaw('LOWER(REPLACE(type, " ", "")) = ?', [$typeNorm])
            ->where('valid', 1)
            ->first();

        return $row?->id;
    }
}
