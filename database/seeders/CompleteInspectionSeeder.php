<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inspection;
use App\Models\InspectionService;
use App\Models\PersonnelAssignment;
use App\Models\EquipmentAssignment;
use App\Models\ConsumableAssignment;
use App\Models\InspectionResult;
use App\Models\User;
use App\Models\Client;
use App\Models\Personnel;
use App\Models\Equipment;
use App\Models\Consumable;

class CompleteInspectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First ensure we have required data
        $this->ensureBasicData();
        
        // Create a complete inspection with all fields populated
        $inspection = $this->createCompleteInspection();
        
        // Add services
        $this->addInspectionServices($inspection);
        
        // Add personnel assignments (for existing records)
        $this->addPersonnelAssignments($inspection);
        
        // Add equipment assignments
        $this->addEquipmentAssignments($inspection);
        
        // Add consumable assignments
        $this->addConsumableAssignments($inspection);
        
        // Add inspection results/items
        $this->addInspectionResults($inspection);
        
        // Add sample images
        $this->addSampleImages($inspection);
        
        echo "Complete inspection seeded successfully!\n";
        echo "Inspection ID: {$inspection->id}\n";
        echo "Inspection Number: {$inspection->inspection_number}\n";
    }
    
    private function ensureBasicData()
    {
        // Ensure we have clients
        if (Client::count() === 0) {
            Client::create([
                'company_name' => 'Offshore Engineering Solutions Ltd',
                'contact_person' => 'David Richardson',
                'email' => 'david.richardson@oesl.com',
                'phone' => '+44 1224 567890',
                'address' => '123 North Sea Boulevard, Aberdeen, AB12 3CD, United Kingdom',
                'is_active' => true,
            ]);
        }
        
        // Ensure we have personnel
        if (Personnel::count() === 0) {
            Personnel::create([
                'first_name' => 'James',
                'last_name' => 'Henderson',
                'position' => 'Senior NDT Inspector',
                'department' => 'Quality Assurance',
                'employee_id' => 'INS001',
                'email' => 'james.henderson@company.com',
                'phone' => '+44 7890 123456',
                'qualifications' => 'CSWIP 3.1, ASNT Level III',
                'certifications' => 'NDT Level 3 (UT, RT, MT, PT), LEEA Inspector',
                'is_active' => true,
            ]);
        }
        
        // Ensure we have equipment
        if (Equipment::count() === 0) {
            Equipment::create([
                'name' => 'Ultrasonic Thickness Meter',
                'type' => 'NDT Equipment',
                'brand_model' => 'Olympus 45MG',
                'serial_number' => 'UT45MG-2024-001',
                'is_active' => true,
            ]);
        }
        
        // Ensure we have consumables
        if (Consumable::count() === 0) {
            Consumable::create([
                'name' => 'Magnetic Particles',
                'type' => 'NDT Consumable',
                'brand_manufacturer' => 'Magnaflux',
                'unit' => 'kg',
                'is_active' => true,
            ]);
        }
        
        // Ensure we have inspector users
        if (!User::where('role', 'inspector')->exists()) {
            User::create([
                'name' => 'Sarah Mitchell',
                'email' => 'sarah.mitchell@company.com',
                'password' => bcrypt('password123'),
                'role' => 'inspector',
                'department' => 'NDT Services',
                'certification' => 'ASNT Level II, API 510 Inspector',
                'is_active' => true,
            ]);
        }
    }
    
    private function createCompleteInspection()
    {
        $client = Client::first();
        
        // Debug client info
        echo "Client found: " . ($client ? $client->company_name : 'No client found') . "\n";
        
        return Inspection::create([
            'inspection_number' => Inspection::generateInspectionNumber(),
            'client_name' => 'Offshore Engineering Solutions Ltd', // Force the name for now
            'project_name' => 'North Sea Platform Alpha - Annual Inspection Campaign',
            'location' => 'Platform Alpha, North Sea, Block 15/25, UK Continental Shelf',
            'area_of_examination' => 'Primary crane boom assembly and lifting attachments',
            'services_performed' => 'Load Test, Lifting Examination, Thorough Examination, MPI, Visual Inspection',
            'contract' => 'NSP-2025-LIFT-001',
            'work_order' => 'WO-2025-09-001',
            'purchase_order' => 'PO-2025-LIFT-456',
            'client_job_reference' => 'ALPHA-CRANE-ANNUAL-2025',
            'job_ref' => 'JR-LIFT-2025-001',
            'standards' => 'BS EN 14502-1:2005, LEEA 051, API RP 2D',
            'local_procedure_number' => 'LP-LIFT-001-Rev.5',
            'drawing_number' => 'DWG-CRANE-ALPHA-001-C',
            'test_restrictions' => 'Testing limited to daylight hours due to offshore safety protocols',
            'surface_condition' => 'Good - Recently painted with epoxy coating, minor surface wear acceptable',
            'inspection_date' => '2025-09-02',
            'weather_conditions' => 'Clear with light winds',
            'temperature' => 18.5,
            'humidity' => 72,
            'equipment_type' => 'Offshore Pedestal Crane',
            'equipment_description' => '50-tonne capacity offshore pedestal crane with telescopic boom',
            'manufacturer' => 'MacGregor',
            'model' => 'MGC-50T-Offshore',
            'serial_number' => 'MGC-2019-0847',
            'asset_tag' => 'ALPHA-CRANE-001',
            'manufacture_year' => 2019,
            'capacity' => 50.0,
            'capacity_unit' => 'tonnes',
            'applicable_standard' => 'BS EN 14502-1:2005+A1:2009',
            'inspection_class' => 'Class A - Critical Lifting Equipment',
            'certification_body' => 'Lloyd\'s Register EMEA',
            'previous_certificate_number' => 'LR-LIFT-2024-08734',
            'last_inspection_date' => '2024-09-01',
            'next_inspection_due' => '2026-09-01',
            'next_inspection_date' => '2026-08-15',
            'overall_result' => 'pass',
            'defects_found' => 'Minor wear on boom pivot pin bushing (Item 3.2). Slight corrosion on secondary winch housing (Item 4.1). Both items within acceptable limits per LEEA guidelines.',
            'recommendations' => 'Monitor boom pivot pin bushing wear - inspect again in 6 months. Apply touch-up paint to winch housing corrosion areas. Replace wire rope slings as scheduled (due Q4 2025).',
            'limitations' => 'Internal inspection of boom structure limited due to access restrictions. Full internal inspection recommended during next dry dock period.',
            'lead_inspector_name' => 'James Henderson',
            'lead_inspector_certification' => 'CSWIP 3.1 Senior Welding Inspector, ASNT Level III (UT/MT/PT), LEEA Lifting Equipment Inspector, API 510 Pressure Vessel Inspector',
            'inspector_comments' => 'Equipment found to be in good overall condition with only minor defects identified. All load tests passed successfully. Crane is fit for continued service until next scheduled inspection. Recommend maintaining current preventive maintenance schedule.',
            'general_notes' => 'Inspection conducted in accordance with client safety procedures. All personnel briefed on offshore safety requirements. Weather conditions favorable throughout inspection period.',
            'status' => 'completed',
            'report_date' => now(),
            'inspection_images' => json_encode([
                'images/inspections/crane-overview-001.jpg',
                'images/inspections/boom-assembly-002.jpg',
                'images/inspections/load-test-003.jpg',
                'images/inspections/mpi-results-004.jpg',
                'images/inspections/defect-detail-005.jpg'
            ]),
            'service_notes' => json_encode([
                'lifting_examination' => [
                    'inspector_id' => User::where('role', 'inspector')->first()->id,
                    'inspection_time' => '09:30',
                    'notes' => 'All structural connections inspected and found satisfactory'
                ],
                'load_test' => [
                    'inspector_id' => User::where('role', 'inspector')->first()->id,
                    'inspection_time' => '11:00',
                    'test_load' => '62.5 tonnes (125% SWL)',
                    'duration' => '10 minutes',
                    'result' => 'PASS - No deformation observed'
                ]
            ])
        ]);
    }
    
    private function addInspectionServices($inspection)
    {
        $services = [
            [
                'service_type' => 'lifting_examination',
                'service_data' => [
                    'service_name' => 'Thorough Lifting Examination',
                    'service_description' => 'Comprehensive examination of crane structure, mechanisms, and safety systems',
                    'test_parameters' => 'Visual inspection, dimensional checks, function tests',
                    'acceptance_criteria' => 'No cracks, deformation, or safety system failures',
                    'applicable_codes' => 'BS EN 14502-1, LEEA 051',
                    'estimated_duration' => '4 hours',
                    'cost_estimate' => 2500.00
                ],
                'notes' => 'All structural connections inspected and found satisfactory'
            ],
            [
                'service_type' => 'load_test',
                'service_data' => [
                    'service_name' => 'Proof Load Test',
                    'service_description' => 'Static load test at 125% of Safe Working Load',
                    'test_parameters' => '62.5 tonnes for 10 minutes, measurement of deflection',
                    'acceptance_criteria' => 'No permanent deformation, return to original position',
                    'applicable_codes' => 'BS EN 14502-1 Section 9.3',
                    'estimated_duration' => '2 hours',
                    'cost_estimate' => 1800.00
                ],
                'notes' => 'Load test passed successfully - no deformation observed'
            ],
            [
                'service_type' => 'mpi_service',
                'service_data' => [
                    'service_name' => 'Magnetic Particle Inspection',
                    'service_description' => 'NDT examination of critical welded joints and connections',
                    'test_parameters' => 'Wet fluorescent method, 1500-3000 gauss field strength',
                    'acceptance_criteria' => 'No relevant indications per AWS D1.1',
                    'applicable_codes' => 'ASME Section V, AWS D1.1',
                    'estimated_duration' => '3 hours',
                    'cost_estimate' => 1200.00
                ],
                'notes' => 'All welds examined - no relevant indications found'
            ],
            [
                'service_type' => 'visual_examination',
                'service_data' => [
                    'service_name' => 'Visual Inspection',
                    'service_description' => 'Detailed visual examination of all accessible components',
                    'test_parameters' => 'Direct visual, borescope where required',
                    'acceptance_criteria' => 'No visible damage, corrosion within limits',
                    'applicable_codes' => 'ASME Section V Article 9',
                    'estimated_duration' => '2 hours',
                    'cost_estimate' => 800.00
                ],
                'notes' => 'Visual inspection completed - minor corrosion noted and documented'
            ]
        ];
        
        foreach ($services as $service) {
            InspectionService::create([
                'inspection_id' => $inspection->id,
                'service_type' => $service['service_type'],
                'service_data' => json_encode($service['service_data']),
                'notes' => $service['notes']
            ]);
        }
    }
    
    private function addPersonnelAssignments($inspection)
    {
        $personnel = Personnel::first();
        
        PersonnelAssignment::create([
            'inspection_id' => $inspection->id,
            'personnel_id' => $personnel ? $personnel->id : 'P001',
            'personnel_name' => $personnel ? ($personnel->first_name . ' ' . $personnel->last_name) : 'James Henderson',
            'role' => 'lead_inspector',
            'certification_level' => 'Level III',
            'certification_number' => 'ASNT-12345-III',
            'certification_expiry' => '2025-12-31',
            'assigned_services' => json_encode(['lifting_examination', 'load_test', 'visual_examination']),
            'notes' => 'Lead inspector responsible for overall inspection coordination and approval'
        ]);
    }
    
    private function addEquipmentAssignments($inspection)
    {
        $equipment = Equipment::first();
        
        $equipmentAssignments = [
            [
                'equipment_id' => $equipment ? $equipment->id : 'EQ001',
                'equipment_name' => $equipment ? $equipment->name : 'Ultrasonic Thickness Meter',
                'equipment_type' => 'NDT Equipment',
                'make_model' => $equipment ? $equipment->brand_model : 'Olympus 45MG',
                'serial_number' => $equipment ? $equipment->serial_number : 'UT45MG-2024-001',
                'condition' => 'excellent',
                'calibration_status' => 'current',
                'last_calibration_date' => '2025-01-15',
                'next_calibration_date' => '2026-01-15',
                'calibration_certificate' => 'CAL-2025-UT-001',
                'assigned_services' => json_encode(['visual_examination']),
                'notes' => 'Last serviced 2025-01-20, all functions operating normally'
            ],
            [
                'equipment_id' => 'EQ002',
                'equipment_name' => 'Test Weights - Certified',
                'equipment_type' => 'Load Testing',
                'make_model' => 'Straightpoint SP-75T',
                'serial_number' => 'SP-75T-2023-042',
                'condition' => 'good',
                'calibration_status' => 'current',
                'last_calibration_date' => '2025-02-01',
                'next_calibration_date' => '2026-02-01',
                'calibration_certificate' => 'CAL-2025-LT-002',
                'assigned_services' => json_encode(['load_test']),
                'notes' => 'Certified test weights, traceable to national standards'
            ]
        ];
        
        foreach ($equipmentAssignments as $assignment) {
            EquipmentAssignment::create(array_merge($assignment, [
                'inspection_id' => $inspection->id
            ]));
        }
    }
    
    private function addConsumableAssignments($inspection)
    {
        $consumable = Consumable::first();
        
        $consumableAssignments = [
            [
                'consumable_id' => $consumable ? $consumable->id : 'C001',
                'consumable_name' => $consumable ? $consumable->name : 'Magnetic Particles',
                'consumable_type' => 'NDT Consumable',
                'brand_manufacturer' => $consumable ? $consumable->brand_manufacturer : 'Magnaflux',
                'product_code' => 'MF-14AM-FLO',
                'batch_lot_number' => 'MF2025-001-A',
                'expiry_date' => '2026-03-01',
                'quantity_used' => 0.5,
                'unit' => 'kg',
                'unit_cost' => 45.00,
                'total_cost' => 22.50,
                'supplier' => 'NDT Supply Company Ltd',
                'assigned_services' => json_encode(['mpi_service']),
                'condition' => 'new',
                'notes' => 'Fluorescent magnetic particles for wet method MPI'
            ],
            [
                'consumable_id' => 'C002',
                'consumable_name' => 'Penetrant Testing Kit',
                'consumable_type' => 'NDT Consumable',
                'brand_manufacturer' => 'Sherwin',
                'product_code' => 'SHW-PT-KIT-A',
                'batch_lot_number' => 'SHW2025-PT-007',
                'expiry_date' => '2025-11-30',
                'quantity_used' => 2.0,
                'unit' => 'sets',
                'unit_cost' => 28.50,
                'total_cost' => 57.00,
                'supplier' => 'Industrial NDT Supplies',
                'assigned_services' => json_encode(['visual_examination']),
                'condition' => 'good',
                'notes' => 'Complete PT kit including cleaner, penetrant, and developer'
            ]
        ];
        
        foreach ($consumableAssignments as $assignment) {
            ConsumableAssignment::create(array_merge($assignment, [
                'inspection_id' => $inspection->id
            ]));
        }
    }
    
    private function addInspectionResults($inspection)
    {
        // Get the inspection services first
        $services = InspectionService::where('inspection_id', $inspection->id)->get();
        
        $results = [
            [
                'inspection_service_id' => $services->where('service_type', 'lifting_examination')->first()->id,
                'test_location' => 'Main boom structure - base connection',
                'test_method' => 'Visual examination',
                'test_parameters' => json_encode(['visual_inspection' => 'direct_visual', 'access' => 'full']),
                'measurements' => json_encode(['condition' => 'good', 'defects' => 'none']),
                'result' => 'pass',
                'observations' => 'No visible defects, paint in good condition',
                'defects_noted' => null,
                'acceptance_criteria' => 'No cracks, corrosion, or deformation',
                'inspector_name' => 'James Henderson',
                'test_datetime' => now(),
                'comments' => 'All bolts tight, no corrosion observed'
            ],
            [
                'inspection_service_id' => $services->where('service_type', 'load_test')->first()->id,
                'test_location' => 'Complete crane assembly',
                'test_method' => 'Proof load test',
                'test_parameters' => json_encode(['test_load' => '62.5 tonnes', 'duration' => '10 minutes']),
                'measurements' => json_encode(['deflection' => '0mm', 'permanent_set' => '0mm']),
                'result' => 'pass',
                'observations' => 'No deformation observed during or after test',
                'defects_noted' => null,
                'acceptance_criteria' => 'No permanent deformation, return to original position',
                'inspector_name' => 'James Henderson',
                'test_datetime' => now(),
                'comments' => 'Load held for 10 minutes, returned to original position'
            ],
            [
                'inspection_service_id' => $services->where('service_type', 'mpi_service')->first()->id,
                'test_location' => 'Critical welded joints',
                'test_method' => 'Magnetic particle inspection',
                'test_parameters' => json_encode(['method' => 'wet_fluorescent', 'field_strength' => '2500_gauss']),
                'measurements' => json_encode(['indications' => 'none', 'coverage' => '100%']),
                'result' => 'pass',
                'observations' => 'All welds examined with full coverage',
                'defects_noted' => null,
                'acceptance_criteria' => 'No relevant indications per AWS D1.1',
                'inspector_name' => 'James Henderson',
                'test_datetime' => now(),
                'comments' => 'All critical welds passed MPI examination'
            ]
        ];
        
        foreach ($results as $result) {
            InspectionResult::create(array_merge($result, [
                'inspection_id' => $inspection->id
            ]));
        }
    }
    
    private function addSampleImages($inspection)
    {
        // Create the images directory if it doesn't exist
        $imagesDir = public_path('images/inspections');
        if (!file_exists($imagesDir)) {
            mkdir($imagesDir, 0755, true);
        }
        
        // Create placeholder images (you can replace these with actual images)
        $imageFiles = [
            'crane-overview-001.jpg',
            'boom-assembly-002.jpg', 
            'load-test-003.jpg',
            'mpi-results-004.jpg',
            'defect-detail-005.jpg'
        ];
        
        foreach ($imageFiles as $filename) {
            $imagePath = $imagesDir . '/' . $filename;
            if (!file_exists($imagePath)) {
                // Create a simple placeholder image (1x1 pixel PNG)
                $placeholderImage = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
                file_put_contents($imagePath, $placeholderImage);
            }
        }
        
        echo "Sample images created in: $imagesDir\n";
    }
}
