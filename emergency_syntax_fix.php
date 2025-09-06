<?php

// Emergency fix for syntax error
// Upload this to production and run it

echo "<h2>🚨 Emergency Syntax Error Fix</h2>\n";
echo "<pre>\n";

try {
    echo "Step 1: Clearing all Laravel caches...\n";
    
    // Clear caches using shell commands
    $commands = [
        'php artisan cache:clear',
        'php artisan config:clear', 
        'php artisan view:clear',
        'php artisan route:clear',
        'php artisan optimize:clear'
    ];
    
    foreach ($commands as $cmd) {
        echo "Running: $cmd\n";
        $output = shell_exec($cmd . ' 2>&1');
        echo "Output: " . trim($output) . "\n\n";
    }
    
    echo "Step 2: Checking file permissions...\n";
    $files = [
        'app/Http/Controllers/InspectionController.php',
        'bootstrap/cache',
        'storage/logs',
        'storage/framework/cache',
        'storage/framework/views'
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            $perms = substr(sprintf('%o', fileperms($file)), -4);
            echo "$file: $perms\n";
        } else {
            echo "$file: NOT FOUND\n";
        }
    }
    
    echo "\nStep 3: Testing PHP syntax on controller...\n";
    $syntaxCheck = shell_exec('php -l app/Http/Controllers/InspectionController.php 2>&1');
    echo "Syntax check: " . trim($syntaxCheck) . "\n\n";
    
    echo "Step 4: Regenerating optimized files...\n";
    $optimize = shell_exec('php artisan optimize 2>&1');
    echo "Optimize: " . trim($optimize) . "\n\n";
    
    echo "✅ EMERGENCY FIX COMPLETED\n\n";
    echo "💡 NEXT STEPS:\n";
    echo "1. Try your inspection form again\n";
    echo "2. If still getting errors, check:\n";
    echo "   - PHP version compatibility\n";
    echo "   - Server error logs\n";
    echo "   - Browser developer console\n";
    echo "   - Laravel logs in storage/logs/\n\n";
    
    echo "🔧 If errors persist, the issue might be:\n";
    echo "   - Production PHP version differences\n";
    echo "   - Server configuration limits\n";
    echo "   - Database connection issues\n";
    echo "   - Form submission size limits\n";
    
} catch (Exception $e) {
    echo "❌ ERROR during fix: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
