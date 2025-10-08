<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;

try {
    echo "=== Testing Client API ===\n";
    
    // Test database connection and client count
    $clientCount = Client::count();
    echo "Total clients in database: $clientCount\n";
    
    if ($clientCount > 0) {
        echo "\nClients in database:\n";
        $clients = Client::select('id', 'client_name', 'client_code', 'is_active')->get();
        foreach ($clients as $client) {
            $status = $client->is_active ? 'Active' : 'Inactive';
            echo "- ID: {$client->id}, Name: {$client->client_name}, Code: {$client->client_code}, Status: $status\n";
        }
    }
    
    // Test the API endpoint logic
    echo "\n=== Testing API Logic ===\n";
    $apiClients = Client::active()
        ->select([
            'id',
            'client_name',
            'client_code',
            'company_type',
            'industry',
            'address',
            'city',
            'state',
            'country',
            'postal_code',
            'phone',
            'email',
            'website',
            'contact_person',
            'contact_position',
            'contact_phone',
            'contact_email',
            'payment_terms',
            'preferred_currency',
            'notes'
        ])
        ->orderBy('client_name')
        ->limit(50)
        ->get();
        
    echo "API would return " . $apiClients->count() . " clients\n";
    
    if ($apiClients->count() > 0) {
        echo "\nAPI Response Data:\n";
        foreach ($apiClients as $client) {
            $displayName = $client->client_code ? 
                "{$client->client_name} ({$client->client_code})" : 
                $client->client_name;
            echo "- {$displayName}\n";
        }
        
        echo "\nFirst client JSON:\n";
        echo json_encode($apiClients->first()->toArray(), JSON_PRETTY_PRINT) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
