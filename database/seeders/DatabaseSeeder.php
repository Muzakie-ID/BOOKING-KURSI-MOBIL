<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        // Routes
        $r1 = \App\Models\Route::create([
            'origin' => 'Semarang',
            'destination' => 'Purworejo',
            'price_estimate_min' => 75000,
            'price_estimate_max' => 80000,
        ]);
        
        $r2 = \App\Models\Route::create([
            'origin' => 'Purworejo',
            'destination' => 'Semarang',
            'price_estimate_min' => 75000,
            'price_estimate_max' => 80000,
        ]);

        // Drop Off Points
        $r1->dropOffPoints()->createMany([
            ['name' => 'Alun-alun Purworejo'],
            ['name' => 'Terminal Kutoarjo'],
            ['name' => 'Pasar Baledono']
        ]);

        $r2->dropOffPoints()->createMany([
            ['name' => 'Sukun'],
            ['name' => 'Jatingaleh'],
            ['name' => 'Terboyo'],
            ['name' => 'Simpang Lima']
        ]);

        // Fleets
        \App\Models\Fleet::create([
            'name' => 'Brio Merah',
            'type' => 'small',
            'capacity' => 4,
            'row_layout' => [
                ['label' => 'Depan', 'seats' => [1]], // Samping supir
                ['label' => 'Belakang', 'seats' => [2, 3, 4]],
            ]
        ]);

        \App\Models\Fleet::create([
            'name' => 'Avanza Silver',
            'type' => 'standard',
            'capacity' => 7,
            'row_layout' => [
                ['label' => 'Depan', 'seats' => [1]],
                ['label' => 'Tengah', 'seats' => [2, 3, 4]],
                ['label' => 'Belakang', 'seats' => [5, 6, 7]],
            ]
        ]);
    }
}
