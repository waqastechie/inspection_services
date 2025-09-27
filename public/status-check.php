<?php
// Simple syntax check that can be accessed via browser
header('Content-Type: text/plain');

echo "=== INSPECTION SYSTEM STATUS CHECK ===\n\n";

// Check if Inspection model file exists and is valid
$inspectionFile = __DIR__ . '/../app/Models/Inspection.php';
if (file_exists($inspectionFile)) {
    echo "✅ Inspection.php exists\n";
    
    // Check for syntax errors
    $output = [];
    $return_var = 0;
    exec("php -l \"$inspectionFile\"", $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ Inspection.php syntax is valid\n";
    } else {
        echo "❌ Syntax error in Inspection.php:\n";
        echo implode("\n", $output) . "\n";
    }
} else {
    echo "❌ Inspection.php not found\n";
}

// Check edit blade file
$editBladeFile = __DIR__ . '/../resources/views/inspections/edit.blade.php';
if (file_exists($editBladeFile)) {
    echo "✅ edit.blade.php exists\n";
    
    $content = file_get_contents($editBladeFile);
    
    // Check for common syntax issues
    $ifCount = substr_count($content, '@if');
    $endifCount = substr_count($content, '@endif');
    
    if ($ifCount === $endifCount) {
        echo "✅ Balanced @if/@endif statements ($ifCount each)\n";
    } else {
        echo "❌ Unbalanced @if/@endif: $ifCount @if vs $endifCount @endif\n";
    }
    
    // Check for comment blocks
    $commentStart = substr_count($content, '{{--');
    $commentEnd = substr_count($content, '--}}');
    
    if ($commentStart === $commentEnd) {
        echo "✅ Balanced comment blocks ($commentStart each)\n";
    } else {
        echo "❌ Unbalanced comments: $commentStart {{-- vs $commentEnd --}}\n";
    }
} else {
    echo "❌ edit.blade.php not found\n";
}

echo "\n=== STATUS: ";
if (file_exists($inspectionFile) && file_exists($editBladeFile)) {
    echo "READY TO TEST ===\n";
    echo "\nTry accessing your inspection pages now!\n";
} else {
    echo "ISSUES DETECTED ===\n";
}
?>
