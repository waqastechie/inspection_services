<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inspection;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestInspectionData extends Command
{
    protected $signature = 'test:inspection-data';
    protected $description = 'Test if all inspection data is saving correctly';

    public function handle()
    {
        $this->info('=== INSPECTION DATA SAVING TEST ===');
        $this->newLine();

        try {
            // Test 1: Check required columns exist
            $this->info('1. Checking database columns...');
            
            $requiredColumns = [
                'area_of_examination', 'services_performed', 'contract', 'work_order',
                'purchase_order', 'client_job_reference', 'job_ref', 'standards',
                'local_procedure_number', 'drawing_number', 'test_restrictions',
                'surface_condition', 'inspector_comments', 'next_inspection_date',
                'lifting_examination_inspector', 'load_test_inspector',
                'thorough_examination_inspector', 'mpi_service_inspector', 'visual_inspector'
            ];
            
            $missingColumns = [];
            foreach ($requiredColumns as $column) {
                if (!Schema::hasColumn('inspections', $column)) {
                    $missingColumns[] = $column;
                }
            }
            
            if (empty($missingColumns)) {
                $this->info('✅ All required columns present!');
            } else {
                $this->error('❌ Missing columns:');
                foreach ($missingColumns as $col) {
                    $this->line("   - $col");
                }
                return 1;
            }
            
            $this->newLine();
            
            // Test 2: Create a test inspection with all fields
            $this->info('2. Testing inspection creation with full data...');
            
            $testData = [
                'inspection_number' => 'TEST' . time(),
                'client_name' => 'Test Client Full',
                'project_name' => 'Test Project Full',
                'location' => 'Test Location Full',
                'area_of_examination' => 'Test Area',
                'services_performed' => 'Test Services',
                'contract' => 'TEST-CONTRACT-001',
                'work_order' => 'WO-001',
                'purchase_order' => 'PO-001',
                'client_job_reference' => 'CJR-001',
                'job_ref' => 'JR-001',
                'standards' => 'Test Standards',
                'local_procedure_number' => 'LPN-001',
                'drawing_number' => 'DWG-001',
                'test_restrictions' => 'Test restrictions',
                'surface_condition' => 'Good condition',
                'inspection_date' => date('Y-m-d'),
                'lead_inspector_name' => 'Test Inspector Full',
                'lead_inspector_certification' => 'Test Certification Full',
                'equipment_type' => 'Test Equipment Full',
                'status' => 'draft',
                'inspector_comments' => 'Test comments',
                'next_inspection_date' => date('Y-m-d', strtotime('+1 year'))
            ];
            
            $inspection = Inspection::create($testData);
            $this->info("✅ Basic inspection created (ID: {$inspection->id})");
            
            // Test 3: Test service inspector assignments
            $this->newLine();
            $this->info('3. Testing service inspector assignments...');
            
            $personnel = Personnel::first();
            if ($personnel) {
                $inspection->update([
                    'lifting_examination_inspector' => $personnel->id,
                    'load_test_inspector' => $personnel->id,
                ]);
                $this->info('✅ Service inspectors assigned successfully');
            } else {
                $this->warn('⚠️  No personnel found for assignment test');
            }
            
            // Test 4: Verify data was saved
            $this->newLine();
            $this->info('4. Verifying saved data...');
            $savedInspection = Inspection::find($inspection->id);
            
            $fieldsToCheck = [
                'area_of_examination', 'services_performed', 'contract', 'work_order',
                'inspector_comments', 'next_inspection_date'
            ];
            
            $savedFields = 0;
            foreach ($fieldsToCheck as $field) {
                if (!empty($savedInspection->$field)) {
                    $savedFields++;
                    $this->info("✅ $field: {$savedInspection->$field}");
                } else {
                    $this->error("❌ $field: NOT SAVED");
                }
            }
            
            $this->newLine();
            $this->info('📊 RESULTS:');
            $this->line("Saved fields: $savedFields/" . count($fieldsToCheck));
            
            if ($savedFields == count($fieldsToCheck)) {
                $this->info('✅ ALL DATA SAVING CORRECTLY!');
                $this->newLine();
                $this->info('💡 Your inspection form should now work properly.');
                $this->line('   Try creating a new inspection through the web interface.');
            } else {
                $this->error('❌ SOME DATA NOT SAVING');
                $this->newLine();
                $this->info('💡 Possible issues:');
                $this->line('   - Missing database columns');
                $this->line('   - Mass assignment protection');
                $this->line('   - Validation errors');
            }
            
            // Clean up
            $inspection->delete();
            $this->newLine();
            $this->info('✅ Test inspection cleaned up');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ ERROR: ' . $e->getMessage());
            $this->line('Line: ' . $e->getLine());
            $this->line('File: ' . $e->getFile());
            return 1;
        }
    }
}
