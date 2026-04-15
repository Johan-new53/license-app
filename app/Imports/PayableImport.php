<?php

namespace App\Imports;

use App\Models\Payableto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class PayableImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected string $userEntry;

    public int $inserted = 0;
    public int $updated = 0;
    public int $skipped = 0;

    public array $errors = [];

    protected array $allowedTypes = [
        'hardcopy',
        'softcopy',
        'automate',
        'digital',
    ];

    public function __construct(string $userEntry)
    {
        $this->userEntry = $userEntry;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $excelRow = $index + 2;

            try {
                $nama = $this->cleanValue(
                    $this->getValue($row, [
                        'name',
                        'vendor_name',
                        'nama',
                    ])
                );

                $vendorAccount = $this->cleanValue(
                    $this->getValue($row, [
                        'vendor_account',
                        'account_name',
                        'account',
                        'vendor_account_',
                    ])
                );

                $hariRaw = $this->getValue($row, [
                    'top_hari',
                    'top_hari_',
                    'hari',
                ], 0);

                $validRaw = $this->getValue($row, [
                    'valid',
                    'status',
                ], 1);

                $termPayment = $this->cleanValue(
                    $this->getValue($row, [
                        'term_payment',
                        'payment_term',
                        'termpayment',
                    ], '-')
                );

                $typeRaw = $this->cleanValue(
                    $this->getValue($row, [
                        'type',
                        'jenis',
                    ])
                );

                if ($nama === '' && $vendorAccount === '' && $typeRaw === '') {
                    $this->skipped++;
                    continue;
                }

                if ($nama === '') {
                    $this->skipped++;
                    $this->errors[] = "Baris {$excelRow}: Name/Nama kosong.";
                    continue;
                }

                $type = strtolower(trim($typeRaw));

                if ($type === '') {
                    $this->skipped++;
                    $this->errors[] = "Baris {$excelRow}: Type kosong.";
                    continue;
                }

                if (!in_array($type, $this->allowedTypes, true)) {
                    $this->skipped++;
                    $this->errors[] = "Baris {$excelRow}: Type '{$typeRaw}' tidak valid. Hanya boleh hardcopy, softcopy, automate, atau digital.";
                    continue;
                }

                $hari = is_numeric($hariRaw) ? (int) $hariRaw : 0;

                if (is_string($validRaw)) {
                    $validNormalized = strtolower(trim($validRaw));

                    if (in_array($validNormalized, ['1', 'valid', 'yes', 'true'], true)) {
                        $valid = 1;
                    } elseif (in_array($validNormalized, ['0', 'invalid', 'no', 'false', ''], true)) {
                        $valid = 0;
                    } else {
                        $valid = 1;
                    }
                } else {
                    $valid = is_numeric($validRaw) ? (int) $validRaw : 1;
                }

                $valid = $valid == 1 ? 1 : 0;
                $termPayment = $termPayment !== '' ? $termPayment : '-';

                $existingQuery = Payableto::where('type', $type)
                    ->where('nama', $nama);

                if ($vendorAccount !== '') {
                    $existingQuery->where('vendor_account', $vendorAccount);
                } else {
                    $existingQuery->where(function ($q) {
                        $q->whereNull('vendor_account')
                          ->orWhere('vendor_account', '');
                    });
                }

                $existing = $existingQuery->first();

                if ($existing) {
                    $existing->hari = $hari;
                    $existing->valid = $valid;
                    $existing->user_entry = $this->userEntry;

                    if (($existing->vendor_account === null || $existing->vendor_account === '') && $vendorAccount !== '') {
                        $existing->vendor_account = $vendorAccount;
                    }

                    if ($existing->term_payment === null || $existing->term_payment === '') {
                        $existing->term_payment = $termPayment;
                    }

                    $existing->save();
                    $this->updated++;
                } else {
                    Payableto::create([
                        'vendor_account' => $vendorAccount !== '' ? $vendorAccount : null,
                        'nama' => $nama,
                        'hari' => $hari,
                        'valid' => $valid,
                        'term_payment' => $termPayment,
                        'type' => $type,
                        'user_entry' => $this->userEntry,
                    ]);

                    $this->inserted++;
                }
            } catch (\Throwable $e) {
                $this->skipped++;
                $this->errors[] = "Baris {$excelRow}: " . $e->getMessage();
            }
        }
    }

    private function getValue($row, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (isset($row[$key])) {
                return $row[$key];
            }
        }

        return $default;
    }

    private function cleanValue($value): string
    {
        if ($value === null) {
            return '';
        }

        return trim((string) $value);
    }
}
