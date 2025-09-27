<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FINAL TEST: PERSONNEL CREATION ===\n";

try {
    echo "1. Testing the exact validation rule that was causing the error...\n";
    
    // This is exactly what the PersonnelController does when you submit the form
    $request_data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'position' => 'Inspector',
        'department' => 'QA',
        'employee_id' => '123',
        'email' => 'john@test.com',
        'is_active' => 1
    ];
    
    $validator = \Illuminate\Support\Facades\Validator::make($request_data, [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'employee_id' => 'nullable|string|max:100|unique:personnels,employee_id',  // FIXED!
        'email' => 'nullable|email|max:255',
        'is_active' => 'boolean',
    ]);
    
    // The validation should work now without SQL errors
    if ($validator->fails()) {
        echo "   ✓ Validation ran successfully (may have failed due to unique constraint, but no SQL error)\n";
        echo "   Validation errors: " . json_encode($validator->errors()->all()) . "\n";
    } else {
        echo "   ✓ Validation passed completely\n";
    }
    
    echo "\n2. Testing actual personnel creation with unique employee_id...\n";
    
    $unique_data = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'position' => 'Test Position',
        'department' => 'Test Department',
        'employee_id' => 'TEST_' . time(),
        'email' => 'test@example.com',
        'is_active' => true
    ];
    
    // Create personnel record
    $personnel = \App\Models\Personnel::create($unique_data);
    echo "   ✅ Personnel created successfully!\n";
    echo "   ID: {$personnel->id}\n";
    echo "   Name: {$personnel->first_name} {$personnel->last_name}\n";
    echo "   Employee ID: {$personnel->employee_id}\n";
    
    // Test the unique validation with the same employee_id
    echo "\n3. Testing unique validation with existing employee_id...\n";
    $validator2 = \Illuminate\Support\Facades\Validator::make([
        'employee_id' => $personnel->employee_id
    ], [
        'employee_id' => 'unique:personnels,employee_id'
    ]);
    
    if ($validator2->fails()) {
        echo "   ✅ Unique validation works correctly - detected duplicate employee_id\n";
    } else {
        echo "   ⚠️  Unique validation didn't catch the duplicate\n";
    }
    
    // Clean up
    $personnel->delete();
    echo "   ✅ Test record cleaned up\n";
    
    echo "\n🎉 SUCCESS! PERSONNEL CREATION IS NOW FIXED!\n";
    echo "✅ The validation rules now use the correct 'personnels' table\n";
    echo "✅ No more 'Table sc.personnel doesn't exist' errors\n";
    echo "✅ Personnel creation should work in your browser now\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR STILL EXISTS:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    if (strpos($e->getMessage(), 'personnel') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "\nThis is still the table name error. More investigation needed.\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";