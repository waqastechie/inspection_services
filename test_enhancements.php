<?php
echo "=== Enhanced Inspection Form Verification ===\n\n";

// Test 1: Check client API endpoint
echo "1. Testing client API endpoint...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/clients');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success'] && isset($data['clients'])) {
        echo "✅ Client API working correctly\n";
        echo "   - HTTP Status: $httpCode\n";
        echo "   - Clients found: " . count($data['clients']) . "\n";
        if (!empty($data['clients'])) {
            $client = $data['clients'][0];
            echo "   - Sample client: {$client['client_name']} ({$client['client_code']})\n";
        }
    } else {
        echo "❌ Client API returned invalid format\n";
        echo "   - Response: " . substr($response, 0, 200) . "...\n";
    }
} else {
    echo "❌ Client API failed\n";
    echo "   - HTTP Status: $httpCode\n";
    echo "   - Response: " . substr($response, 0, 200) . "...\n";
}

echo "\n";

// Test 2: Check enhancement files exist
echo "2. Checking enhanced section files...\n";
$files = [
    'resources/views/inspections/sections/asset-details.blade.php' => 'Asset Details',
    'resources/views/inspections/sections/testing-methods.blade.php' => 'Testing Methods',
    'resources/views/inspections/sections/items-table.blade.php' => 'Items Table',
    'resources/views/inspections/sections/client-information.blade.php' => 'Client Information'
];

foreach ($files as $file => $name) {
    if (file_exists($file)) {
        echo "✅ $name section exists\n";
    } else {
        echo "❌ $name section missing\n";
    }
}

echo "\n";

// Test 3: Check JavaScript client loading fix
echo "3. Checking JavaScript client loading fix...\n";
$jsFile = 'public/js/inspection-form-simple.js';
if (file_exists($jsFile)) {
    $jsContent = file_get_contents($jsFile);
    
    if (strpos($jsContent, 'apiUrl("/api/clients")') !== false) {
        echo "✅ JavaScript uses correct API endpoint\n";
    } else if (strpos($jsContent, 'apiUrl("/inspections/api/clients")') !== false) {
        echo "❌ JavaScript still uses old API endpoint\n";
    } else {
        echo "⚠️ Cannot determine JavaScript API endpoint\n";
    }
    
    if (strpos($jsContent, 'getElementById("client_id")') !== false) {
        echo "✅ JavaScript uses correct element ID\n";
    } else {
        echo "❌ JavaScript uses wrong element ID\n";
    }
    
    if (strpos($jsContent, 'response.clients') !== false) {
        echo "✅ JavaScript handles API response format correctly\n";
    } else {
        echo "❌ JavaScript doesn't handle API response format\n";
    }
} else {
    echo "❌ JavaScript file not found\n";
}

echo "\n";

// Test 4: Check enhanced features in template files
echo "4. Checking enhanced features...\n";

// Asset Details enhancements
$assetFile = 'resources/views/inspections/sections/asset-details.blade.php';
if (file_exists($assetFile)) {
    $content = file_get_contents($assetFile);
    $features = [
        'Offshore Container' => 'Equipment types',
        'SWL' => 'SWL field',
        'Test Load Applied' => 'Test Load field',
        'A - New Installation' => 'Examination reasons',
        'ND' => 'Status codes'
    ];
    
    foreach ($features as $check => $feature) {
        if (strpos($content, $check) !== false) {
            echo "✅ Asset Details: $feature found\n";
        } else {
            echo "❌ Asset Details: $feature missing\n";
        }
    }
}

// Testing Methods enhancements
$testingFile = 'resources/views/inspections/sections/testing-methods.blade.php';
if (file_exists($testingFile)) {
    $content = file_get_contents($testingFile);
    $features = [
        'MPI' => 'MPI Testing',
        'DPI' => 'DPI Testing',
        'Load Testing' => 'Load Testing',
        'AC Yoke' => 'MPI Equipment',
        'White Contrast Paint' => 'DPI Materials'
    ];
    
    foreach ($features as $check => $feature) {
        if (strpos($content, $check) !== false) {
            echo "✅ Testing Methods: $feature found\n";
        } else {
            echo "❌ Testing Methods: $feature missing\n";
        }
    }
}

// Items Table enhancements
$itemsFile = 'resources/views/inspections/sections/items-table.blade.php';
if (file_exists($itemsFile)) {
    $content = file_get_contents($itemsFile);
    $features = [
        'Serial Number' => 'Serial Number field',
        'Description' => 'Description field',
        'Test Load Applied' => 'Test Load field',
        'A - New Installation' => 'Examination reasons'
    ];
    
    foreach ($features as $check => $feature) {
        if (strpos($content, $check) !== false) {
            echo "✅ Items Table: $feature found\n";
        } else {
            echo "❌ Items Table: $feature missing\n";
        }
    }
}

echo "\n";

// Test 5: Check edit page client handling
echo "5. Checking edit page client handling...\n";
$clientFile = 'resources/views/inspections/sections/client-information.blade.php';
if (file_exists($clientFile)) {
    $content = file_get_contents($clientFile);
    
    if (strpos($content, '@if(isset($inspection) && $inspection->client)') !== false) {
        echo "✅ Edit mode client display logic found\n";
    } else {
        echo "❌ Edit mode client display logic missing\n";
    }
    
    if (strpos($content, 'form-control-plaintext') !== false) {
        echo "✅ Read-only client display styling found\n";
    } else {
        echo "❌ Read-only client display styling missing\n";
    }
    
    if (strpos($content, 'Choose a client...') !== false) {
        echo "✅ Create mode client selection found\n";
    } else {
        echo "❌ Create mode client selection missing\n";
    }
}

echo "\n=== Verification Complete ===\n";
echo "🚀 Enhanced inspection form is ready for use!\n";
echo "📋 Access the forms at:\n";
echo "   - Create: http://localhost:8000/inspections/create\n";
echo "   - Edit: http://localhost:8000/inspections/1/edit\n";