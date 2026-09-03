<?php

namespace App\Exports;

use App\Models\Finance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Finance::with(['category', 'dept', 'rek_sumber', 'bank', 'matauang', 'ppn', 'payableto', 'rektujuan'])
            ->select('finances.*')
            ->selectRaw('COALESCE(finances.form_submission_time, DATE(finances.created_at)) as submission_date')
            ->selectRaw("COALESCE(finances.final_validation_time, (SELECT DATE(created_at) FROM history_approval WHERE history_approval.id_finance = finances.id AND history_approval.status = 'approved 2' ORDER BY id DESC LIMIT 1)) as approved2_date");

        if (!empty($this->filters['submission_date_from'])) {
            $query->whereRaw('COALESCE(finances.form_submission_time, DATE(finances.created_at)) >= ?', [$this->filters['submission_date_from']]);
        }
        if (!empty($this->filters['submission_date_to'])) {
            $query->whereRaw('COALESCE(finances.form_submission_time, DATE(finances.created_at)) <= ?', [$this->filters['submission_date_to']]);
        }
        if (!empty($this->filters['approved2_date_from'])) {
            $query->whereRaw("COALESCE(finances.final_validation_time, (SELECT DATE(created_at) FROM history_approval WHERE history_approval.id_finance = finances.id AND history_approval.status = 'approved 2' ORDER BY id DESC LIMIT 1)) >= ?", [$this->filters['approved2_date_from']]);
        }
        if (!empty($this->filters['approved2_date_to'])) {
            $query->whereRaw("COALESCE(finances.final_validation_time, (SELECT DATE(created_at) FROM history_approval WHERE history_approval.id_finance = finances.id AND history_approval.status = 'approved 2' ORDER BY id DESC LIMIT 1)) <= ?", [$this->filters['approved2_date_to']]);
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('finances.invoice_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('finances.invoice_date', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['payable_to'])) {
            $query->whereHas('payableto', function ($q) {
                $q->where('nama', 'like', '%' . $this->filters['payable_to'] . '%');
            });
        }
        if (!empty($this->filters['doc_no'])) {
            $query->where('finances.doc_no', 'like', '%' . $this->filters['doc_no'] . '%');
        }
        if (!empty($this->filters['description'])) {
            $query->where('finances.description', 'like', '%' . $this->filters['description'] . '%');
        }
        if (!empty($this->filters['type'])) {
            $query->where('finances.type', $this->filters['type']);
        }
        if (!empty($this->filters['status'])) {
            $statuses = (array) $this->filters['status'];
            $query->whereIn('finances.status', $statuses);
        }

        return $query->orderBy('finances.invoice_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'TYPE',
            'RECEIPT DATE INVOICE FROM DIVISION',
            'SUBMISSION DATE',
            'UNIT HOSPITALS',
            'SUPPLIER NAME',
            'Invoice Date',
            'Document No',
            'DESCRIPTION',
            'STATUS',
            'APPROVED 2 DATE',
            'PAYMENT DATE',
            'PAYMENT TERM',
            'PO/AGREEMENT NO',
            'PO/AGREEMENT CATEGORY',
            'DEPT',
            'NAMA REKENING TUJUAN',
            'BANK TUJUAN',
            'NO REKENING TUJUAN',
            'CURRENCY',
            'Amount',
            'PPN (IDR)',
            'KURS /Rupiah',
            'COURIER SERVICE/OTHERS',
            'WITHHOLDING TAX (PPh 23 & 4(2))',
            'GRAND TOTAL IDR'
        ];
    }

    public function map($finance): array
    {
        $namaRekTujuan = $finance->nama_rekening_tujuan ?: ($finance->rektujuan->nama ?? '');
        $bankTujuan = $finance->bank->nama ?? ($finance->rektujuan->bank ?? '');
        $noRekTujuan = $finance->no_rek_tujuan ?: ($finance->rektujuan->norek ?? '');

        $receiptDate = $finance->created_at ? $finance->created_at->format('d-m-Y') : '';
        $submissionDate = $finance->submission_date 
            ? \Carbon\Carbon::parse($finance->submission_date)->format('d-m-Y') 
            : '';

        $approved2Date = $finance->approved2_date 
            ? \Carbon\Carbon::parse($finance->approved2_date)->format('d-m-Y') 
            : '';

        return [
            $finance->type,
            $receiptDate,
            $submissionDate,
            $finance->rek_sumber->nama ?? '',
            $finance->payableto->nama ?? '',
            $finance->invoice_date ? $finance->invoice_date->format('d-m-Y') : '',
            $finance->doc_no,
            $finance->description,
            $finance->status,
            $approved2Date,
            $finance->payment_date ? \Carbon\Carbon::parse($finance->payment_date)->format('d-m-Y') : '',
            $finance->payment_term,
            $finance->po_no,
            $finance->category->nama ?? '',
            $finance->dept->nama ?? '',
            $namaRekTujuan,
            $bankTujuan,
            $noRekTujuan,
            $finance->matauang->nama ?? '',
            $finance->dpp,
            $finance->nilai_ppn,
            '',
            '',
            ($finance->pph * -1),
            $finance->total_amount
        ];
    }
}
