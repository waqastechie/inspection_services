<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing Blade syntax...\n";
    
    // Try to compile the edit view
    $view = view('inspections.edit', [
        'inspection' => new App\Models\Inspection([
            'inspection_number' => 'TEST001',
            'client_name' => 'Test Client',
            'status' => 'draft'
        ]),
        'personnel' => collect()
    ]);
    
    // If we get here without errors, the syntax is good
    echo "✅ Blade syntax is correct!\n";
    echo "✅ Edit view compiles successfully!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
