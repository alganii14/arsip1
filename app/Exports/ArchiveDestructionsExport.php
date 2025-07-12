<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ArchiveDestructionsExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithMapping, ShouldAutoSize
{
    protected $destructions;

    public function __construct($destructions)
    {
        $this->destructions = $destructions;
    }

    public function collection()
    {
        return $this->destructions;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Arsip',
            'Nama Dokumen',
            'Kategori',
            'Tanggal Arsip',
            'Dibuat oleh',
            'Tanggal Pemusnahan',
            'Petugas',
            'Catatan Pemusnahan'
        ];
    }

    public function map($destruction): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $destruction->arsip->kode,
            $destruction->arsip->nama_dokumen,
            $destruction->arsip->kategori,
            $destruction->arsip->tanggal_arsip ? $destruction->arsip->tanggal_arsip->format('d/m/Y') : '-',
            $destruction->arsip->creator->name ?? '-',
            $destruction->destroyed_at->format('d/m/Y H:i'),
            $destruction->user->name,
            $destruction->destruction_notes
        ];
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
            ],
            // Add borders to all cells
            'A1:I' . ($this->destructions->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]
        ];
    }

    public function title(): string
    {
        return 'Riwayat Pemusnahan Arsip';
    }
}
