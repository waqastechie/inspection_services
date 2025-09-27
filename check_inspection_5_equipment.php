<?php
// Simple script to check inspection equipment data
require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== CHECKING INSPECTION ID 5 EQUIPMENT DATA ===\n\n";
    
    // Check if inspection 5 exists
    $inspection = \App\Models\Inspection::find(5);
    if (!$inspection) {
        echo "❌ Inspection ID 5 does not exist\n";
        exit;
    }
    
    echo "✅ Inspection ID 5 exists: " . $inspection->inspection_number . "\n";
    echo "   Client: " . ($inspection->client ? $inspection->client->client_name : 'N/A') . "\n";
    echo "   Status: " . $inspection->status . "\n\n";
    
    // Check equipment data
    $equipmentCount = \App\Models\InspectionEquipment::where('inspection_id', 5)->count();
    echo "Equipment records count: " . $equipmentCount . "\n\n";
    
    if ($equipmentCount > 0) {
        $equipment = \App\Models\InspectionEquipment::where('inspection_id', 5)->get();
        
        echo "Equipment Details:\n";
        echo "ID | Equipment Type | Serial Number | Status | Category | Created\n";
        echo "---|----------------|---------------|--------|----------|--------\n";
        
        foreach ($equipment as $item) {
            printf("%2d | %-14s | %-13s | %-6s | %-8s | %s\n",
                $item->id,
                substr($item->equipment_type ?: 'N/A', 0, 14),
                substr($item->serial_number ?: 'N/A', 0, 13),
                $item->status ?: 'N/A',
                $item->category ?: 'N/A',
                $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'N/A'
            );
        }
    } else {
        echo "❌ No equipment records found for inspection ID 5\n\n";
        
        // Check if there are any equipment records at all
        $totalEquipment = \App\Models\InspectionEquipment::count();
        echo "Total equipment records in database: " . $totalEquipment . "\n";
        
        if ($totalEquipment > 0) {
            echo "\nSample equipment records (other inspections):\n";
            $samples = \App\Models\InspectionEquipment::with('inspection')->take(5)->get();
            foreach ($samples as $sample) {
                echo "  - Inspection " . $sample->inspection_id . ": " . $sample->equipment_type . " (" . $sample->serial_number . ")\n";
            }
        }
    }
    
    // Check recent form submissions for this inspection
    echo "\n=== DEBUGGING INFO ===\n";
    echo "Inspection created: " . $inspection->created_at . "\n";
    echo "Inspection updated: " . $inspection->updated_at . "\n";
    
    // Check if there are any wizard step logs or data
    if (method_exists($inspection, 'current_step')) {
        echo "Current step: " . ($inspection->current_step ?: 'N/A') . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}