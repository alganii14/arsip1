<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Arsip;
use App\Models\User;

class UpdateExistingArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update arsip yang belum memiliki created_by
        $arsipsWithoutCreator = Arsip::whereNull('created_by')->get();

        if ($arsipsWithoutCreator->count() > 0) {
            // Ambil admin user sebagai default creator untuk arsip lama
            $defaultUser = User::where('role', 'admin')->first();

            if ($defaultUser) {
                foreach ($arsipsWithoutCreator as $arsip) {
                    $arsip->update(['created_by' => $defaultUser->id]);
                }

                $this->command->info("Updated {$arsipsWithoutCreator->count()} arsips with default creator.");
            } else {
                $this->command->error("No admin user found to set as default creator.");
            }
        } else {
            $this->command->info("All arsips already have creators assigned.");
        }
    }
}
