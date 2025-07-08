<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Arsip;
use App\Models\User;

class UpdateExistingArsipsCreatedBySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first admin user as default creator for existing arsips
        $defaultCreator = User::where('role', 'admin')->first();

        if (!$defaultCreator) {
            $this->command->error('No admin user found. Please create an admin user first.');
            return;
        }

        // Update all arsips that have NULL created_by
        $arsipsWithoutCreator = Arsip::whereNull('created_by')->get();

        $this->command->info("Found {$arsipsWithoutCreator->count()} arsips without creator.");

        foreach ($arsipsWithoutCreator as $arsip) {
            $arsip->created_by = $defaultCreator->id;
            $arsip->save();
        }

        $this->command->info("Updated {$arsipsWithoutCreator->count()} arsips with default creator: {$defaultCreator->name}");
    }
}
