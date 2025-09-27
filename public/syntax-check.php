<?php
header('Content-Type: text/plain');

echo "=== SYNTAX CHECK ===\n\n";

try {
    // Check ImageUploadService syntax
    $serviceFile = __DIR__ . '/../app/Services/ImageUploadService.php';
    
    if (!file_exists($serviceFile)) {
        echo "❌ ImageUploadService.php not found\n";
        exit(1);
    }
    
    echo "✅ ImageUploadService.php file exists\n";
    
    // Check syntax
    $output = [];
    $return_var = 0;
    exec("php -l \"$serviceFile\"", $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ ImageUploadService.php syntax is valid\n";
    } else {
        echo "❌ Syntax error in ImageUploadService.php:\n";
        echo implode("\n", $output) . "\n";
        exit(1);
    }
    
    // Test Laravel bootstrap
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test service instantiation
    $service = new App\Services\ImageUploadService();
    echo "✅ ImageUploadService instantiated successfully\n";
    
    // Test table check
    if (Schema::hasTable('inspection_images')) {
        echo "✅ inspection_images table exists\n";
    } else {
        echo "⚠️  inspection_images table does not exist - create it first\n";
    }
    
    echo "\n🎉 ALL SYNTAX CHECKS PASSED!\n";
    echo "The ImageUploadService is ready to use.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
