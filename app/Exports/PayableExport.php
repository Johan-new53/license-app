<?php

namespace App\Exports;

use App\Models\Payableto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PayableExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithStrictNullComparison
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $type = $this->request->type ?? 'hardcopy';

        $query = Payableto::where('type', $type);

        if ($this->request->filled('nama')) {
            $query->where('nama', 'LIKE', '%' . $this->request->nama . '%');
        }

        if ($this->request->filled('vendor_account')) {
            $query->where('vendor_account', 'LIKE', '%' . $this->request->vendor_account . '%');
        }

        if ($this->request->filled('hari')) {
            $query->where('hari', $this->request->hari);
        }

        if ($this->request->filled('valid')) {
            $query->where('valid', $this->request->valid);
        }

        return $query->orderBy('nama', 'ASC')->get()->map(function ($item) {
            return [
                (string) ($item->vendor_account ?? ''),
                (string) ($item->nama ?? ''),
                $item->hari === null || $item->hari === '' ? 0 : (int) $item->hari,
                $item->valid === null || $item->valid === '' ? 0 : (int) $item->valid,
                (string) ucfirst($item->type ?? ''),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Vendor Account',
            'Name',
            'TOP (Hari)',
            'Valid',
            'Type',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $headerRange = "A1:{$highestColumn}1";
                $dataRange = "A1:{$highestColumn}{$highestRow}";
                $hariRange = "C2:C{$highestRow}";
                $validRange = "D2:D{$highestRow}";
                $typeRange = "E2:E{$highestRow}";

                $sheet->freezePane('A2');

                $sheet->getStyle($headerRange)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'D9EAF7',
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle($hariRange)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $sheet->getStyle($validRange)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $sheet->getStyle($hariRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle($validRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle($typeRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
