<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inspection;
use App\Http\Controllers\InspectionController;

class TestEquipmentSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:equipment-save {inspection_id=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test equipment saving functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inspectionId = $this->argument('inspection_id');
        
        $this->info("Testing Equipment Saving for Inspection ID {$inspectionId}");
        $this->info("==========================================");

        // Find inspection
        $inspection = Inspection::find($inspectionId);
        if (!$inspection) {
            $this->error("ERROR: Inspection {$inspectionId} not found!");
            return;
        }

        $this->info("✓ Found inspection: {$inspection->inspection_no} - {$inspection->client_name}");

        // Check current equipment count
        $currentCount = $inspection->inspectionEquipment()->count();
        $this->info("Current equipment count: {$currentCount}");

        // Test data
        $testEquipmentData = [
            [
                'equipment_type' => 'Test Equipment 1',
                'serial_number' => 'TEST001',
                'description' => 'Test equipment for debugging',
                'reason_for_examination' => 'Initial examination',
                'model' => 'TEST-MODEL-1',
                'status' => 'pass',
                'category' => 'item',
                'date_of_manufacture' => '2023-01-01',
                'date_of_last_examination' => '2024-01-01',
                'date_of_next_examination' => '2025-01-01',
                'swl' => '1000',
                'test_load_applied' => '1200',
                'remarks' => 'Test remarks 1'
            ],
            [
                'equipment_type' => 'Test Equipment 2', 
                'serial_number' => 'TEST002',
                'description' => 'Another test equipment',
                'reason_for_examination' => 'Periodic examination',
                'model' => 'TEST-MODEL-2',
                'status' => 'pass',
                'category' => 'item',
                'date_of_manufacture' => '2022-06-15',
                'date_of_last_examination' => '2024-06-15',
                'date_of_next_examination' => '2025-12-15',
                'swl' => '2000',
                'test_load_applied' => '2400',
                'remarks' => 'Test remarks 2'
            ]
        ];

        $this->info("Test equipment data:");
        $this->line(json_encode($testEquipmentData, JSON_PRETTY_PRINT));

        // Test the saveEquipmentData method using reflection
        try {
            $controller = new InspectionController();
            
            // Use reflection to call private method
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('saveEquipmentData');
            $method->setAccessible(true);
            
            $this->info("Calling saveEquipmentData method...");
            $method->invoke($controller, $inspection, json_encode($testEquipmentData));
            
            // Check new count
            $newCount = $inspection->fresh()->inspectionEquipment()->count();
            $this->info("Equipment count after save: {$newCount}");
            
            if ($newCount > $currentCount) {
                $this->info("✓ SUCCESS: Equipment was saved! Added " . ($newCount - $currentCount) . " records");
                
                // Show the saved records
                $this->info("Saved equipment records:");
                $equipment = $inspection->fresh()->inspectionEquipment()->latest()->take($newCount - $currentCount)->get();
                foreach ($equipment as $item) {
                    $this->line("- ID {$item->id}: {$item->equipment_type} ({$item->serial_number})");
                }
            } else {
                $this->error("✗ FAILED: No new equipment was saved");
            }
            
        } catch (\Exception $e) {
            $this->error("✗ ERROR: " . $e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
        }

        $this->info("=== Test Complete ===");
    }
}
