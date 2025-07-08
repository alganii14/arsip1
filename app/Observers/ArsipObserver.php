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
        // Retention date calculation is now handled in the controller
        // to avoid conflicts with manual retention settings
    }

    /**
     * Handle the Arsip "updated" event.
     */
    public function updated(Arsip $arsip): void
    {
        // Observer now only handles auto-move to JRE
        // Retention date calculation is handled in the controller
        // to avoid conflicts with manual retention settings
    }

    /**
     * Handle the Arsip "retrieved" event.
     */
    public function retrieved(Arsip $arsip): void
    {
        // Don't auto-move to JRE if we're in a pemindahan context
        // Check if current request is related to pemindahan
        $currentRoute = request()->route();
        if ($currentRoute) {
            $routeName = $currentRoute->getName();
            if (str_contains($routeName ?? '', 'pemindahan')) {
                return; // Don't auto-move when handling pemindahan
            }
        }

        // Auto-move to JRE if expired when arsip is retrieved
        if (!$arsip->is_archived_to_jre && $arsip->shouldMoveToJre()) {
            $arsip->autoMoveToJreIfExpired();
        }
    }
}
