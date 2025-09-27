<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Equipment;

echo "=== EQUIPMENT DEBUG ===\n";
echo "Total equipment count: " . Equipment::count() . "\n\n";

echo "All equipment (raw data):\n";
foreach (Equipment::all() as $eq) {
    echo "ID: {$eq->id}\n";
    echo "Name: {$eq->name}\n";
    echo "Category: {$eq->equipment_category}\n";
    echo "Is Active: " . ($eq->is_active ? 'true' : 'false') . "\n";
    echo "Type: {$eq->type}\n";
    echo "Brand/Model: {$eq->brand_model}\n";
    echo "Serial: {$eq->serial_number}\n";
    echo "---\n";
}

echo "\nActive equipment count: " . Equipment::active()->count() . "\n";
echo "Asset equipment count: " . Equipment::where('equipment_category', 'asset')->count() . "\n";
echo "Active assets count: " . Equipment::active()->assets()->count() . "\n";

echo "\nEquipment that would be returned by API:\n";
$apiQuery = Equipment::active()->assets();
$apiResults = $apiQuery->get(['id', 'name', 'type', 'brand_model', 'serial_number', 'equipment_category', 'is_active']);

echo "Query: " . $apiQuery->toSql() . "\n";
echo "Count: " . $apiResults->count() . "\n";

foreach ($apiResults as $eq) {
    echo "- {$eq->name} (Category: {$eq->equipment_category}, Active: " . ($eq->is_active ? 'yes' : 'no') . ")\n";
}
