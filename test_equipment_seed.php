<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Equipment;

echo "Current equipment count: " . Equipment::count() . "\n";

if (Equipment::count() == 0) {
    echo "Adding sample equipment...\n";
    
    $sampleEquipment = [
        [
            'name' => 'UT Flaw Detector Pro',
            'type' => 'ultrasonic_tester',
            'brand_model' => 'UT-Pro-2000',
            'serial_number' => 'UT001-2024',
            'equipment_category' => 'asset',
            'condition' => 'excellent',
            'is_active' => true,
        ],
        [
            'name' => 'Chain Block 5T',
            'type' => 'chain_block',
            'brand_model' => 'LiftMaster LM-5T',
            'serial_number' => 'LM5T-002-2023',
            'equipment_category' => 'asset',
            'swl' => '5 tonnes',
            'condition' => 'good',
            'is_active' => true,
        ],
        [
            'name' => 'Digital Caliper 300mm',
            'type' => 'measuring_device',
            'brand_model' => 'PrecisionTool PT-300D',
            'serial_number' => 'PT300-004-2024',
            'equipment_category' => 'asset',
            'condition' => 'excellent',
            'is_active' => true,
        ]
    ];
    
    foreach ($sampleEquipment as $eq) {
        Equipment::create($eq);
        echo "Created: " . $eq['name'] . "\n";
    }
}

echo "Final equipment count: " . Equipment::count() . "\n";
echo "Equipment list:\n";
foreach (Equipment::all() as $eq) {
    echo "- {$eq->name} ({$eq->type}) - {$eq->equipment_category}\n";
}

// Test the API logic
echo "\nTesting API query logic:\n";
$query = Equipment::active();
$query->where('equipment_category', 'asset');
$equipment = $query->get(['id', 'name', 'type', 'brand_model', 'serial_number', 'equipment_category']);
echo "API would return " . $equipment->count() . " items:\n";
foreach ($equipment as $eq) {
    echo "- {$eq->name} - {$eq->brand_model} ({$eq->serial_number})\n";
}
