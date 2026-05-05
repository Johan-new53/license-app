<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Example row data
        return [
            [
                'hardcopy', // type
                'PO-12345', // PO Number
                '2024-01-01 10:00:00', // Form Submission Time
                '2024-01-02 14:00:00', // Final Validation Time
                'example@email.com', // email
                'Finance', // Requesting Department
                'Siloam Hospitals - 1234567890', // Hospital Unit & Rekening Sumber
                'Vendor Name', // Payable To
                'John Doe', // Nama Rekening Tujuan
                'BCA', // Bank Tujuan
                '1234567890', // VA Number (no rekening tujuan)
                '2024-01-10', // Invoice Date
                'INV/2024/001', // Document number(s)
                'Description of payment', // description
                'IDR', // currency
                '1000000', // dpp
                '11', // PPN Persen
                '110000', // ppn (nominal)
                '0', // pph
                '1110000', // Total Amount
                'File.pdf', // Input file
                'TRUE', // is_sent
                '30', // Payment Term
                'JRN-001', // Journal Number
                'approved', // status
                '2024-02-10', // Due Date
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'type',
            'PO Number',
            'Form Submission Time',
            'Final Validation Time',
            'email',
            'Requesting Department',
            'Hospital Unit & Rekening Sumber',
            'Payable To',
            'Nama Rekening Tujuan',
            'Bank Tujuan',
            'VA Number (no rekening tujuan)',
            'Invoice Date',
            'Document number(s)',
            'description',
            'currency',
            'dpp',
            'PPN Persen',
            'ppn',
            'pph',
            'Total Amount',
            'Input file',
            'is_sent',
            'Payment Term',
            'Journal Number',
            'status',
            'Due Date',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
