<?php

require_once 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Inspection;

// Create 5 test inspections
for ($i = 1; $i <= 5; $i++) {
    Inspection::create([
        'inspection_number' => 'INS-2025-' . str_pad($i + 2000, 4, '0', STR_PAD_LEFT),
        'client_name' => 'Test Client ' . $i,
        'project_name' => 'Test Project ' . $i,
        'location' => 'Test Location ' . $i,
        'inspection_date' => now()->subDays($i),
        'equipment_type' => 'crane',
        'equipment_description' => 'Test equipment description for crane ' . $i,
        'applicable_standard' => 'AS 1418.1',
        'inspection_class' => 'Annual',
        'lead_inspector_name' => 'Test Inspector ' . $i,
        'lead_inspector_certification' => 'Certified Inspector Level ' . $i,
        'status' => 'completed',
        'report_date' => now(),
    ]);
}

echo "Created 5 test inspections successfully!\n";
