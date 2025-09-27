<?php

// Simple migration runner
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "Running Laravel Migration...\n";

try {
    // Run migrations
    Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    
    $output = Illuminate\Support\Facades\Artisan::output();
    echo $output;
    
    echo "\nMigration completed successfully!\n";
    
    // Verify the table was created
    $tableExists = Illuminate\Support\Facades\Schema::hasTable('inspection_images');
    
    if ($tableExists) {
        echo "✅ inspection_images table created successfully!\n";
        
        // Show table structure
        $columns = Illuminate\Support\Facades\Schema::getColumnListing('inspection_images');
        echo "\nTable columns:\n";
        foreach ($columns as $column) {
            echo "  - $column\n";
        }
    } else {
        echo "❌ inspection_images table was not created\n";
    }
    
} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}
