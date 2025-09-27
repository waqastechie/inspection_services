<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InspectionServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_type' => 'lifting_examination',
                'service_name' => 'Lifting Examination',
                'service_description' => 'Inspection and certification of lifting equipment.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'service_type' => 'mpi',
                'service_name' => 'Magnetic Particle Inspection',
                'service_description' => 'MPI for surface and near-surface defect detection.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'service_type' => 'visual',
                'service_name' => 'Visual Inspection',
                'service_description' => 'Visual inspection for general condition and compliance.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('inspection_services')->insert($services);
    }
}
