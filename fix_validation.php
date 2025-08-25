<?php

// Find and replace validation rules
$content = file_get_contents('app/Http/Controllers/InspectionController.php');

// Replace the validation rules
$content = str_replace(
    "'applicable_standard' => 'nullable|string|max:255',",
    "// 'applicable_standard' => 'nullable|string|max:255', // REMOVED - problematic field",
    $content
);

$content = str_replace(
    "'inspection_class' => 'nullable|string|max:100',",
    "// 'inspection_class' => 'nullable|string|max:100', // REMOVED - problematic field",
    $content
);

file_put_contents('app/Http/Controllers/InspectionController.php', $content);

echo "Validation rules updated!\n";
