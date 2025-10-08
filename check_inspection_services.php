<?php
// Quick diagnostic: Check service records for a given inspection ID
// Usage: php check_inspection_services.php [inspection_id]

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Inspection;
use App\Models\LiftingExamination;
use App\Models\MpiInspection;
use App\Models\LoadTest;
use App\Models\OtherTest;

$id = isset($argv[1]) ? (int)$argv[1] : 12;

echo "=== Inspecting Services for Inspection ID {$id} ===\n\n";

$inspection = Inspection::find($id);
if (!$inspection) {
    echo "❌ Inspection not found.\n";
    exit(1);
}

echo "Inspection #: " . ($inspection->inspection_number ?? 'N/A') . "\n";
echo "Status: " . ($inspection->status ?? 'N/A') . "\n\n";

// Lifting Examination / Thorough Examination
$lifting = LiftingExamination::where('inspection_id', $id)->first();
echo "-- Lifting Examination --\n";
if ($lifting) {
    echo "Record: FOUND (id={$lifting->id})\n";
    echo "Inspector ID: " . ($lifting->inspector_id ?? 'NULL') . "\n";
    echo "First Examination: " . ($lifting->first_examination ?? 'NULL') . "\n";
    echo "Safe to Operate: " . ($lifting->safe_to_operate ?? 'NULL') . "\n";
    echo "Overall Status: " . ($lifting->overall_status ?? 'N/A') . "\n";
    echo "Thorough Inspector: " . ($lifting->thorough_examination_inspector ?? 'NULL') . "\n";
    echo "Thorough Method: " . ($lifting->thorough_method ?? 'NULL') . "\n";
    echo "Thorough Results: " . ($lifting->thorough_results ?? 'NULL') . "\n";
} else {
    echo "Record: NOT FOUND\n";
}
echo "\n";

// MPI / Visual
$mpi = MpiInspection::where('inspection_id', $id)->first();
echo "-- MPI Inspection --\n";
if ($mpi) {
    echo "Record: FOUND (id={$mpi->id})\n";
    echo "Inspector ID: " . ($mpi->inspector_id ?? 'NULL') . "\n";
    echo "Contrast Paint Method: " . ($mpi->contrast_paint_method ?? 'NULL') . "\n";
    echo "MPI Results: " . ($mpi->mpi_results ?? 'NULL') . "\n";
    echo "Visual Inspector: " . ($mpi->visual_inspector ?? 'NULL') . "\n";
    echo "Visual Method: " . ($mpi->visual_method ?? 'NULL') . "\n";
    echo "Visual Results: " . ($mpi->visual_results ?? 'NULL') . "\n";
} else {
    echo "Record: NOT FOUND\n";
}
echo "\n";

// Load Test
$load = LoadTest::where('inspection_id', $id)->first();
echo "-- Load Test --\n";
if ($load) {
    echo "Record: FOUND (id={$load->id})\n";
    echo "Duration Held: " . ($load->duration_held ?? 'NULL') . "\n";
    echo "Two Points Diagonal: " . ($load->two_points_diagonal ?? 'NULL') . "\n";
    echo "Four Points: " . ($load->four_points ?? 'NULL') . "\n";
    echo "Result: " . ($load->result ?? 'NULL') . "\n";
} else {
    echo "Record: NOT FOUND\n";
}
echo "\n";

// Other Tests
$other = OtherTest::where('inspection_id', $id)->first();
echo "-- Other Tests --\n";
if ($other) {
    echo "Record: FOUND (id={$other->id})\n";
    echo "Drop Load: " . ($other->drop_test_load ?? 'NULL') . "\n";
    echo "Tilt Load: " . ($other->tilt_test_load ?? 'NULL') . "\n";
    echo "Lowering Load: " . ($other->lowering_test_load ?? 'NULL') . "\n";
    echo "Other Test Results: " . ($other->other_test_results ?? 'NULL') . "\n";
} else {
    echo "Record: NOT FOUND\n";
}
echo "\n";

// Summary
echo "=== Summary ===\n";
echo "Lifting Examination: " . ($lifting ? 'FOUND' : 'MISSING') . "\n";
echo "MPI Inspection: " . ($mpi ? 'FOUND' : 'MISSING') . "\n";
echo "Load Test: " . ($load ? 'FOUND' : 'MISSING') . "\n";
echo "Other Tests: " . ($other ? 'FOUND' : 'MISSING') . "\n";
echo "\nDone.\n";