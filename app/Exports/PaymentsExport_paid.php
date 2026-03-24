<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport_paid implements FromCollection, WithHeadings, WithMapping
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
            ->leftJoin('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')
            ->leftJoin('m_category', 'finances.id_category', '=', 'm_category.id')
            ->leftJoin('m_dept', 'finances.id_dept', '=', 'm_dept.id')
            ->leftJoin('m_currency', 'finances.id_currency', '=', 'm_currency.id')
            ->where('finances.status', 'paid')
            ->where('finances.due_date', '=', $this->payment_date)
            ->select(
                'finances.created_at',
                'm_hu_rek_sumber.nama as nama_rek_sumber',
                'm_payableto.nama as nama_payable',
                'm_category.nama as nama_category',
                'm_dept.nama as nama_dept',
                'm_currency.nama as nama_currency',
                'finances.invoice_date',
                'finances.doc_no',
                'finances.description',
                'finances.payment_term',
                'finances.po_no',
                'finances.dpp',
                'finances.nilai_ppn',
                'finances.pph',
                'finances.total_amount'
            )
            ->get();
    }

    // Optional: headings for Excel
    public function headings(): array
    {
        return [
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

    // Map the data for Excel: format dates as strings
    public function map($row): array
    {
        return [
            optional($row->created_at) ? date('d-m-Y', strtotime($row->created_at)) : '',
            $row->nama_rek_sumber,
            $row->nama_payable,
            optional($row->invoice_date) ? date('d-m-Y', strtotime($row->invoice_date)) : '',
            $row->doc_no,
            $row->description,
            $row->payment_term,
            $row->po_no,
            $row->nama_category,
            $row->nama_dept,
            $row->nama_currency,
            $row->dpp,
            $row->nilai_ppn,
            '',
            '',
            $row->pph*-1,
            $row->total_amount           

        ];
    }
}