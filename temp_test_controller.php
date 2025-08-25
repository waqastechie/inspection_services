<?php

// Temporary test to create inspection without problematic fields

$data = [
    'inspection_number' => 'INS' . date('Ymd') . '001',
    'client_name' => 'Test Client',
    'project_name' => 'Test Project',
    'location' => 'Test Location',
    'inspection_date' => '2025-08-18',
    'equipment_type' => 'spreader_beam',
    'lead_inspector_name' => 'Test Inspector',
    'lead_inspector_certification' => 'Test Cert',
    'status' => 'submitted',
    'report_date' => now(),
    'equipment_description' => 'Test Equipment'
];

// This should work if the issue is with applicable_standard and inspection_class fields
echo "Test data prepared without problematic fields\n";
print_r($data);
