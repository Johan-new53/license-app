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
        $query = Finance::with(['category', 'dept', 'rek_sumber', 'bank', 'matauang', 'ppn'])
            ->where(function($q) {
                $q->where('status', 'LIKE', 'approved%')
                  ->orWhere('status', 'paid');
            });

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
            $query->where('status', 'like', $this->filters['status'] . '%');
        }

        return $query->orderBy('invoice_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Module',
            'Doc Number',
            'Description',
            'Department',
            'Amount',
            'Currency',
            'Rekening Tujuan',
            'Invoice Date',
            'Payment Date',
            'Status'
        ];
    }

    public function map($finance): array
    {
        static $no = 1;
        
        $rek_tujuan = $finance->nama_rekening_tujuan 
            ? $finance->nama_rekening_tujuan . " (" . ($finance->bank->nama ?? '') . " - " . $finance->no_rek_tujuan . ")"
            : ($finance->rektujuan->nama ?? '-');

        return [
            $no++,
            ucfirst($finance->type),
            $finance->doc_no,
            $finance->description,
            $finance->dept->nama ?? '-',
            number_format($finance->total_amount, 2),
            $finance->matauang->nama ?? '-',
            $rek_tujuan,
            $finance->invoice_date ? $finance->invoice_date->format('d-m-Y') : '-',
            $finance->payment_date ? date('d-m-Y', strtotime($finance->payment_date)) : '-',
            $finance->status
        ];
    }
}
