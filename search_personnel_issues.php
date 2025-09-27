<?php

echo "=== COMPREHENSIVE SEARCH FOR PERSONNEL TABLE REFERENCES ===\n";

// Function to search for patterns in files
function searchInFile($filepath, $pattern) {
    if (!file_exists($filepath) || !is_readable($filepath)) {
        return [];
    }
    
    $content = file_get_contents($filepath);
    $matches = [];
    
    // Search for the pattern
    if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
        $results = [];
        foreach ($matches[0] as $match) {
            $lines = substr_count(substr($content, 0, $match[1]), "\n") + 1;
            $results[] = [
                'line' => $lines,
                'match' => $match[0],
                'context' => trim(substr($content, max(0, $match[1] - 50), 100))
            ];
        }
        return $results;
    }
    
    return [];
}

// Patterns to search for
$patterns = [
    '/table\([\'"]personnel[\'"][^s]/i',  // table('personnel') but not table('personnels')
    '/from\s+personnel\s+/i',             // FROM personnel
    '/join\s+personnel\s+/i',             // JOIN personnel  
    '/insert\s+into\s+personnel\s+/i',    // INSERT INTO personnel
    '/update\s+personnel\s+/i',           // UPDATE personnel
    '/delete\s+from\s+personnel\s+/i',    // DELETE FROM personnel
];

$directories = [
    __DIR__ . '/app',
    __DIR__ . '/routes',
    __DIR__ . '/database',
    __DIR__ . '/resources/views',
    __DIR__ . '/public/js',
];

$foundIssues = [];

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match('/\.(php|js|blade\.php)$/', $file->getFilename())) {
            $filepath = $file->getPathname();
            
            foreach ($patterns as $pattern) {
                $matches = searchInFile($filepath, $pattern);
                if (!empty($matches)) {
                    $foundIssues[] = [
                        'file' => $filepath,
                        'pattern' => $pattern,
                        'matches' => $matches
                    ];
                }
            }
        }
    }
}

if (empty($foundIssues)) {
    echo "✅ NO PROBLEMATIC PERSONNEL TABLE REFERENCES FOUND!\n";
    echo "All table references appear to be using the correct 'personnels' table name.\n\n";
    
    echo "This suggests the error you're seeing might be:\n";
    echo "1. 🔄 Browser cache - Try hard refresh (Ctrl+F5)\n";
    echo "2. 🔄 PHP OpCache - Try restarting Apache/Nginx\n";
    echo "3. 🔄 Laravel cache - Already cleared with artisan commands\n";
    echo "4. 📁 Hidden file - Check .env, config files, or custom scripts\n";
    
} else {
    echo "❌ FOUND " . count($foundIssues) . " POTENTIAL ISSUES:\n\n";
    
    foreach ($foundIssues as $issue) {
        echo "📁 File: " . str_replace(__DIR__, '', $issue['file']) . "\n";
        echo "🔍 Pattern: " . $issue['pattern'] . "\n";
        
        foreach ($issue['matches'] as $match) {
            echo "   Line {$match['line']}: {$match['match']}\n";
            echo "   Context: ...{$match['context']}...\n";
        }
        echo "\n";
    }
}

echo "=== SEARCH COMPLETE ===\n";