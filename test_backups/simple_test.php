<?php

// Simple test without Laravel bootstrap
echo "Testing basic PHP functionality...\n";
echo "✓ PHP is working\n";

// Test if the file exists
$inspectionFile = __DIR__ . '/app/Models/Inspection.php';
if (file_exists($inspectionFile)) {
    echo "✓ Inspection.php file exists\n";
} else {
    echo "❌ Inspection.php file not found\n";
    exit(1);
}

// Test basic file content
$content = file_get_contents($inspectionFile);
if (strpos($content, 'class Inspection extends Model') !== false) {
    echo "✓ Inspection class found\n";
} else {
    echo "❌ Inspection class not found in file\n";
    exit(1);
}

// Check for image relationships (should not exist)
if (strpos($content, 'images()') === false && strpos($content, 'HasMany') === false) {
    echo "✓ No image relationships found - good!\n";
} else {
    echo "❌ Image relationships still exist in model\n";
    exit(1);
}

echo "\n✅ Basic file checks passed!\n";
echo "Now try accessing your application in the browser.\n";
