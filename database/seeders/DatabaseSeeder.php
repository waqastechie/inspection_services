<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        
        // Seed users and roles first
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            PersonnelSeeder::class,
            EquipmentSeeder::class,
            ConsumableSeeder::class,
            InspectionSeeder::class,
        ]);
        
        $this->command->info('Database seeding completed successfully!');
    }
}
