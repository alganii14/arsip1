<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\ArchiveDestruction;
use App\Models\Jre;

class TestMusnahkanArsip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:musnahkan-arsip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test destroy archive functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing destroy archive functionality...');

        // Test database connection
        try {
            DB::connection()->getPdo();
            $this->info('✓ Database connection: OK');
        } catch (\Exception $e) {
            $this->error('✗ Database connection: FAILED - ' . $e->getMessage());
            return;
        }

        // Check if archive_destructions table exists
        if (Schema::hasTable('archive_destructions')) {
            $this->info('✓ archive_destructions table: EXISTS');
        } else {
            $this->error('✗ archive_destructions table: NOT EXISTS');
        }

        // Test ArchiveDestruction model
        try {
            $model = new ArchiveDestruction();
            $this->info('✓ ArchiveDestruction model: OK');
        } catch (\Exception $e) {
            $this->error('✗ ArchiveDestruction model: FAILED - ' . $e->getMessage());
        }

        // Test if there are any JRE records
        $jreCount = Jre::count();
        $this->info("JRE records count: $jreCount");

        if ($jreCount > 0) {
            $this->info('✓ There are JRE records to test with');
        } else {
            $this->warn('! No JRE records found for testing');
        }

        $this->info('Test completed!');
    }
}
