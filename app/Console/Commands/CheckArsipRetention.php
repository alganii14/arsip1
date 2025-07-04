<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arsip;

class CheckArsipRetention extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arsip:check-retention';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check arsip retention dates and move to JRE if necessary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking arsip retention dates...');

        // Check for arsips that have reached their retention date and automatically move to JRE
        $arsips = Arsip::where('is_archived_to_jre', false)
                      ->whereDate('retention_date', '<=', now())
                      ->get();

        $movedCount = 0;
        foreach ($arsips as $arsip) {
            if ($arsip->shouldMoveToJre()) {
                // Mark retention notification first
                $arsip->has_retention_notification = true;
                $arsip->save();

                // Then automatically move to JRE
                $jre = $arsip->moveToJre('Automatically moved to JRE by system when retention date reached');
                $this->info("Arsip {$arsip->kode} automatically moved to JRE with ID {$jre->id}");
                $movedCount++;
            }
        }

        $this->info("Retention check completed. {$movedCount} arsip automatically moved to JRE.");

        return Command::SUCCESS;
    }
}
