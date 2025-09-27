<?php
// Test the client-inspection relationship
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Client;
use App\Models\Inspection;

try {
    echo "Testing Client-Inspection Relationship:\n";
    echo "=====================================\n\n";
    
    // Test 1: Get clients with inspection count
    echo "Clients with inspection counts:\n";
    $clients = Client::withCount('inspections')->get();
    
    foreach ($clients as $client) {
        echo "- {$client->client_name}: {$client->inspections_count} inspections\n";
    }
    
    echo "\nInspections with client names:\n";
    $inspections = Inspection::with('client')->get();
    
    foreach ($inspections as $inspection) {
        $clientName = $inspection->client ? $inspection->client->client_name : 'No Client';
        echo "- {$inspection->inspection_number}: {$clientName}\n";
    }
    
    echo "\n✅ Relationship test completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
