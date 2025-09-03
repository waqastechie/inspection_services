<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$inspection = \App\Models\Inspection::with('services')->find(19);

if ($inspection) {
    echo "Inspection #" . $inspection->id . " Services:\n\n";
    
    foreach ($inspection->services as $service) {
        echo "=== " . $service->service_type_name . " ===\n";
        echo "Status: " . $service->status . "\n";
        echo "Notes: " . ($service->notes ?: 'None') . "\n";
        echo "Service Data:\n";
        if ($service->service_data) {
            $serviceData = is_string($service->service_data) ? json_decode($service->service_data, true) : $service->service_data;
            if (is_array($serviceData)) {
                foreach ($serviceData as $key => $value) {
                    echo "  - $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
                }
            } else {
                echo "  Raw data: " . $service->service_data . "\n";
            }
        } else {
            echo "  No service data\n";
        }
        echo "\n";
    }
} else {
    echo "Inspection not found\n";
}
