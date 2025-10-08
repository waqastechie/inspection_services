<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inspection;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckSpecificInspection extends Command
{
    protected $signature = 'check:inspection {id}';
    protected $description = 'Check specific inspection data';

    public function handle()
    {
        $inspectionId = $this->argument('id');
        
        $this->info("=== CHECKING INSPECTION ID: {$inspectionId} ===");
        $this->newLine();

        try {
            $inspection = Inspection::find($inspectionId);
            
            if (!$inspection) {
                $this->error("❌ Inspection ID {$inspectionId} not found");
                return 1;
            }
            
            $this->info("📋 Inspection Number: {$inspection->inspection_number}");
            $this->info("📅 Created: {$inspection->created_at}");
            $this->info("📅 Updated: {$inspection->updated_at}");
            $this->newLine();
            
            // Check ALL fields systematically
            $this->info('=== RAW DATA DUMP ===');
            
            $allFields = [
                'client_name', 'project_name', 'location', 'area_of_examination',
                'services_performed', 'contract', 'work_order', 'purchase_order',
                'client_job_reference', 'job_ref', 'standards', 'local_procedure_number',
                'drawing_number', 'test_restrictions', 'surface_condition',
                'inspection_date', 'lead_inspector_name', 'lead_inspector_certification',
                'weather_conditions', 'temperature', 'humidity',
                'equipment_type', 'equipment_description', 'manufacturer', 'model',
                'serial_number', 'asset_tag', 'manufacture_year', 'capacity',
                'capacity_unit', 'inspector_comments', 'recommendations',
                'defects_found', 'overall_result', 'next_inspection_date',
                'general_notes', 'status', 'lifting_examination_inspector',
                'load_test_inspector', 'thorough_examination_inspector',
                'mpi_service_inspector', 'visual_inspector'
            ];
            
            $filledCount = 0;
            $emptyCount = 0;
            
            foreach ($allFields as $field) {
                $value = $inspection->$field;
                if (!empty($value)) {
                    $this->info("✅ {$field}: {$value}");
                    $filledCount++;
                } else {
                    $this->line("❌ {$field}: EMPTY/NULL");
                    $emptyCount++;
                }
            }
            
            $this->newLine();
            $this->info("📊 SUMMARY:");
            $this->line("Filled fields: {$filledCount}");
            $this->line("Empty fields: {$emptyCount}");
            $this->line("Total fields: " . count($allFields));
            
            $fillPercentage = round(($filledCount / count($allFields)) * 100, 1);
            $this->line("Fill percentage: {$fillPercentage}%");
            
            if ($fillPercentage < 30) {
                $this->error('❌ CRITICAL: Very low data fill rate - likely form submission issue');
            } elseif ($fillPercentage < 60) {
                $this->warn('⚠️  WARNING: Low data fill rate - possible partial submission');
            } else {
                $this->info('✅ Good data fill rate');
            }
            
            // Check related data
            $this->newLine();
            $this->info('=== RELATED DATA ===');
            $this->line("Services: " . $inspection->services->count());
            $this->line("Personnel Assignments: " . $inspection->personnelAssignments()->count());
            $this->line("Equipment Assignments: " . $inspection->equipmentAssignments()->count());
            $this->line("Consumable Assignments: " . $inspection->consumableAssignments()->count());
            $this->line("Inspection Results: " . $inspection->inspectionResults()->count());
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ ERROR: ' . $e->getMessage());
            return 1;
        }
    }
}
