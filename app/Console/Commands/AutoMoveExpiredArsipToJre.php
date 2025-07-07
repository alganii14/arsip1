<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arsip;
use Carbon\Carbon;

class AutoMoveExpiredArsipToJre extends Command
{
    protected $signature = 'arsip:auto-move-expired';
    protected $description = 'Automatically move expired arsips to JRE';

    public function handle()
    {
        $this->info('Starting automatic movement of expired arsips to JRE...');

        // Get all arsips that have reached their retention date
        $expiredArsips = Arsip::active()
            ->whereNotNull('retention_date')
            ->whereDate('retention_date', '<=', Carbon::now())
            ->get();

        $movedCount = 0;

        foreach ($expiredArsips as $arsip) {
            try {
                // Move to JRE
                $jre = $arsip->moveToJre('Automatically moved to JRE when retention date reached on ' . Carbon::now()->format('Y-m-d H:i:s'));

                $this->info("✓ Arsip [{$arsip->kode}] moved to JRE (ID: {$jre->id})");
                $movedCount++;

            } catch (\Exception $e) {
                $this->error("✗ Failed to move arsip [{$arsip->kode}]: " . $e->getMessage());
            }
        }

        $this->info("Completed. {$movedCount} arsips moved to JRE.");

        return Command::SUCCESS;
    }
}
