<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Equipment;

echo "=== FIXING EQUIPMENT DATA ===\n";

// Get all equipment
$equipment = Equipment::all();
echo "Found " . $equipment->count() . " equipment records\n";

foreach ($equipment as $eq) {
    $updated = false;
    echo "\nChecking: {$eq->name}\n";
    
    // Fix is_active if it's null or false
    if ($eq->is_active !== true) {
        echo "  - Setting is_active to true\n";
        $eq->is_active = true;
        $updated = true;
    }
    
    // Fix equipment_category if it's not set or wrong
    if (empty($eq->equipment_category) || $eq->equipment_category !== 'asset') {
        echo "  - Setting equipment_category to 'asset'\n";
        $eq->equipment_category = 'asset';
        $updated = true;
    }
    
    if ($updated) {
        $eq->save();
        echo "  - Updated!\n";
    } else {
        echo "  - No changes needed\n";
    }
}

echo "\n=== FINAL CHECK ===\n";
echo "Active assets count: " . Equipment::active()->assets()->count() . "\n";

echo "\nEquipment that API will return:\n";
foreach (Equipment::active()->assets()->get() as $eq) {
    echo "- {$eq->name} ({$eq->type})\n";
}
