<?php

require_once 'vendor/autoload.php';

use App\Models\Equipment;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test the equipment query
$query = Equipment::active();
$equipment = $query->get([
    'id', 
    'name', 
    'type', 
    'brand_model', 
    'serial_number', 
    'condition',
    'calibration_date',
    'calibration_due',
    'calibration_certificate',
    'maintenance_notes'
]);

echo "Raw equipment data:\n";
print_r($equipment->toArray());

// Transform the data
$transformedEquipment = $equipment->map(function($item) {
    return [
        'id' => $item->id,
        'equipment_name' => $item->name,
        'equipment_type' => $item->type,
        'brand_model' => $item->brand_model,
        'serial_number' => $item->serial_number,
        'condition' => $item->condition,
        'calibration_date' => $item->calibration_date ? $item->calibration_date->format('Y-m-d') : null,
        'calibration_due' => $item->calibration_due ? $item->calibration_due->format('Y-m-d') : null,
        'calibration_certificate' => $item->calibration_certificate,
        'maintenance_notes' => $item->maintenance_notes,
    ];
});

echo "\nTransformed equipment data:\n";
print_r($transformedEquipment->toArray());

echo "\nJSON output:\n";
echo json_encode($transformedEquipment, JSON_PRETTY_PRINT);

?>
