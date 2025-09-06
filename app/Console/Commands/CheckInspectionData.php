<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inspection;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckInspectionData extends Command
{
    protected $signature = 'check:inspection-data';
    protected $description = 'Check if inspection data is saving and displaying correctly';

    public function handle()
    {
        $this->info('=== CHECKING LATEST INSPECTION DATA ===');
        $this->newLine();

        try {
            // Get the latest inspection
            $inspection = Inspection::latest()->first();
            
            if (!$inspection) {
                $this->error('❌ No inspections found in database');
                return 1;
            }
            
            $this->info("📋 Inspection ID: {$inspection->id}");
            $this->info("📋 Inspection Number: {$inspection->inspection_number}");
            $this->info("📅 Created: {$inspection->created_at}");
            $this->newLine();
            
            // Check basic fields
            $this->info('=== BASIC INFORMATION ===');
            $this->line("Client: " . ($inspection->client_name ?? 'NULL'));
            $this->line("Project: " . ($inspection->project_name ?? 'NULL'));
            $this->line("Location: " . ($inspection->location ?? 'NULL'));
            $this->line("Inspection Date: " . ($inspection->inspection_date ?? 'NULL'));
            $this->line("Lead Inspector: " . ($inspection->lead_inspector_name ?? 'NULL'));
            $this->line("Certification: " . ($inspection->lead_inspector_certification ?? 'NULL'));
            $this->line("Weather: " . ($inspection->weather_conditions ?? 'NULL'));
            $this->newLine();
            
            // Check additional fields that might not be saving
            $this->info('=== ADDITIONAL FIELDS ===');
            $this->line("Area of Examination: " . ($inspection->area_of_examination ?? 'NULL'));
            $this->line("Services Performed: " . ($inspection->services_performed ?? 'NULL'));
            $this->line("Contract: " . ($inspection->contract ?? 'NULL'));
            $this->line("Work Order: " . ($inspection->work_order ?? 'NULL'));
            $this->line("Purchase Order: " . ($inspection->purchase_order ?? 'NULL'));
            $this->line("Job Reference: " . ($inspection->job_ref ?? 'NULL'));
            $this->line("Standards: " . ($inspection->standards ?? 'NULL'));
            $this->line("Inspector Comments: " . ($inspection->inspector_comments ?? 'NULL'));
            $this->newLine();
            
            // Check equipment information
            $this->info('=== EQUIPMENT INFORMATION ===');
            $this->line("Equipment Type: " . ($inspection->equipment_type ?? 'NULL'));
            $this->line("Equipment Description: " . ($inspection->equipment_description ?? 'NULL'));
            $this->line("Manufacturer: " . ($inspection->manufacturer ?? 'NULL'));
            $this->line("Model: " . ($inspection->model ?? 'NULL'));
            $this->line("Serial Number: " . ($inspection->serial_number ?? 'NULL'));
            $this->line("Capacity: " . ($inspection->capacity ?? 'NULL'));
            $this->newLine();
            
            // Check service inspectors
            $this->info('=== SERVICE INSPECTORS ===');
            $this->line("Lifting Examination Inspector: " . ($inspection->lifting_examination_inspector ?? 'NULL'));
            $this->line("Load Test Inspector: " . ($inspection->load_test_inspector ?? 'NULL'));
            $this->line("Thorough Examination Inspector: " . ($inspection->thorough_examination_inspector ?? 'NULL'));
            $this->line("MPI Service Inspector: " . ($inspection->mpi_service_inspector ?? 'NULL'));
            $this->line("Visual Inspector: " . ($inspection->visual_inspector ?? 'NULL'));
            $this->newLine();
            
            // Summary
            $totalFields = 0;
            $filledFields = 0;
            
            $fieldsToCheck = [
                'area_of_examination', 'services_performed', 'contract', 'work_order',
                'purchase_order', 'job_ref', 'standards', 'inspector_comments',
                'equipment_description', 'manufacturer', 'model', 'serial_number',
                'lifting_examination_inspector', 'load_test_inspector'
            ];
            
            foreach ($fieldsToCheck as $field) {
                $totalFields++;
                if (!empty($inspection->$field)) {
                    $filledFields++;
                }
            }
            
            $this->info('📊 SUMMARY:');
            $this->line("Fields filled: $filledFields/$totalFields");
            
            if ($filledFields < $totalFields / 2) {
                $this->error('❌ PROBLEM: Most fields are empty - likely a form submission issue');
                $this->info('💡 Possible causes:');
                $this->line('   - Form not sending all data');
                $this->line('   - JavaScript issues');
                $this->line('   - Validation blocking fields');
                $this->line('   - Controller not processing all fields');
            } else {
                $this->info('✅ Data seems to be saving properly');
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ ERROR: ' . $e->getMessage());
            return 1;
        }
    }
}
