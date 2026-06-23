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
        $query = Finance::with(['category', 'dept', 'rek_sumber', 'bank', 'matauang', 'ppn', 'payableto']);

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('invoice_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('invoice_date', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['doc_no'])) {
            $query->where('doc_no', 'like', '%' . $this->filters['doc_no'] . '%');
        }
        if (!empty($this->filters['description'])) {
            $query->where('description', 'like', '%' . $this->filters['description'] . '%');
        }
        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }
        if (!empty($this->filters['status'])) {
            $statuses = (array) $this->filters['status'];
            $query->whereIn('status', $statuses);
        }

        return $query->orderBy('invoice_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'TYPE',
            'RECEIPT DATE INVOICE FROM DIVISION',
            'UNIT HOSPITALS',
            'SUPPLIER NAME',
            'Invoice Date',
            'Invoice No. (Kwitansi No.)',
            'DESCRIPTION',
            'PAYMENT TERM',
            'PO/AGREEMENT NO',
            'PO/AGREEMENT CATEGORY',
            'DEPT',
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
        return [
            $finance->type,
            $finance->created_at ? $finance->created_at->format('d-m-Y') : '',
            $finance->rek_sumber->nama ?? '',
            $finance->payableto->nama ?? '',
            $finance->invoice_date ? $finance->invoice_date->format('d-m-Y') : '',
            $finance->doc_no,
            $finance->description,
            $finance->payment_term,
            $finance->po_no,
            $finance->category->nama ?? '',
            $finance->dept->nama ?? '',
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
