<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InspectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inspectors = [
            [
                'name' => 'MASOOD RAHMAN',
                'email' => 'masood.rahman@inspection.com',
                'password' => Hash::make('password123'),
                'role' => 'inspector',
                'is_active' => true,
                'phone' => '+1-555-0101',
                'department' => 'NDT Inspection',
                'certification' => 'MT/PT/UT/VT/EMI-LEVEL 2-LEEA-LAC, OCE',
                'certification_expiry' => now()->addYears(2),
            ],
            [
                'name' => 'JOHN SMITH',
                'email' => 'john.smith@inspection.com',
                'password' => Hash::make('password123'),
                'role' => 'inspector',
                'is_active' => true,
                'phone' => '+1-555-0102',
                'department' => 'Lifting Equipment',
                'certification' => 'VT/MPI-LEVEL 2-LEEA-LAC',
                'certification_expiry' => now()->addYears(1),
            ],
            [
                'name' => 'SARAH JOHNSON',
                'email' => 'sarah.johnson@inspection.com',
                'password' => Hash::make('password123'),
                'role' => 'inspector',
                'is_active' => true,
                'phone' => '+1-555-0103',
                'department' => 'Structural Inspection',
                'certification' => 'UT/RT-LEVEL 2-AWS-CWI',
                'certification_expiry' => now()->addMonths(18),
            ],
            [
                'name' => 'AHMED ALI',
                'email' => 'ahmed.ali@inspection.com',
                'password' => Hash::make('password123'),
                'role' => 'inspector',
                'is_active' => true,
                'phone' => '+1-555-0104',
                'department' => 'Marine Inspection',
                'certification' => 'VT/MPI/PT-LEVEL 2-DNV-GL',
                'certification_expiry' => now()->addYears(3),
            ],
            [
                'name' => 'MIKE WILSON',
                'email' => 'mike.wilson@inspection.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => '+1-555-0105',
                'department' => 'Administration',
                'certification' => 'Management/All Methods-LEVEL 3',
                'certification_expiry' => now()->addYears(5),
            ],
        ];

        foreach ($inspectors as $inspector) {
            User::updateOrCreate(
                ['email' => $inspector['email']],
                $inspector
            );
        }

        $this->command->info('Inspector users seeded successfully!');
    }
}
