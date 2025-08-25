<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    // Check if clients already exist
    $existingClients = App\Models\Client::count();
    
    if ($existingClients > 0) {
        echo "Clients already exist ({$existingClients} found). Skipping creation.\n";
        exit;
    }
    
    echo "Creating sample clients...\n";
    
    $clients = [
        [
            'client_name' => 'Saipem Trechville LLC',
            'client_code' => 'ST01',
            'company_type' => 'LLC',
            'industry' => 'Oil & Gas',
            'address' => '123 Industrial Boulevard',
            'city' => 'Houston',
            'state' => 'TX',
            'country' => 'United States',
            'postal_code' => '77001',
            'phone' => '+1 (713) 555-0101',
            'email' => 'contact@saipem-trechville.com',
            'website' => 'https://www.saipem.com',
            'contact_person' => 'John Martinez',
            'contact_position' => 'Project Manager',
            'contact_phone' => '+1 (713) 555-0102',
            'contact_email' => 'j.martinez@saipem-trechville.com',
            'tax_id' => '74-1234567',
            'payment_terms' => 'Net 30',
            'credit_limit' => 50000.00,
            'preferred_currency' => 'USD',
            'notes' => 'Major oil & gas contractor with multiple offshore platforms',
            'is_active' => true,
        ],
        [
            'client_name' => 'Gulf Marine Services Inc',
            'client_code' => 'GMS2',
            'company_type' => 'Corporation',
            'industry' => 'Marine/Offshore',
            'address' => '456 Harbor Drive',
            'city' => 'New Orleans',
            'state' => 'LA',
            'country' => 'United States',
            'postal_code' => '70112',
            'phone' => '+1 (504) 555-0201',
            'email' => 'info@gulfmarine.com',
            'website' => 'https://www.gulfmarine.com',
            'contact_person' => 'Sarah Johnson',
            'contact_position' => 'Operations Director',
            'contact_phone' => '+1 (504) 555-0202',
            'contact_email' => 's.johnson@gulfmarine.com',
            'tax_id' => '72-2345678',
            'payment_terms' => 'Net 15',
            'credit_limit' => 75000.00,
            'preferred_currency' => 'USD',
            'notes' => 'Specialized in marine vessel inspections and certifications',
            'is_active' => true,
        ],
        [
            'client_name' => 'Petro Chemical Solutions Ltd',
            'client_code' => 'PCS3',
            'company_type' => 'Corporation',
            'industry' => 'Chemical',
            'address' => '789 Refinery Road',
            'city' => 'Pasadena',
            'state' => 'TX',
            'country' => 'United States',
            'postal_code' => '77506',
            'phone' => '+1 (281) 555-0301',
            'email' => 'contact@petrochemsolutions.com',
            'contact_person' => 'Michael Chen',
            'contact_position' => 'Safety Manager',
            'contact_phone' => '+1 (281) 555-0302',
            'contact_email' => 'm.chen@petrochemsolutions.com',
            'tax_id' => '76-3456789',
            'payment_terms' => 'Net 30',
            'credit_limit' => 25000.00,
            'preferred_currency' => 'USD',
            'notes' => 'Petrochemical processing facility requiring regular NDT inspections',
            'is_active' => true,
        ],
        [
            'client_name' => 'Atlantic Crane & Rigging Co',
            'client_code' => 'ACR4',
            'company_type' => 'LLC',
            'industry' => 'Construction',
            'address' => '321 Crane Avenue',
            'city' => 'Mobile',
            'state' => 'AL',
            'country' => 'United States',
            'postal_code' => '36601',
            'phone' => '+1 (251) 555-0401',
            'email' => 'service@atlanticcrane.com',
            'website' => 'https://www.atlanticcrane.com',
            'contact_person' => 'Robert Wilson',
            'contact_position' => 'Equipment Manager',
            'contact_phone' => '+1 (251) 555-0402',
            'contact_email' => 'r.wilson@atlanticcrane.com',
            'tax_id' => '63-4567890',
            'payment_terms' => 'Net 30',
            'credit_limit' => 40000.00,
            'preferred_currency' => 'USD',
            'notes' => 'Heavy lifting and rigging equipment requiring thorough examinations',
            'is_active' => true,
        ],
        [
            'client_name' => 'Energy Systems Manufacturing',
            'client_code' => 'ESM5',
            'company_type' => 'Corporation',
            'industry' => 'Manufacturing',
            'address' => '555 Factory Street',
            'city' => 'Beaumont',
            'state' => 'TX',
            'country' => 'United States',
            'postal_code' => '77701',
            'phone' => '+1 (409) 555-0501',
            'email' => 'quality@energysystems.com',
            'contact_person' => 'Lisa Thompson',
            'contact_position' => 'Quality Assurance Lead',
            'contact_phone' => '+1 (409) 555-0502',
            'contact_email' => 'l.thompson@energysystems.com',
            'tax_id' => '74-5678901',
            'payment_terms' => 'Net 45',
            'credit_limit' => 60000.00,
            'preferred_currency' => 'USD',
            'notes' => 'Manufacturer of energy sector equipment and components',
            'is_active' => true,
        ]
    ];
    
    foreach ($clients as $clientData) {
        $client = App\Models\Client::create($clientData);
        echo "Created client: {$client->client_name} ({$client->client_code})\n";
    }
    
    echo "\nSuccessfully created " . count($clients) . " sample clients!\n";
    echo "You can now access the client management at: http://127.0.0.1:8001/admin/clients\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
