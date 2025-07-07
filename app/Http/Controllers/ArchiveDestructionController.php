<?php

namespace App\Http\Controllers;

use App\Models\ArchiveDestruction;
use Illuminate\Http\Request;

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
}
