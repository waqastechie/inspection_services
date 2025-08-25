<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inspection;
use App\Models\InspectionService;
use App\Models\PersonnelAssignment;
use App\Models\EquipmentAssignment;
use App\Models\ConsumableAssignment;
use App\Models\InspectionResult;
use Carbon\Carbon;

class InspectionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data first (in reverse order due to foreign keys)
        InspectionResult::query()->delete();
        ConsumableAssignment::query()->delete();
        EquipmentAssignment::query()->delete();
        PersonnelAssignment::query()->delete();
        InspectionService::query()->delete();
        Inspection::query()->delete();
        
        // Create sample inspections with complete data
        $inspections = [
            [
                'inspection_number' => 'INS-2025-001',
                'client_name' => 'Shell Oil & Gas',
                'project_name' => 'North Sea Platform Alpha - Crane Inspection',
                'location' => 'North Sea Platform Alpha, Block 15/25',
                'inspection_date' => Carbon::now()->subDays(5),
                'weather_conditions' => 'Overcast, Light winds 5-10 knots',
                'temperature' => '12°C',
                'humidity' => '85%',
                'equipment_type' => 'Offshore Crane',
                'equipment_description' => '250T Offshore Pedestal Crane with Boom Extension',
                'manufacturer' => 'Liebherr',
                'model' => 'CBB 250-16',
                'serial_number' => 'LH-250-2018-045',
                'asset_tag' => 'SHELL-CR-001',
                'manufacture_year' => 2018,
                'capacity' => 250.00,
                'capacity_unit' => 'Tonnes',
                'applicable_standard' => 'API Spec 2C, LOLER 1998, BS 7121',
                'inspection_class' => 'Thorough Examination',
                'certification_body' => 'Lloyd\'s Register',
                'previous_certificate_number' => 'LR-2024-CE-1234',
                'last_inspection_date' => Carbon::now()->subMonths(6),
                'next_inspection_due' => Carbon::now()->addMonths(6),
                'overall_result' => 'conditional_pass',
                'defects_found' => 'Minor corrosion on boom section joints (Cat 3). Wire rope shows minor fraying on outer strands. Hydraulic oil leak detected in boom cylinder.',
                'recommendations' => '1. Address corrosion on boom joints within 3 months. 2. Replace wire rope within 2 months. 3. Repair hydraulic leak immediately before next operation.',
                'limitations' => 'Inspection carried out in high wind conditions. Full boom extension test limited to 80% capacity due to weather.',
                'lead_inspector_name' => 'John Richardson, CEng',
                'lead_inspector_certification' => 'LEEA Qualified, API 2C Inspector',
                'inspector_signature' => 'signatures/john_richardson_sig.png',
                'report_date' => Carbon::now()->subDays(3),
                'general_notes' => 'Equipment shows good overall condition considering harsh offshore environment. Regular maintenance schedule appears to be followed.',
                'attachments' => json_encode([
                    'documents/crane_maintenance_log_2024.pdf',
                    'documents/previous_inspection_report.pdf',
                    'documents/manufacturer_specifications.pdf'
                ]),
                'status' => 'completed',
            ],
            [
                'inspection_number' => 'INS-2025-002',
                'client_name' => 'BP Exploration',
                'project_name' => 'Forties Field - Lifting Equipment Annual Inspection',
                'location' => 'Forties Alpha Platform, Central North Sea',
                'inspection_date' => Carbon::now()->subDays(10),
                'weather_conditions' => 'Clear skies, Moderate winds 15-20 knots',
                'temperature' => '8°C',
                'humidity' => '78%',
                'equipment_type' => 'Chain Block Hoist',
                'equipment_description' => '5 Tonne Manual Chain Block with 3m Lift',
                'manufacturer' => 'Yale',
                'model' => 'VS III 5000',
                'serial_number' => 'YL-5T-2020-892',
                'asset_tag' => 'BP-CB-045',
                'manufacture_year' => 2020,
                'capacity' => 5.00,
                'capacity_unit' => 'Tonnes',
                'applicable_standard' => 'BS EN 13157, LOLER 1998',
                'inspection_class' => 'Thorough Examination',
                'certification_body' => 'SGS United Kingdom Ltd',
                'previous_certificate_number' => 'SGS-2024-TE-5678',
                'last_inspection_date' => Carbon::now()->subYear(),
                'next_inspection_due' => Carbon::now()->addMonths(12),
                'overall_result' => 'pass',
                'defects_found' => null,
                'recommendations' => 'Continue with current maintenance schedule. Monitor chain wear patterns.',
                'limitations' => 'Visual inspection only - equipment in active use during inspection period.',
                'lead_inspector_name' => 'Sarah Mitchell, MIMechE',
                'lead_inspector_certification' => 'LEEA Qualified, NORSOK Certified',
                'inspector_signature' => 'signatures/sarah_mitchell_sig.png',
                'report_date' => Carbon::now()->subDays(8),
                'general_notes' => 'Equipment in excellent condition. All safety features functioning correctly.',
                'attachments' => json_encode([
                    'documents/chain_block_manual.pdf',
                    'documents/maintenance_schedule.pdf'
                ]),
                'status' => 'approved',
            ],
            [
                'inspection_number' => 'INS-2025-003',
                'client_name' => 'TotalEnergies UK',
                'project_name' => 'Culzean Platform - Emergency Lifting Equipment',
                'location' => 'Culzean Platform, East Shetland Basin',
                'inspection_date' => Carbon::now()->subDays(15),
                'weather_conditions' => 'Rough seas, Strong winds 25-30 knots',
                'temperature' => '6°C',
                'humidity' => '92%',
                'equipment_type' => 'Davit Crane',
                'equipment_description' => '2T Emergency Lifeboat Davit System',
                'manufacturer' => 'Schat-Harding',
                'model' => 'MK-6 Davit',
                'serial_number' => 'SH-DV-2019-156',
                'asset_tag' => 'TOT-DV-008',
                'manufacture_year' => 2019,
                'capacity' => 2.00,
                'capacity_unit' => 'Tonnes',
                'applicable_standard' => 'SOLAS, MCA MGN 331(M), LOLER 1998',
                'inspection_class' => 'Annual Inspection',
                'certification_body' => 'Bureau Veritas',
                'previous_certificate_number' => 'BV-2024-LB-9876',
                'last_inspection_date' => Carbon::now()->subYear(),
                'next_inspection_due' => Carbon::now()->addMonths(12),
                'overall_result' => 'fail',
                'defects_found' => 'Critical: Wire rope severely corroded with multiple broken strands (>10% of total strands). Davit arm showing stress fractures. Winch brake adjustment outside acceptable limits.',
                'recommendations' => 'IMMEDIATE ACTION REQUIRED: 1. Replace wire rope immediately. 2. Conduct full structural analysis of davit arm. 3. Service and recalibrate winch brake system. Equipment must be taken out of service until repairs completed.',
                'limitations' => 'Load testing not performed due to critical defects identified during visual inspection.',
                'lead_inspector_name' => 'Michael Thompson, CEng',
                'lead_inspector_certification' => 'MCA Approved, SOLAS Inspector',
                'inspector_signature' => 'signatures/michael_thompson_sig.png',
                'report_date' => Carbon::now()->subDays(12),
                'general_notes' => 'Equipment condition unacceptable for safety-critical application. Immediate remedial action required.',
                'attachments' => json_encode([
                    'documents/solas_compliance_requirements.pdf',
                    'documents/davit_maintenance_manual.pdf',
                    'documents/emergency_procedures.pdf'
                ]),
                'status' => 'completed',
            ],
            [
                'inspection_number' => 'INS-2025-004',
                'client_name' => 'Equinor UK',
                'project_name' => 'Mariner Field - Production Equipment Lifting Assessment',
                'location' => 'Mariner A Platform, East Shetland Basin',
                'inspection_date' => Carbon::now()->subDays(20),
                'weather_conditions' => 'Partly cloudy, Light winds 8-12 knots',
                'temperature' => '10°C',
                'humidity' => '80%',
                'equipment_type' => 'Electric Wire Rope Hoist',
                'equipment_description' => '10T Electric Wire Rope Hoist with Remote Control',
                'manufacturer' => 'Demag Cranes',
                'model' => 'DC-Pro 10-250',
                'serial_number' => 'DC-10T-2021-234',
                'asset_tag' => 'EQU-EH-012',
                'manufacture_year' => 2021,
                'capacity' => 10.00,
                'capacity_unit' => 'Tonnes',
                'applicable_standard' => 'BS EN 14492-2, FEM 9.511, LOLER 1998',
                'inspection_class' => 'Detailed Examination',
                'certification_body' => 'TÜV SÜD',
                'previous_certificate_number' => 'TUV-2024-DE-4321',
                'last_inspection_date' => Carbon::now()->subMonths(8),
                'next_inspection_due' => Carbon::now()->addMonths(4),
                'overall_result' => 'pass',
                'defects_found' => 'Minor: Electrical cable outer sheath showing wear at entry point to motor housing.',
                'recommendations' => 'Replace electrical cable at next scheduled maintenance. Monitor wear progression.',
                'limitations' => 'Full load test conducted at 110% of SWL. All tests satisfactory.',
                'lead_inspector_name' => 'Emma Wilson, BEng',
                'lead_inspector_certification' => 'FEM Qualified, Electrical Safety Inspector',
                'inspector_signature' => 'signatures/emma_wilson_sig.png',
                'report_date' => Carbon::now()->subDays(18),
                'general_notes' => 'Equipment performing well. Electrical systems all within specification.',
                'attachments' => json_encode([
                    'documents/electrical_test_certificates.pdf',
                    'documents/load_test_calculations.pdf'
                ]),
                'status' => 'approved',
            ],
            [
                'inspection_number' => 'INS-2025-005',
                'client_name' => 'ConocoPhillips UK',
                'project_name' => 'Britannia Platform - Lifting Accessories Inspection',
                'location' => 'Britannia Bridge Linked Platform, Central North Sea',
                'inspection_date' => Carbon::now()->subDays(7),
                'weather_conditions' => 'Fog patches, Calm conditions',
                'temperature' => '9°C',
                'humidity' => '95%',
                'equipment_type' => 'Lifting Slings and Shackles',
                'equipment_description' => 'Various capacity webbing slings, wire rope slings, and shackles',
                'manufacturer' => 'Various (Gunnebo, Crosby, Certex)',
                'model' => 'Multiple models',
                'serial_number' => 'Various - See detailed inventory',
                'asset_tag' => 'COP-LS-SET-001',
                'manufacture_year' => null,
                'capacity' => 50.00,
                'capacity_unit' => 'Tonnes (Maximum)',
                'applicable_standard' => 'BS EN 1492 series, LOLER 1998',
                'inspection_class' => 'Six Monthly Inspection',
                'certification_body' => 'Intertek',
                'previous_certificate_number' => 'ITK-2024-SM-7890',
                'last_inspection_date' => Carbon::now()->subMonths(6),
                'next_inspection_due' => Carbon::now()->addMonths(6),
                'overall_result' => 'conditional_pass',
                'defects_found' => '15 items failed inspection: 8 webbing slings with cuts/abrasion, 4 wire rope slings with broken wires, 3 shackles with wear beyond limits.',
                'recommendations' => 'Remove failed items from service immediately. Implement better storage procedures to prevent damage.',
                'limitations' => 'Proof load testing not performed on-site - visual and dimensional checks only.',
                'lead_inspector_name' => 'David Kumar, MSc',
                'lead_inspector_certification' => 'LEEA Certified, BS EN 1492 Specialist',
                'inspector_signature' => 'signatures/david_kumar_sig.png',
                'report_date' => Carbon::now()->subDays(5),
                'general_notes' => 'General housekeeping of lifting accessories needs improvement. Training recommended for equipment handlers.',
                'attachments' => json_encode([
                    'documents/lifting_accessories_inventory.pdf',
                    'documents/storage_guidelines.pdf',
                    'documents/training_requirements.pdf'
                ]),
                'status' => 'completed',
            ]
        ];

        foreach ($inspections as $inspectionData) {
            $inspection = Inspection::create($inspectionData);

            // Create inspection services for each inspection
            $this->createInspectionServices($inspection);
            
            // Create personnel assignments
            $this->createPersonnelAssignments($inspection);
            
            // Create equipment assignments
            $this->createEquipmentAssignments($inspection);
            
            // Create consumable assignments
            $this->createConsumableAssignments($inspection);
            
            // Create inspection results
            $this->createInspectionResults($inspection);
        }

        $this->command->info('Created ' . count($inspections) . ' comprehensive inspection records with all related data.');
    }

    private function createInspectionServices($inspection)
    {
        $serviceTypes = [
            'visual_inspection' => [
                'description' => 'Visual inspection of all accessible components',
                'requirements' => 'Good lighting, access equipment if required',
                'acceptance_criteria' => 'No visible cracks, corrosion, or damage',
            ],
            'dimensional_check' => [
                'description' => 'Dimensional verification of critical components',
                'requirements' => 'Calibrated measuring equipment',
                'acceptance_criteria' => 'All dimensions within manufacturer tolerances',
            ],
            'load_test' => [
                'description' => 'Proof load test at 110% of Safe Working Load',
                'requirements' => 'Certified test weights, suitable test area',
                'acceptance_criteria' => 'No permanent deformation or failure',
            ],
            'function_test' => [
                'description' => 'Operational testing of all functions',
                'requirements' => 'Competent operator, safety barriers',
                'acceptance_criteria' => 'All functions operate smoothly and safely',
            ],
            'ndt_inspection' => [
                'description' => 'Non-destructive testing of critical welds',
                'requirements' => 'MPI/DPI equipment, certified NDT technician',
                'acceptance_criteria' => 'No relevant indications detected',
            ]
        ];

        // Randomly select 3-4 services for each inspection
        $allServiceTypes = array_keys($serviceTypes);
        $numServices = rand(3, 4);
        $selectedServiceTypes = array_slice($allServiceTypes, 0, $numServices);

        foreach ($selectedServiceTypes as $serviceType) {
            $serviceData = $serviceTypes[$serviceType];
            
            InspectionService::create([
                'inspection_id' => $inspection->id,
                'service_type' => $serviceType,
                'service_data' => json_encode($serviceData),
                'status' => 'completed',
                'notes' => 'Service completed as per inspection plan.'
            ]);
        }
    }

    private function createPersonnelAssignments($inspection)
    {
        $personnel = [
            [
                'personnel_id' => 'INSP-001',
                'personnel_name' => $inspection->lead_inspector_name,
                'role' => 'lead_inspector',
                'certification_level' => 'Level 3',
                'certification_number' => 'LEEA-2024-001',
                'certification_expiry' => Carbon::now()->addYears(3),
                'assigned_services' => json_encode(['visual_inspection', 'load_test', 'function_test']),
                'notes' => 'Overall inspection planning, execution, and reporting'
            ],
            [
                'personnel_id' => 'NDT-001',
                'personnel_name' => 'Mark Stevens',
                'role' => 'ndt_technician',
                'certification_level' => 'PCN Level 2',
                'certification_number' => 'PCN-MPI-2024-456',
                'certification_expiry' => Carbon::now()->addYears(2),
                'assigned_services' => json_encode(['ndt_inspection']),
                'notes' => 'Non-destructive testing of welds and joints'
            ],
            [
                'personnel_id' => 'SAF-001',
                'personnel_name' => 'Lisa Cooper',
                'role' => 'safety_observer',
                'certification_level' => 'NEBOSH General',
                'certification_number' => 'NEB-2024-789',
                'certification_expiry' => Carbon::now()->addYears(4),
                'assigned_services' => json_encode(['visual_inspection', 'load_test']),
                'notes' => 'Safety oversight and risk assessment'
            ]
        ];

        foreach ($personnel as $person) {
            PersonnelAssignment::create([
                'inspection_id' => $inspection->id,
                'personnel_id' => $person['personnel_id'],
                'personnel_name' => $person['personnel_name'],
                'role' => $person['role'],
                'certification_level' => $person['certification_level'],
                'certification_number' => $person['certification_number'],
                'certification_expiry' => $person['certification_expiry'],
                'assigned_services' => $person['assigned_services'],
                'notes' => $person['notes']
            ]);
        }
    }

    private function createEquipmentAssignments($inspection)
    {
        $equipment = [
            [
                'equipment_id' => 'CAL-001',
                'equipment_name' => 'Digital Caliper',
                'equipment_type' => 'measuring',
                'make_model' => 'Mitutoyo CD-15DCX',
                'serial_number' => 'MIT-2023-1234',
                'condition' => 'excellent',
                'calibration_status' => 'current',
                'last_calibration_date' => Carbon::now()->subMonths(3),
                'next_calibration_date' => Carbon::now()->addMonths(9),
                'calibration_certificate' => 'CAL-2024-5678',
                'assigned_services' => json_encode(['dimensional_check']),
                'notes' => 'Used for dimensional verification of wire rope and components'
            ],
            [
                'equipment_id' => 'MPI-001',
                'equipment_name' => 'MPI Equipment',
                'equipment_type' => 'ndt',
                'make_model' => 'Magnaflux Y-7',
                'serial_number' => 'MF-Y7-2022-9876',
                'condition' => 'good',
                'calibration_status' => 'current',
                'last_calibration_date' => Carbon::now()->subMonths(1),
                'next_calibration_date' => Carbon::now()->addMonths(11),
                'calibration_certificate' => 'NDT-2024-3456',
                'assigned_services' => json_encode(['ndt_inspection']),
                'notes' => 'Magnetic particle inspection of ferrous components'
            ],
            [
                'equipment_id' => 'LC-001',
                'equipment_name' => 'Load Cell',
                'equipment_type' => 'load_testing',
                'make_model' => 'Straightpoint SWLC300',
                'serial_number' => 'SP-LC-2023-5432',
                'condition' => 'excellent',
                'calibration_status' => 'due_soon',
                'last_calibration_date' => Carbon::now()->subMonths(9),
                'next_calibration_date' => Carbon::now()->addMonths(3),
                'calibration_certificate' => 'LC-2024-7890',
                'assigned_services' => json_encode(['load_test']),
                'notes' => 'Load monitoring during proof load testing'
            ],
            [
                'equipment_id' => 'WT-001',
                'equipment_name' => 'Test Weights',
                'equipment_type' => 'load_testing',
                'make_model' => 'Water Bags 25T Set',
                'serial_number' => 'WB-25T-2021-1111',
                'condition' => 'good',
                'calibration_status' => 'current',
                'last_calibration_date' => Carbon::now()->subMonths(6),
                'next_calibration_date' => Carbon::now()->addMonths(6),
                'calibration_certificate' => 'WT-2024-2222',
                'assigned_services' => json_encode(['load_test']),
                'notes' => 'Certified test weights for proof load testing'
            ],
            [
                'equipment_id' => 'LT-001',
                'equipment_name' => 'Portable Light Tower',
                'equipment_type' => 'support',
                'make_model' => 'LED Tower LT-5000',
                'serial_number' => 'LT-2022-8888',
                'condition' => 'good',
                'calibration_status' => 'not_required',
                'last_calibration_date' => null,
                'next_calibration_date' => null,
                'calibration_certificate' => null,
                'assigned_services' => json_encode(['visual_inspection']),
                'notes' => 'Illumination for detailed visual inspection'
            ]
        ];

        foreach ($equipment as $item) {
            EquipmentAssignment::create([
                'inspection_id' => $inspection->id,
                'equipment_id' => $item['equipment_id'],
                'equipment_name' => $item['equipment_name'],
                'equipment_type' => $item['equipment_type'],
                'make_model' => $item['make_model'],
                'serial_number' => $item['serial_number'],
                'condition' => $item['condition'],
                'calibration_status' => $item['calibration_status'],
                'last_calibration_date' => $item['last_calibration_date'],
                'next_calibration_date' => $item['next_calibration_date'],
                'calibration_certificate' => $item['calibration_certificate'],
                'assigned_services' => $item['assigned_services'],
                'notes' => $item['notes']
            ]);
        }
    }

    private function createConsumableAssignments($inspection)
    {
        $consumables = [
            [
                'consumable_id' => 'MPI-INK-001',
                'consumable_name' => 'Magnetic Particle Ink',
                'consumable_type' => 'mpi_consumable',
                'brand_manufacturer' => 'Magnaflux Corporation',
                'product_code' => 'MF-BLACK-7HF',
                'batch_lot_number' => 'MPI-2024-B789',
                'expiry_date' => Carbon::now()->addMonths(18),
                'quantity_used' => 2.000,
                'unit' => 'liters',
                'unit_cost' => 45.50,
                'total_cost' => 91.00,
                'supplier' => 'Magnaflux Corporation',
                'condition' => 'new',
                'assigned_services' => json_encode(['ndt_inspection']),
                'notes' => 'Black magnetic ink for contrast enhancement'
            ],
            [
                'consumable_id' => 'CLEAN-001',
                'consumable_name' => 'Cleaning Solvent',
                'consumable_type' => 'cleaning',
                'brand_manufacturer' => 'Industrial Chemicals Ltd',
                'product_code' => 'IC-CS-500',
                'batch_lot_number' => 'CS-2024-A456',
                'expiry_date' => Carbon::now()->addYears(2),
                'quantity_used' => 5.000,
                'unit' => 'liters',
                'unit_cost' => 12.75,
                'total_cost' => 63.75,
                'supplier' => 'Industrial Chemicals Ltd',
                'condition' => 'good',
                'assigned_services' => json_encode(['visual_inspection', 'ndt_inspection']),
                'notes' => 'Pre-cleaning of surfaces before NDT'
            ],
            [
                'consumable_id' => 'PAINT-001',
                'consumable_name' => 'White Contrast Paint',
                'consumable_type' => 'dpi_consumable',
                'brand_manufacturer' => 'Ardrox Protective Coatings',
                'product_code' => 'ARD-WCP-400',
                'batch_lot_number' => 'WCP-2024-C123',
                'expiry_date' => Carbon::now()->addMonths(24),
                'quantity_used' => 1.000,
                'unit' => 'can',
                'unit_cost' => 23.90,
                'total_cost' => 23.90,
                'supplier' => 'Ardrox Protective Coatings',
                'condition' => 'new',
                'assigned_services' => json_encode(['ndt_inspection']),
                'notes' => 'Background contrast for dye penetrant testing'
            ],
            [
                'consumable_id' => 'WIPE-001',
                'consumable_name' => 'Disposable Wipes',
                'consumable_type' => 'cleaning',
                'brand_manufacturer' => 'SafetyFirst Supplies',
                'product_code' => 'SFS-DW-100',
                'batch_lot_number' => 'DW-2024-P567',
                'expiry_date' => null,
                'quantity_used' => 2.000,
                'unit' => 'packs',
                'unit_cost' => 8.25,
                'total_cost' => 16.50,
                'supplier' => 'SafetyFirst Supplies',
                'condition' => 'good',
                'assigned_services' => json_encode(['visual_inspection', 'ndt_inspection']),
                'notes' => 'General cleaning and preparation'
            ],
            [
                'consumable_id' => 'DOC-001',
                'consumable_name' => 'Memory Cards',
                'consumable_type' => 'documentation',
                'brand_manufacturer' => 'SanDisk',
                'product_code' => 'SD-64GB-EXT',
                'batch_lot_number' => 'CF-2024-D890',
                'expiry_date' => null,
                'quantity_used' => 1.000,
                'unit' => 'piece',
                'unit_cost' => 35.00,
                'total_cost' => 35.00,
                'supplier' => 'Photography Equipment Co',
                'condition' => 'new',
                'assigned_services' => json_encode(['visual_inspection', 'load_test', 'ndt_inspection']),
                'notes' => 'Digital documentation of inspection findings'
            ]
        ];

        foreach ($consumables as $item) {
            ConsumableAssignment::create([
                'inspection_id' => $inspection->id,
                'consumable_id' => $item['consumable_id'],
                'consumable_name' => $item['consumable_name'],
                'consumable_type' => $item['consumable_type'],
                'brand_manufacturer' => $item['brand_manufacturer'],
                'product_code' => $item['product_code'],
                'batch_lot_number' => $item['batch_lot_number'],
                'expiry_date' => $item['expiry_date'],
                'quantity_used' => $item['quantity_used'],
                'unit' => $item['unit'],
                'unit_cost' => $item['unit_cost'],
                'total_cost' => $item['total_cost'],
                'supplier' => $item['supplier'],
                'condition' => $item['condition'],
                'assigned_services' => $item['assigned_services'],
                'notes' => $item['notes']
            ]);
        }
    }

    private function createInspectionResults($inspection)
    {
        // Get inspection services for this inspection
        $services = InspectionService::where('inspection_id', $inspection->id)->get();

        foreach ($services as $service) {
            // Create multiple results per service
            $resultCount = rand(2, 5);
            
            for ($i = 1; $i <= $resultCount; $i++) {
                $result = $this->generateResultData($service, $i, $inspection);
                
                InspectionResult::create([
                    'inspection_id' => $inspection->id,
                    'inspection_service_id' => $service->id,
                    'test_location' => $result['test_location'],
                    'test_method' => $result['test_method'],
                    'test_parameters' => json_encode($result['test_parameters']),
                    'measurements' => json_encode($result['measurements']),
                    'result' => $result['result'],
                    'observations' => $result['observations'],
                    'defects_noted' => $result['defects_noted'],
                    'acceptance_criteria' => $result['acceptance_criteria'],
                    'images' => json_encode($result['images']),
                    'documents' => json_encode($result['documents']),
                    'inspector_name' => $result['inspector_name'],
                    'test_datetime' => $result['test_datetime'],
                    'comments' => $result['comments']
                ]);
            }
        }
    }

    private function generateResultData($service, $testNumber, $inspection)
    {
        $serviceType = $service->service_type;
        $baseDateTime = Carbon::parse($inspection->inspection_date)->addHours(rand(8, 16));
        
        // Generate test location based on equipment type
        $locations = [
            'Main boom section - weld joint #' . $testNumber,
            'Base pivot assembly - bearing housing',
            'Wire rope attachment point #' . $testNumber,
            'Hydraulic cylinder mounting',
            'Load block assembly',
            'Hook and safety latch',
            'Boom extension joint #' . $testNumber,
            'Counterweight assembly',
            'Operator cab mounting',
            'Jib connection point #' . $testNumber
        ];

        $result = [
            'test_location' => $locations[array_rand($locations)],
            'test_method' => $this->getTestMethod($serviceType),
            'test_parameters' => $this->getTestParameters($serviceType),
            'measurements' => $this->getMeasurements($serviceType),
            'result' => $this->getTestResult($inspection->overall_result, $testNumber),
            'observations' => $this->getObservations($serviceType),
            'defects_noted' => $this->getDefects($serviceType, $inspection->overall_result),
            'acceptance_criteria' => $this->getAcceptanceCriteria($serviceType),
            'images' => $this->getImages($serviceType, $testNumber),
            'documents' => $this->getDocuments($serviceType),
            'inspector_name' => $this->getInspectorName($serviceType),
            'test_datetime' => $baseDateTime->addMinutes(rand(15, 120)),
            'comments' => $this->getComments($serviceType)
        ];

        return $result;
    }

    private function getTestMethod($serviceType)
    {
        $methods = [
            'visual_inspection' => 'Direct visual examination with magnification',
            'dimensional_check' => 'Digital caliper measurement',
            'load_test' => 'Proof load test using certified weights',
            'function_test' => 'Operational function verification',
            'ndt_inspection' => 'Magnetic Particle Inspection (MPI)'
        ];

        return $methods[$serviceType] ?? 'Standard inspection method';
    }

    private function getTestParameters($serviceType)
    {
        $parameters = [
            'visual_inspection' => [
                'lighting_level' => '500 lux minimum',
                'magnification' => '2x - 10x',
                'surface_condition' => 'Clean, dry',
                'ambient_temperature' => '10°C'
            ],
            'dimensional_check' => [
                'measurement_tolerance' => '±0.1mm',
                'calibration_status' => 'Current',
                'reference_standard' => 'Manufacturer drawings',
                'environmental_conditions' => 'Stable'
            ],
            'load_test' => [
                'test_load' => '110% SWL',
                'hold_time' => '10 minutes',
                'load_application_rate' => 'Gradual',
                'safety_factor' => '4:1'
            ],
            'function_test' => [
                'operational_speed' => 'Normal',
                'load_conditions' => 'No load to full load',
                'cycle_count' => '10 complete cycles',
                'emergency_stops' => 'All functions tested'
            ],
            'ndt_inspection' => [
                'current_type' => 'AC Yoke',
                'field_strength' => '3000-4000 A/m',
                'particle_type' => 'Fluorescent',
                'UV_light_intensity' => '1000 μW/cm²'
            ]
        ];

        return $parameters[$serviceType] ?? ['standard' => 'As per procedure'];
    }

    private function getMeasurements($serviceType)
    {
        $measurements = [
            'visual_inspection' => [
                'surface_condition' => 'Good',
                'coating_condition' => '85% intact',
                'corrosion_level' => 'Light surface rust',
                'crack_detection' => 'None visible'
            ],
            'dimensional_check' => [
                'diameter_measurement' => '24.8mm (±0.1)',
                'length_measurement' => '2500mm (±2)',
                'wear_measurement' => '0.3mm reduction',
                'out_of_round' => '0.05mm'
            ],
            'load_test' => [
                'applied_load' => '27.5 tonnes',
                'deflection' => '15mm',
                'permanent_set' => '<0.1mm',
                'load_hold_time' => '10 minutes'
            ],
            'function_test' => [
                'hoist_speed' => '0.8 m/min',
                'traverse_speed' => '5 m/min',
                'slew_speed' => '0.5 rpm',
                'brake_effectiveness' => '100%'
            ],
            'ndt_inspection' => [
                'field_strength_verified' => '3500 A/m',
                'sensitivity_check' => 'Pass (test shim)',
                'background_fluorescence' => 'Acceptable',
                'indication_assessment' => 'No relevant indications'
            ]
        ];

        return $measurements[$serviceType] ?? ['standard_measurement' => 'Within tolerance'];
    }

    private function getTestResult($overallResult, $testNumber)
    {
        // Vary results based on overall inspection result
        if ($overallResult === 'fail') {
            return $testNumber <= 2 ? 'fail' : (rand(0, 1) ? 'pass' : 'acceptable');
        } elseif ($overallResult === 'conditional_pass') {
            return $testNumber <= 1 ? 'acceptable' : 'pass';
        } else {
            return rand(0, 10) < 9 ? 'pass' : 'acceptable';
        }
    }

    private function getObservations($serviceType)
    {
        $observations = [
            'visual_inspection' => 'Surface shows normal wear patterns consistent with service environment. Some corrosion present but within acceptable limits.',
            'dimensional_check' => 'All critical dimensions measured and recorded. Minor wear evident but within manufacturer tolerances.',
            'load_test' => 'Equipment performed satisfactorily under test load. No signs of distress or permanent deformation.',
            'function_test' => 'All operational functions tested and found to operate smoothly. Control systems responsive.',
            'ndt_inspection' => 'Magnetic particle inspection completed on all accessible welds. Surface preparation adequate.'
        ];

        return $observations[$serviceType] ?? 'Standard observations recorded as per procedure.';
    }

    private function getDefects($serviceType, $overallResult)
    {
        if ($overallResult === 'pass') {
            return rand(0, 3) === 0 ? 'Minor surface scratches - no structural significance' : null;
        }

        $defects = [
            'visual_inspection' => 'Corrosion pitting depth 2-3mm on boom joint. Paint system degraded over 40% of surface area.',
            'dimensional_check' => 'Wire rope diameter reduced by 8% from nominal. Pin wear exceeding 5% limit.',
            'load_test' => 'Permanent set of 0.8mm recorded after load test. Exceeds 0.5mm limit.',
            'function_test' => 'Brake engagement delayed by 2 seconds. Slew bearing rough operation noted.',
            'ndt_inspection' => 'Linear indication 25mm length detected in heat affected zone of main weld.'
        ];

        return $defects[$serviceType] ?? null;
    }

    private function getAcceptanceCriteria($serviceType)
    {
        $criteria = [
            'visual_inspection' => 'No cracks, severe corrosion, or damage affecting structural integrity',
            'dimensional_check' => 'All dimensions within ±5% of nominal values',
            'load_test' => 'No permanent deformation >0.5mm. Load held for minimum 10 minutes',
            'function_test' => 'All functions operate smoothly without excessive noise or vibration',
            'ndt_inspection' => 'No relevant indications >3mm length or >1mm depth'
        ];

        return $criteria[$serviceType] ?? 'As per applicable standards and manufacturer specifications';
    }

    private function getImages($serviceType, $testNumber)
    {
        $images = [
            "images/inspections/{$serviceType}_test_{$testNumber}_overview.jpg",
            "images/inspections/{$serviceType}_test_{$testNumber}_detail.jpg",
            "images/inspections/{$serviceType}_test_{$testNumber}_measurement.jpg"
        ];

        // Return 1-3 images randomly
        return array_slice($images, 0, rand(1, 3));
    }

    private function getDocuments($serviceType)
    {
        $docs = [
            "documents/{$serviceType}_procedure.pdf",
            "documents/{$serviceType}_checklist.pdf"
        ];

        return rand(0, 1) ? $docs : [];
    }

    private function getInspectorName($serviceType)
    {
        $inspectors = [
            'visual_inspection' => 'John Richardson, CEng',
            'dimensional_check' => 'Sarah Mitchell, MIMechE',
            'load_test' => 'Michael Thompson, CEng',
            'function_test' => 'Emma Wilson, BEng',
            'ndt_inspection' => 'Mark Stevens, PCN Level 2'
        ];

        return $inspectors[$serviceType] ?? 'Qualified Inspector';
    }

    private function getComments($serviceType)
    {
        $comments = [
            'visual_inspection' => 'Weather conditions suitable for inspection. Good accessibility achieved.',
            'dimensional_check' => 'Measurements taken using calibrated equipment. Accuracy verified.',
            'load_test' => 'Test conducted safely with all precautions in place. Area cleared of personnel.',
            'function_test' => 'Equipment operated by qualified personnel under supervision.',
            'ndt_inspection' => 'Surface preparation adequate. Lighting conditions optimal for inspection.'
        ];

        return $comments[$serviceType] ?? 'Test completed as per procedure.';
    }
}
