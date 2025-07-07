<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jre;

class CleanupRecoveredJre extends Command
{
    protected $signature = 'jre:cleanup-recovered';
    protected $description = 'Clean up JRE records that have been recovered';

    public function handle()
    {
        $this->info('Cleaning up recovered JRE records...');

        // Find all JRE records with 'recovered' status
        $recoveredJres = Jre::where('status', 'recovered')->get();

        $deletedCount = 0;

        foreach ($recoveredJres as $jre) {
            try {
                $arsipKode = $jre->arsip->kode ?? 'Unknown';
                $jre->delete();
                $this->info("✓ Deleted recovered JRE record for arsip: {$arsipKode}");
                $deletedCount++;

            } catch (\Exception $e) {
                $this->error("✗ Failed to delete JRE record ID {$jre->id}: " . $e->getMessage());
            }
        }

        $this->info("Cleanup completed. {$deletedCount} recovered JRE records deleted.");

        return Command::SUCCESS;
    }
}
