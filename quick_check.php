<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

echo "Equipment Assignments for inspection 3: " . \App\Models\EquipmentAssignment::where('inspection_id', 3)->count() . "\n";

$assignments = \App\Models\EquipmentAssignment::where('inspection_id', 3)->get();
foreach($assignments as $a) {
    echo "- {$a->equipment_name} ({$a->equipment_type})\n";
}

echo "\nInspection 3 exists: " . (\App\Models\Inspection::find(3) ? 'YES' : 'NO') . "\n";