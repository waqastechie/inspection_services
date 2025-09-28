<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestEquipmentPersistence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-equipment-persistence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test equipment assignment persistence between wizard steps';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing equipment assignment persistence...');
        
        // Create a test inspection
        $inspection = \App\Models\Inspection::create([
            'inspection_number' => 'TEST-' . time(),
            'client_id' => 1,
            'site_id' => 1,
            'inspector_id' => 1,
            'equipment_id' => 1,
            'inspection_date' => now()->format('Y-m-d'),
            'status' => 'draft'
        ]);

        $this->info("Created test inspection ID: {$inspection->id}");

        // Simulate step 3 - Equipment Assignments
        $this->info("\n=== STEP 3: Equipment Assignments ===");
        $equipmentData = json_encode([
            [
                'equipment_name' => 'Test Scale 1',
                'equipment_type' => 'Scale',
                'serial_number' => 'TST001'
            ],
            [
                'equipment_name' => 'Test Scale 2', 
                'equipment_type' => 'Scale',
                'serial_number' => 'TST002'
            ]
        ]);

        // Call saveEquipmentAssignments
        $controller = new \App\Http\Controllers\InspectionController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('saveEquipmentAssignments');
        $method->setAccessible(true);
        $method->invoke($controller, $inspection, $equipmentData);

        // Check assignments after step 3
        $assignments = \App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->get();
        $this->info("Equipment assignments after step 3: " . $assignments->count());
        foreach ($assignments as $assignment) {
            $this->info("- {$assignment->equipment_name} (ID: {$assignment->id})");
        }

        // Simulate step 4 - Equipment Details
        $this->info("\n=== STEP 4: Equipment Details ===");
        $request4 = new \Illuminate\Http\Request();
        $request4->merge([
            'equipment_type' => 'Test Calibrator',
            'equipment_serial' => 'CAL001',
            'equipment_manufacturer' => 'Test Mfg',
            'equipment_model' => 'Model X',
            'equipment_status' => 'good',
            'equipment_comments' => 'Additional equipment for testing'
        ]);

        // Call saveEquipmentDetails
        $method4 = $reflection->getMethod('saveEquipmentDetails');
        $method4->setAccessible(true);
        $method4->invoke($controller, $inspection, $request4);

        // Check assignments after step 4
        $assignmentsAfter = \App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->get();
        $this->info("Equipment assignments after step 4: " . $assignmentsAfter->count());
        foreach ($assignmentsAfter as $assignment) {
            $this->info("- {$assignment->equipment_name} (ID: {$assignment->id})");
        }

        // Verify the fix
        if ($assignmentsAfter->count() >= $assignments->count()) {
            $this->info("\n✅ SUCCESS: Equipment assignments from step 3 were preserved!");
            $this->info("Original count: {$assignments->count()}, Final count: {$assignmentsAfter->count()}");
        } else {
            $this->error("\n❌ FAILURE: Equipment assignments were deleted!");
            $this->error("Original count: {$assignments->count()}, Final count: {$assignmentsAfter->count()}");
        }

        // Cleanup
        $this->info("\nCleaning up test data...");
        \App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->delete();
        $inspection->delete();
        $this->info("Test completed.");
    }
}
