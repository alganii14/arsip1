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
    
        // Check for arsips that have reached their retention date
        $arsips = Arsip::where('is_archived_to_jre', false)
                      ->whereDate('retention_date', '<=', now())
                      ->where('has_retention_notification', false)
                      ->get();
        
        foreach ($arsips as $arsip) {
            $arsip->has_retention_notification = true;
            $arsip->save();
            $this->info("Arsip {$arsip->kode} marked for retention notification.");
        }
        
        // Check for arsips that should be moved to JRE
        $jreArsips = Arsip::where('is_archived_to_jre', false)
                         ->whereDate('retention_date', '<=', now())
                         ->get();
        
        foreach ($jreArsips as $arsip) {
            if ($arsip->shouldMoveToJre()) {
                $jre = $arsip->moveToJre('Automatically moved to JRE by system');
                $this->info("Arsip {$arsip->kode} moved to JRE with ID {$jre->id}");
            }
        }
        
        $this->info('Retention check completed.');
        
        return Command::SUCCESS;
    }
}
