<?php

// Test the application via HTTP to see if we can reproduce the error

$testUrls = [
    'http://127.0.0.1:8080/',
    'http://127.0.0.1:8080/api/personnel',
    'http://127.0.0.1:8080/inspections/api/personnel',
    'http://127.0.0.1:8080/debug-client-api',
    'http://127.0.0.1:8080/test-db'
];

echo "=== TESTING APPLICATION VIA HTTP ===\n";

foreach ($testUrls as $url) {
    echo "\nTesting: {$url}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "  ✗ CURL Error: {$error}\n";
        continue;
    }
    
    echo "  HTTP Code: {$httpCode}\n";
    
    // Check if response contains the personnel error
    if (strpos($response, "Table 'sc.personnel' doesn't exist") !== false) {
        echo "  ✗ FOUND THE ERROR! This URL triggers the personnel table issue\n";
        echo "  Response excerpt:\n";
        $lines = explode("\n", $response);
        foreach ($lines as $line) {
            if (strpos($line, 'personnel') !== false || strpos($line, 'employee_id') !== false) {
                echo "    {$line}\n";
            }
        }
    } elseif ($httpCode >= 200 && $httpCode < 300) {
        echo "  ✓ Success\n";
    } elseif ($httpCode >= 400) {
        echo "  ! HTTP Error {$httpCode}\n";
        // Show first few lines of response for debugging
        $lines = explode("\n", $response);
        for ($i = 0; $i < min(5, count($lines)); $i++) {
            if (!empty(trim($lines[$i]))) {
                echo "    {$lines[$i]}\n";
            }
        }
    }
}

echo "\n=== HTTP TEST COMPLETE ===\n";