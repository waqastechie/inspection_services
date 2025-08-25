<?php
// Simple test to check edit page functionality

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

// Check if we have any inspections
try {
    $inspections = App\Models\Inspection::all();
    echo "Found " . $inspections->count() . " inspections\n";
    
    if ($inspections->count() > 0) {
        $inspection = $inspections->first();
        echo "First inspection ID: " . $inspection->id . "\n";
        echo "Project Name: " . $inspection->project_name . "\n";
        echo "Lead Inspector: " . ($inspection->lead_inspector_name ?? 'Not set') . "\n";
        echo "Edit URL would be: /inspections/" . $inspection->id . "/edit\n";
    } else {
        echo "No inspections found. Create one first.\n";
    }
    
    // Also check if we have personnel for dropdowns
    $personnel = App\Models\Personnel::all();
    echo "\nFound " . $personnel->count() . " personnel records\n";
    
    if ($personnel->count() > 0) {
        foreach ($personnel->take(3) as $person) {
            echo "- " . $person->first_name . " " . $person->last_name . " (" . $person->position . ")\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
