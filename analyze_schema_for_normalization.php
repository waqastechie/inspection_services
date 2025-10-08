<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Database Schema Analysis for Normalization ===\n\n";

try {
    // Get all columns in the inspections table
    $columns = Schema::getColumnListing('inspections');
    
    // Categorize columns by service type
    $coreInspectionColumns = [
        'id', 'inspection_number', 'client_id', 'project_id', 'location_id', 'service_id', 'equipment_id',
        'purchase_order', 'client_job_reference', 'rig', 'report_number', 'revision', 'job_ref',
        'standards', 'local_procedure_number', 'drawing_number', 'test_restrictions', 'surface_condition',
        'inspection_date', 'weather_conditions', 'temperature', 'humidity',
        'equipment_type', 'equipment_description', 'manufacturer', 'model', 'serial_number', 'asset_tag',
        'manufacture_year', 'capacity', 'capacity_unit', 'applicable_standard', 'inspection_class',
        'certification_body', 'previous_certificate_number', 'last_inspection_date', 'next_inspection_due',
        'next_inspection_date', 'defects_found', 'recommendations', 'limitations',
        'lead_inspector_name', 'lead_inspector_certification', 'inspector_signature',
        'report_date', 'general_notes', 'inspector_comments', 'inspection_images', 'attachments',
        'status', 'qa_status', 'completed_by', 'completed_at', 'created_by', 'created_at', 'updated_at',
        'overall_result', 'service_notes', 'qa_reviewer_id', 'qa_reviewed_at', 'qa_comments', 'qa_rejection_reason'
    ];
    
    $mpiColumns = [
        'mpi_service_inspector', 'visual_inspector', 'visual_comments', 'visual_method',
        'visual_lighting', 'visual_equipment', 'visual_conditions', 'visual_results'
    ];
    
    $liftingExaminationColumns = [
        'lifting_examination_inspector', 'thorough_examination_inspector', 'thorough_examination_comments',
        'thorough_method', 'thorough_equipment', 'thorough_conditions', 'thorough_results'
    ];
    
    $loadTestColumns = [
        'load_test_inspector', 'load_test_duration', 'load_test_two_points_diagonal',
        'load_test_four_points', 'load_test_deflection', 'load_test_deformation',
        'load_test_distance_from_ground', 'load_test_result', 'load_test_notes'
    ];
    
    $otherTestsColumns = [
        // Drop Test
        'drop_test_load', 'drop_type', 'drop_distance', 'drop_suspended', 'drop_impact_speed', 'drop_result', 'drop_notes',
        // Tilt Test
        'tilt_test_load', 'loaded_tilt', 'empty_tilt', 'tilt_results', 'tilt_stability', 'tilt_direction', 'tilt_duration', 'tilt_notes',
        // Lowering Test
        'lowering_test_load', 'lowering_impact_speed', 'lowering_result', 'lowering_method', 'lowering_distance',
        'lowering_duration', 'lowering_cycles', 'brake_efficiency', 'control_response', 'lowering_notes'
    ];
    
    echo "=== CURRENT SCHEMA ANALYSIS ===\n";
    echo "Total columns: " . count($columns) . "\n\n";
    
    echo "Core Inspection Columns (" . count($coreInspectionColumns) . "):\n";
    foreach ($coreInspectionColumns as $column) {
        if (in_array($column, $columns)) {
            echo "✅ {$column}\n";
        } else {
            echo "❌ {$column} (missing)\n";
        }
    }
    
    echo "\n=== SERVICE-SPECIFIC COLUMNS ===\n";
    
    echo "\nMPI Service Columns (" . count($mpiColumns) . "):\n";
    foreach ($mpiColumns as $column) {
        if (in_array($column, $columns)) {
            echo "✅ {$column}\n";
        } else {
            echo "❌ {$column} (missing)\n";
        }
    }
    
    echo "\nLifting Examination Columns (" . count($liftingExaminationColumns) . "):\n";
    foreach ($liftingExaminationColumns as $column) {
        if (in_array($column, $columns)) {
            echo "✅ {$column}\n";
        } else {
            echo "❌ {$column} (missing)\n";
        }
    }
    
    echo "\nLoad Test Columns (" . count($loadTestColumns) . "):\n";
    foreach ($loadTestColumns as $column) {
        if (in_array($column, $columns)) {
            echo "✅ {$column}\n";
        } else {
            echo "❌ {$column} (missing)\n";
        }
    }
    
    echo "\nOther Tests Columns (" . count($otherTestsColumns) . "):\n";
    foreach ($otherTestsColumns as $column) {
        if (in_array($column, $columns)) {
            echo "✅ {$column}\n";
        } else {
            echo "❌ {$column} (missing)\n";
        }
    }
    
    // Find uncategorized columns
    $allServiceColumns = array_merge($coreInspectionColumns, $mpiColumns, $liftingExaminationColumns, $loadTestColumns, $otherTestsColumns);
    $uncategorizedColumns = array_diff($columns, $allServiceColumns);
    
    if (!empty($uncategorizedColumns)) {
        echo "\n=== UNCATEGORIZED COLUMNS ===\n";
        foreach ($uncategorizedColumns as $column) {
            echo "❓ {$column}\n";
        }
    }
    
    echo "\n=== NORMALIZATION BENEFITS ===\n";
    echo "✅ Reduced NULL values in main inspections table\n";
    echo "✅ Better data organization and maintainability\n";
    echo "✅ Easier to add new service types without modifying main table\n";
    echo "✅ Better performance for queries specific to service types\n";
    echo "✅ Cleaner separation of concerns\n";
    
    echo "\n=== PROPOSED NEW STRUCTURE ===\n";
    echo "1. inspections (core data only)\n";
    echo "2. mpi_inspections (MPI service data)\n";
    echo "3. lifting_examinations (Lifting Examination service data)\n";
    echo "4. load_tests (Load Test data)\n";
    echo "5. other_tests (Drop, Tilt, Lowering tests data)\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}