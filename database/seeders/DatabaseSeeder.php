<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Subclasse;
use Illuminate\Database\Seeder;
use Database\Seeders\ClassesSeeder; // Importa o ClassesSeeder
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama o seeder das classes
        $this->call(ClassesSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(StreetConditionSeeder::class);
        $this->call(SubclasseSeeder::class);
        $this->call(ActivitieSeeder::class);
        $this->call(ActivitieSeeder2::class);
        $this->call(StreetSeeder::class);
        $this->call(StreetSeeder2::class);
        $this->call(IconSeeder::class);
        
        // \App\Models\User::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
