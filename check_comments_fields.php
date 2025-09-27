<?php
// Quick test to check if comments fields exist in database
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Inspection;

echo "Checking Comments & Recommendations fields in database...\n\n";

// Get the latest inspection
$inspection = Inspection::latest()->first();

if (!$inspection) {
    echo "No inspections found in database.\n";
    exit;
}

echo "Inspection: {$inspection->inspection_number}\n";
echo "Inspector Comments: " . ($inspection->inspector_comments ?? 'NULL') . "\n";
echo "Recommendations: " . ($inspection->recommendations ?? 'NULL') . "\n";
echo "Defects Found: " . ($inspection->defects_found ?? 'NULL') . "\n";
echo "Overall Result: " . ($inspection->overall_result ?? 'NULL') . "\n";
echo "Next Inspection Date: " . ($inspection->next_inspection_date ?? 'NULL') . "\n";

// Check if the fields exist in the database structure
try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('inspections');
    
    echo "\nChecking database columns:\n";
    $commentsFields = ['inspector_comments', 'recommendations', 'defects_found', 'overall_result', 'next_inspection_date'];
    
    foreach ($commentsFields as $field) {
        $exists = in_array($field, $columns);
        echo "- $field: " . ($exists ? '✓ EXISTS' : '✗ MISSING') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error checking database structure: " . $e->getMessage() . "\n";
}

echo "\nDone.\n";
?>
