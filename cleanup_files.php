<?php
// Cleanup script to remove all test and development files

$patterns = [
    'test_*.php',
    'check_*.php', 
    'debug_*.php',
    'create_*.php',
    'analyze_*.php',
    'simple_*.php',
    'detailed_*.php',
    'emergency_*.php',
    'syntax_*.php',
    'production_*.php',
    'update_*.php',
    'enhance_*.php',
    'fix_*.php',
    'seed_*.php',
    'temp_*.php',
    '*.bat',
    '*.html',
    '*.pdf',
    '*.sql',
    '*.sh',
    '*.txt'
];

$keepFiles = [
    'composer.json',
    'composer.lock',
    'package.json',
    'artisan',
    'README.md',
    'CHANGELOG.md',
    'phpunit.xml',
    'vite.config.js',
    '.env',
    '.env.example',
    'LICENSE'
];

$deleted = 0;
$errors = 0;

foreach ($patterns as $pattern) {
    $files = glob($pattern);
    foreach ($files as $file) {
        if (is_file($file) && !in_array($file, $keepFiles)) {
            if (unlink($file)) {
                echo "Deleted: $file\n";
                $deleted++;
            } else {
                echo "Error deleting: $file\n";
                $errors++;
            }
        }
    }
}

echo "\nCleanup complete!\n";
echo "Files deleted: $deleted\n";
echo "Errors: $errors\n";
?>
