<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PeminjamanArsipExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $peminjamans;

    public function __construct($peminjamans)
    {
        $this->peminjamans = $peminjamans;
    }

    public function collection()
    {
        return $this->peminjamans;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Arsip',
            'Nama Dokumen',
            'Peminjam',
            'Jabatan',
            'Departemen',
            'Kontak',
            'Tanggal Pinjam',
            'Batas Waktu',
            'Tanggal Kembali',
            'Status',
            'Konfirmasi',
            'Durasi (Hari)',
            'Tujuan Peminjaman',
            'Petugas Peminjaman',
            'Petugas Pengembalian',
            'Catatan'
        ];
    }

    public function map($peminjaman): array
    {
        static $no = 1;

        $statusText = '';
        switch($peminjaman->status) {
            case 'dipinjam':
                $statusText = 'Dipinjam';
                break;
            case 'dikembalikan':
                $statusText = 'Dikembalikan';
                break;
            case 'terlambat':
                $statusText = 'Terlambat';
                break;
            case 'pending':
                $statusText = 'Pending';
                break;
            default:
                $statusText = ucfirst($peminjaman->status);
        }

        $confirmationText = '';
        switch($peminjaman->confirmation_status) {
            case 'pending':
                $confirmationText = 'Pending';
                break;
            case 'approved':
                $confirmationText = 'Disetujui';
                break;
            case 'rejected':
                $confirmationText = 'Ditolak';
                break;
            default:
                $confirmationText = 'N/A';
        }

        return [
            $no++,
            $peminjaman->arsip->kode ?? 'N/A',
            $peminjaman->arsip->nama_dokumen ?? 'N/A',
            $peminjaman->peminjam,
            $peminjaman->jabatan ?? '-',
            $peminjaman->departemen ?? '-',
            $peminjaman->kontak,
            $peminjaman->tanggal_pinjam->format('d/m/Y'),
            $peminjaman->batas_waktu->format('d/m/Y'),
            $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-',
            $statusText,
            $confirmationText,
            $peminjaman->getDurasiPinjam() ?? '-',
            $peminjaman->tujuan_peminjaman ?? '-',
            $peminjaman->petugas_peminjaman ?? '-',
            $peminjaman->petugas_pengembalian ?? '-',
            $peminjaman->catatan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style for header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'color' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
            // Style for all data rows
            'A2:Q' . ($this->peminjamans->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
