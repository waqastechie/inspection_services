<?php
// Simple test route to check database
use Illuminate\Support\Facades\Route;
use App\Models\Client;
use App\Models\Inspection;

Route::get('/test-db', function () {
    try {
        $clientCount = Client::count();
        $inspectionCount = Inspection::count();
        
        $output = "Database Test Results:\n";
        $output .= "Clients: {$clientCount}\n";
        $output .= "Inspections: {$inspectionCount}\n\n";
        
        if ($clientCount > 0) {
            $client = Client::first();
            $inspectionCount = $client->inspections()->count();
            $output .= "First client '{$client->client_name}' has {$inspectionCount} inspections\n";
        }
        
        if ($inspectionCount > 0) {
            $inspection = Inspection::with('client')->first();
            $clientName = $inspection->client ? $inspection->client->client_name : 'No Client';
            $output .= "First inspection '{$inspection->inspection_number}' belongs to client: {$clientName}\n";
        }
        
        return response($output)->header('Content-Type', 'text/plain');
        
    } catch (Exception $e) {
        return response("Error: " . $e->getMessage())->header('Content-Type', 'text/plain');
    }
});
