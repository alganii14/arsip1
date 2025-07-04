<?php

namespace App\Observers;

use App\Models\Arsip;
use Carbon\Carbon;

class ArsipObserver
{
    /**
     * Handle the Arsip "created" event.
     */
    public function created(Arsip $arsip): void
    {
        // Calculate retention date when arsip is created
        $arsip->calculateRetentionDate();
    }

    /**
     * Handle the Arsip "updated" event.
     */
    public function updated(Arsip $arsip): void
    {
        // Check if retention date has changed and recalculate
        if ($arsip->wasChanged('tanggal_arsip') && !$arsip->is_archived_to_jre) {
            $arsip->calculateRetentionDate();
        }
    }

    /**
     * Handle the Arsip "retrieved" event.
     */
    public function retrieved(Arsip $arsip): void
    {
        // Auto-move to JRE if expired when arsip is retrieved
        if (!$arsip->is_archived_to_jre && $arsip->shouldMoveToJre()) {
            $arsip->autoMoveToJreIfExpired();
        }
    }
}
