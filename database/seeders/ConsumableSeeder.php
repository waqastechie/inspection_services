<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consumable;

class ConsumableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding consumables...');

        $consumables = [
            // Contrast Paints
            [
                'type' => 'Contrast Paint',
                'manufacturer' => 'Magnaflux',
                'description' => 'Spotcheck SKL-SP2 White Contrast Paint',
                'batch_number' => 'SP2024001',
                'expiry_date' => '2025-12-31',
                'unit' => 'can',
                'condition' => 'new',
                'services' => ['Magnetic Particle Testing', 'Liquid Penetrant Testing'],
                'is_active' => true,
            ],
            [
                'type' => 'Contrast Paint',
                'manufacturer' => 'Sherwin Williams',
                'description' => 'Fast Dry White Background Paint',
                'batch_number' => 'FD2024002',
                'expiry_date' => '2025-09-30',
                'unit' => 'can',
                'condition' => 'new',
                'services' => ['Magnetic Particle Testing'],
                'is_active' => true,
            ],

            // Ink
            [
                'type' => 'Ink',
                'manufacturer' => 'Magnaflux',
                'description' => '14AM Fluorescent Magnetic Particle Concentrate',
                'batch_number' => 'AM2024003',
                'expiry_date' => '2026-06-30',
                'unit' => 'liter',
                'condition' => 'new',
                'services' => ['Magnetic Particle Testing'],
                'is_active' => true,
            ],
            [
                'type' => 'Ink',
                'manufacturer' => 'Ardrox',
                'description' => 'P84D Fluorescent Penetrant',
                'batch_number' => 'P84-2024004',
                'expiry_date' => '2025-11-15',
                'unit' => 'liter',
                'condition' => 'new',
                'services' => ['Liquid Penetrant Testing'],
                'is_active' => true,
            ],
            [
                'type' => 'Ink',
                'manufacturer' => 'Sherwin Williams',
                'description' => 'Red Dye Penetrant Spray',
                'batch_number' => 'RD2024005',
                'expiry_date' => '2025-08-31',
                'unit' => 'can',
                'condition' => 'new',
                'services' => ['Liquid Penetrant Testing'],
                'is_active' => true,
            ],

            // Cleaner
            [
                'type' => 'Cleaner',
                'manufacturer' => 'Magnaflux',
                'description' => 'SKC-S Solvent Cleaner/Remover',
                'batch_number' => 'SKC2024006',
                'expiry_date' => '2026-03-31',
                'unit' => 'liter',
                'condition' => 'new',
                'services' => ['Magnetic Particle Testing', 'Liquid Penetrant Testing'],
                'is_active' => true,
            ],
            [
                'type' => 'Cleaner',
                'manufacturer' => 'Ardrox',
                'description' => '9D1B Hydrophilic Remover',
                'batch_number' => '9D1-2024007',
                'expiry_date' => '2025-10-31',
                'unit' => 'liter',
                'condition' => 'new',
                'services' => ['Liquid Penetrant Testing'],
                'is_active' => true,
            ],
            [
                'type' => 'Cleaner',
                'manufacturer' => 'Sherwin Williams',
                'description' => 'Universal Degreaser Spray',
                'batch_number' => 'UD2024008',
                'expiry_date' => '2025-12-15',
                'unit' => 'can',
                'condition' => 'new',
                'services' => ['Magnetic Particle Testing', 'Liquid Penetrant Testing', 'Ultrasonic Testing'],
                'is_active' => true,
            ],

            // Additional common consumables
            [
                'type' => 'Developer',
                'manufacturer' => 'Ardrox',
                'description' => 'D70 Non-Aqueous Developer Spray',
                'batch_number' => 'D70-2024009',
                'expiry_date' => '2025-09-30',
                'unit' => 'can',
                'condition' => 'new',
                'services' => ['Liquid Penetrant Testing'],
                'is_active' => true,
            ],
            [
                'type' => 'Couplant',
                'manufacturer' => 'Sonotec',
                'description' => 'Ultrasonic Couplant Gel',
                'batch_number' => 'UC2024010',
                'expiry_date' => '2025-12-31',
                'unit' => 'bottle',
                'condition' => 'new',
                'services' => ['Ultrasonic Testing'],
                'is_active' => true,
            ],
        ];

        foreach ($consumables as $consumableData) {
            Consumable::create($consumableData);
        }

        $this->command->info('✓ Consumables seeded successfully (' . count($consumables) . ' items)');
    }
}
