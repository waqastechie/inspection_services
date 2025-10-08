<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Models\Inspection;
use App\Models\LiftingExamination;
use App\Models\MpiInspection;

echo "Testing direct relationships...\n";

// Create test inspection
echo "Creating test inspection...\n";
$inspection = Inspection::create([
    'inspection_number' => 'TEST-' . time(),
    'status' => 'draft'
]);
echo "Inspection created with ID: {$inspection->id}\n";

// Create lifting examination
echo "Creating lifting examination...\n";
$lifting = LiftingExamination::create([
    'inspection_id' => $inspection->id,
    'first_examination' => 'yes',
    'safe_to_operate' => 'yes',
    'equipment_defect' => 'no'
]);
echo "LiftingExamination created with ID: {$lifting->id}\n";

// Create MPI inspection
echo "Creating MPI inspection...\n";
$mpi = MpiInspection::create([
    'inspection_id' => $inspection->id,
    'contrast_paint_method' => 'test_method',
    'current_flow' => 'AC',
    'mpi_results' => 'passed'
]);
echo "MpiInspection created with ID: {$mpi->id}\n";

// Test relationships
echo "\nTesting relationships...\n";
$inspection->refresh(); // Reload from database

$liftingCheck = $inspection->liftingExamination;
echo "Inspection has lifting examination: " . ($liftingCheck ? 'YES' : 'NO') . "\n";
if ($liftingCheck) {
    echo "  - First examination: {$liftingCheck->first_examination}\n";
    echo "  - Safe to operate: {$liftingCheck->safe_to_operate}\n";
}

$mpiCheck = $inspection->mpiInspection;
echo "Inspection has MPI inspection: " . ($mpiCheck ? 'YES' : 'NO') . "\n";
if ($mpiCheck) {
    echo "  - Contrast paint method: {$mpiCheck->contrast_paint_method}\n";
    echo "  - Current flow: {$mpiCheck->current_flow}\n";
}

echo "\nTest completed successfully!\n";
