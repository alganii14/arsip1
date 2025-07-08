<?php

namespace App\Http\Controllers;

use App\Models\ArchiveDestruction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArchiveDestructionsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ArchiveDestructionController extends Controller
{
    public function index()
    {
        $destructions = ArchiveDestruction::with(['arsip', 'jre', 'user'])
            ->orderBy('destroyed_at', 'desc')
            ->get();

        return view('archive-destructions.index', compact('destructions'));
    }

    public function show(ArchiveDestruction $archiveDestruction)
    {
        $archiveDestruction->load(['arsip', 'jre', 'user']);
        return view('archive-destructions.show', compact('archiveDestruction'));
    }

    public function exportExcel()
    {
        $destructions = ArchiveDestruction::with(['arsip', 'jre', 'user'])
            ->orderBy('destroyed_at', 'desc')
            ->get();

        return Excel::download(new ArchiveDestructionsExport($destructions),
            'Laporan_Riwayat_Pemusnahan_Arsip_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $destructions = ArchiveDestruction::with(['arsip', 'jre', 'user'])
            ->orderBy('destroyed_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('archive-destructions.pdf', compact('destructions'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Riwayat_Pemusnahan_Arsip_' . date('Y-m-d') . '.pdf');
    }
}
