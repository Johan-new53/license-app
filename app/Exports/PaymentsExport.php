<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payment_date;

    public function __construct($payment_date)
    {
        $this->payment_date = $payment_date;
    }

    public function collection()
    {
        return DB::table('finances')
            ->leftJoin('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')
            ->where('finances.status', 'paid')
            ->where('finances.due_date', '<=', $this->payment_date)
            ->select(
                'finances.invoice_date',
                'm_payableto.nama as nama_payable',
                'finances.top_hari',
                'finances.due_date',
                'finances.type',
                'finances.doc_no',
                'finances.description'
            )
            ->get();
    }

    // Optional: headings for Excel
    public function headings(): array
    {
        return [
            'Invoice Date',
            'Nama Payable',
            'TOP Hari',
            'Due Date',
            'Type',
            'Document No',
            'Description'
        ];
    }

    // Map the data for Excel: format dates as strings
    public function map($row): array
    {
        return [
            optional($row->invoice_date) ? date('d-m-Y', strtotime($row->invoice_date)) : '',
            $row->nama_payable,
            $row->top_hari,
            optional($row->due_date) ? date('d-m-Y', strtotime($row->due_date)) : '',
            $row->type,
            $row->doc_no,
            $row->description
        ];
    }
}