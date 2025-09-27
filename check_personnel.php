<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

use App\Models\Personnel;

try {
    echo "Checking personnel table structure and data...\n\n";
    
    // Get the first personnel record
    $personnel = Personnel::first();
    
    if ($personnel) {
        echo "Found personnel record:\n";
        echo "ID: " . $personnel->id . "\n";
        echo "Fields available:\n";
        foreach ($personnel->getAttributes() as $key => $value) {
            echo "  $key: $value\n";
        }
        
        echo "\nTotal personnel count: " . Personnel::count() . "\n";
        echo "Active personnel count: " . Personnel::where('is_active', true)->count() . "\n";
        
        // Show all active personnel
        echo "\nActive personnel:\n";
        $activePersonnel = Personnel::where('is_active', true)->get();
        foreach ($activePersonnel as $person) {
            if (isset($person->first_name) && isset($person->last_name)) {
                echo "  - {$person->first_name} {$person->last_name} ({$person->position})\n";
            } else {
                echo "  - Personnel ID: {$person->id} (missing name fields)\n";
            }
        }
        
    } else {
        echo "No personnel records found in database.\n";
        echo "You may need to seed the database or add personnel manually.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}