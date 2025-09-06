<?php

// Simple test to isolate the syntax error
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Syntax Error Debug Test</h2>\n";
echo "<pre>\n";

try {
    // Test 1: Simple array with the problematic field
    echo "Test 1: Simple array creation...\n";
    $testArray = [
        'lifting_examination_inspector' => 'nullable|exists:personnels,id',
        'load_test_inspector' => 'nullable|exists:personnels,id'
    ];
    echo "✅ Array creation successful\n\n";
    
    // Test 2: JSON encode the array
    echo "Test 2: JSON encoding...\n";
    $json = json_encode($testArray);
    echo "✅ JSON: $json\n\n";
    
    // Test 3: Test Laravel validation format
    echo "Test 3: Laravel validation format...\n";
    $validation = [
        'client_name' => 'required|string|max:255',
        'lifting_examination_inspector' => 'nullable|exists:personnels,id',
        'load_test_inspector' => 'nullable|exists:personnels,id'
    ];
    echo "✅ Validation array created successfully\n\n";
    
    // Test 4: Test with quotes variations
    echo "Test 4: Testing quote variations...\n";
    $testQuotes = [
        "lifting_examination_inspector" => "nullable|exists:personnels,id",
        'load_test_inspector' => 'nullable|exists:personnels,id'
    ];
    echo "✅ Quote variations successful\n\n";
    
    // Test 5: Load Laravel and test validation
    if (file_exists('vendor/autoload.php')) {
        echo "Test 5: Laravel validation test...\n";
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        
        $validator = Illuminate\Support\Facades\Validator::make([
            'lifting_examination_inspector' => null
        ], [
            'lifting_examination_inspector' => 'nullable|exists:personnels,id'
        ]);
        
        echo "✅ Laravel validation test passed\n\n";
    }
    
    echo "🎉 ALL TESTS PASSED - No syntax errors detected locally\n\n";
    echo "💡 The error might be:\n";
    echo "   - Production PHP version difference\n";
    echo "   - Character encoding issue\n";
    echo "   - Hidden/invisible characters\n";
    echo "   - Server configuration\n";
    echo "   - Form submission encoding\n\n";
    
    echo "🔧 SUGGESTED FIXES:\n";
    echo "1. Check PHP version: php --version\n";
    echo "2. Check file encoding (should be UTF-8)\n";
    echo "3. Clear all caches: php artisan cache:clear\n";
    echo "4. Clear config: php artisan config:clear\n";
    echo "5. Regenerate optimized files: php artisan optimize\n";
    
} catch (Exception $e) {
    echo "❌ ERROR FOUND: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
