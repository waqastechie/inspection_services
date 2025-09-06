<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    $personnelCount = App\Models\Personnel::count();
    echo "✅ Personnel table connected successfully!\n";
    echo "Personnel records found: " . $personnelCount . "\n";
    
    // Test validation rules
    $validator = Illuminate\Support\Facades\Validator::make([
        'service_1_inspector' => 999 // Non-existent ID
    ], [
        'service_1_inspector' => 'nullable|exists:personnels,id'
    ]);
    
    if ($validator->fails()) {
        echo "✅ Validation rules working correctly!\n";
        echo "Validation failed as expected for non-existent personnel ID\n";
    } else {
        echo "❌ Validation issue - should have failed\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
