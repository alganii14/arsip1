<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ArchiveDestructionsExport implements FromView, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $destructions;

    public function __construct($destructions)
    {
        $this->destructions = $destructions;
    }

    public function view(): View
    {
        return view('exports.archive-destructions-excel', [
            'destructions' => $this->destructions
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold and with background color
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set border for the header and data
                $lastRow = $event->sheet->getHighestRow();
                $lastColumn = $event->sheet->getHighestColumn();
                
                $event->sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
                
                // Set column widths
                $event->sheet->getColumnDimension('A')->setWidth(5);  // No
                $event->sheet->getColumnDimension('B')->setWidth(15); // Kode Arsip
                $event->sheet->getColumnDimension('C')->setWidth(30); // Nama Dokumen
                $event->sheet->getColumnDimension('D')->setWidth(15); // Kategori
                $event->sheet->getColumnDimension('E')->setWidth(12); // Tanggal Arsip
                $event->sheet->getColumnDimension('F')->setWidth(18); // Tanggal Pemusnahan
                $event->sheet->getColumnDimension('G')->setWidth(15); // Petugas
                $event->sheet->getColumnDimension('H')->setWidth(25); // Catatan Pemusnahan
                $event->sheet->getColumnDimension('I')->setWidth(50); // Catatan Lengkap
            },
        ];
    }

    public function title(): string
    {
        return 'Riwayat Pemusnahan Arsip';
    }
}
