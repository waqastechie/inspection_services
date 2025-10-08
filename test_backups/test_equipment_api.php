<?php

echo "=== TESTING EQUIPMENT API ENDPOINTS ===\n";

// Base URL for the local Laravel server
$baseUrl = 'http://127.0.0.1:8080';

function makeRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['code' => $httpCode, 'body' => $response];
}

// Test equipment types API
echo "1. Testing Equipment Types API...\n";
$response = makeRequest($baseUrl . '/api/equipment-types');
if ($response['code'] === 200) {
    $data = json_decode($response['body'], true);
    if (isset($data['data'])) {
        echo "   ✓ Equipment Types API working. Found " . count($data['data']) . " types\n";
    } else {
        echo "   ✓ Equipment Types API responded with code 200\n";
    }
} else {
    echo "   ✗ Equipment Types API failed. HTTP Code: " . $response['code'] . "\n";
}

// Test equipment API
echo "2. Testing Equipment API...\n";
$response = makeRequest($baseUrl . '/api/equipment');
if ($response['code'] === 200) {
    $data = json_decode($response['body'], true);
    if (isset($data['data'])) {
        echo "   ✓ Equipment API working. Found " . count($data['data']) . " items\n";
    } else {
        echo "   ✓ Equipment API responded with code 200\n";
    }
} else {
    echo "   ✗ Equipment API failed. HTTP Code: " . $response['code'] . "\n";
}

// Test equipment page accessibility
echo "3. Testing Equipment Page...\n";
$response = makeRequest($baseUrl . '/equipment');
if ($response['code'] === 200) {
    echo "   ✓ Equipment page is accessible\n";
} else {
    echo "   ✗ Equipment page failed. HTTP Code: " . $response['code'] . "\n";
}

echo "\n=== EQUIPMENT SYSTEM TEST COMPLETE ===\n";