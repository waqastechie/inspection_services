<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inspection;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;

class TestInspectionSave extends Command
{
    protected $signature = 'test:inspection-save';
    protected $description = 'Test if inspection data saves correctly after the fix';

    public function handle()
    {
        $this->info('=== TESTING INSPECTION SAVE AFTER FIX ===');
        $this->newLine();

        try {
            DB::beginTransaction();
            
            // Create test data exactly like the form would send
            $testData = [
                'inspection_number' => 'TESTFIX' . time(),
                'client_name' => 'Test Client After Fix',
                'project_name' => 'Test Project After Fix',
                'location' => 'Test Location After Fix',
                'area_of_examination' => 'Test Area After Fix',
                'services_performed' => 'Test Services After Fix',
                'contract' => 'TEST-CONTRACT-FIX',
                'work_order' => 'WO-FIX-001',
                'purchase_order' => 'PO-FIX-001',
                'client_job_reference' => 'CJR-FIX-001',
                'job_ref' => 'JR-FIX-001',
                'standards' => 'Test Standards After Fix',
                'local_procedure_number' => 'LPN-FIX-001',
                'drawing_number' => 'DWG-FIX-001',
                'test_restrictions' => 'Test restrictions after fix',
                'surface_condition' => 'Good surface condition after fix',
                'inspection_date' => date('Y-m-d'),
                'lead_inspector_name' => 'Test Inspector After Fix',
                'lead_inspector_certification' => 'Test Certification After Fix',
                'equipment_type' => 'Test Equipment After Fix',
                'equipment_description' => 'Test Description After Fix',
                'manufacturer' => 'Test Manufacturer',
                'model' => 'Test Model',
                'serial_number' => 'TEST-SN-FIX',
                'capacity' => 100.50,
                'capacity_unit' => 'tonnes',
                'weather_conditions' => 'Clear after fix',
                'temperature' => 25.5,
                'humidity' => 65,
                'inspector_comments' => 'Test comments after the fix',
                'next_inspection_date' => date('Y-m-d', strtotime('+1 year')),
                'general_notes' => 'Test notes after fix',
                'status' => 'completed',
                'report_date' => now(),
            ];
            
            // Add service inspectors if personnel exists
            $personnel = Personnel::first();
            if ($personnel) {
                $testData['lifting_examination_inspector'] = $personnel->id;
                $testData['load_test_inspector'] = $personnel->id;
                $testData['thorough_examination_inspector'] = $personnel->id;
                $testData['mpi_service_inspector'] = $personnel->id;
                $testData['visual_inspector'] = $personnel->id;
                $this->info("✅ Added service inspectors using personnel ID: {$personnel->id}");
            } else {
                $this->warn("⚠️  No personnel found - skipping service inspector assignments");
            }
            
            $this->info('📝 Creating inspection with all fields...');
            $inspection = Inspection::create($testData);
            $this->info("✅ Inspection created with ID: {$inspection->id}");
            
            // Verify the data was saved
            $savedInspection = Inspection::find($inspection->id);
            
            $this->newLine();
            $this->info('🔍 Verifying saved data:');
            
            $fieldsToCheck = [
                'area_of_examination' => 'Area of Examination',
                'services_performed' => 'Services Performed',
                'contract' => 'Contract',
                'work_order' => 'Work Order',
                'inspector_comments' => 'Inspector Comments',
                'equipment_description' => 'Equipment Description',
                'manufacturer' => 'Manufacturer',
                'lifting_examination_inspector' => 'Lifting Inspector',
                'load_test_inspector' => 'Load Test Inspector'
            ];
            
            $savedCount = 0;
            foreach ($fieldsToCheck as $field => $label) {
                if (!empty($savedInspection->$field)) {
                    $this->info("✅ {$label}: {$savedInspection->$field}");
                    $savedCount++;
                } else {
                    $this->error("❌ {$label}: NOT SAVED");
                }
            }
            
            $this->newLine();
            $this->info("📊 RESULTS: {$savedCount}/" . count($fieldsToCheck) . " fields saved correctly");
            
            if ($savedCount == count($fieldsToCheck)) {
                $this->info('🎉 ALL DATA SAVING CORRECTLY AFTER FIX!');
                $this->info('💡 The issue has been resolved.');
            } else {
                $this->error('❌ STILL HAVING ISSUES');
                $this->info('💡 Additional debugging needed.');
            }
            
            // Clean up test data
            $inspection->delete();
            $this->info('✅ Test inspection cleaned up');
            
            DB::commit();
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ ERROR: ' . $e->getMessage());
            return 1;
        }
    }
}
