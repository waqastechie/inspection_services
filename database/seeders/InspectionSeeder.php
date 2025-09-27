<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inspection;
use App\Models\Client;
use App\Models\User;

class InspectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding inspections...');

        // Get some clients and users for relationships
        $clients = Client::all();
        $qaUsers = User::where('role', 'qa')->get();
        $inspectors = User::where('role', 'inspector')->get();

        if ($clients->isEmpty()) {
            $this->command->warn('No clients found. Please run ClientSeeder first.');
            return;
        }

        if ($inspectors->isEmpty()) {
            $this->command->warn('No inspectors found. Please run UserSeeder first.');
            return;
        }

        $inspections = [
            // Complete Inspection - QA Approved
            [
                'inspection_number' => 'INS-2024-001',
                'client_id' => $clients->first()->id,
                'project_name' => 'Offshore Platform Crane Inspection',
                'location' => 'Saipem Trechville - Platform Alpha',
                'inspection_date' => '2024-09-15',
                'weather_conditions' => 'Clear, Light winds',
                'temperature' => '28°C',
                'humidity' => '65%',
                'equipment_type' => 'Overhead Crane',
                'equipment_description' => '25 Tonne Bridge Crane with Wire Rope Hoist',
                'manufacturer' => 'Konecranes',
                'model' => 'CXT-25',
                'serial_number' => 'KC-25-2019-001',
                'asset_tag' => 'CRANE-001',
                'manufacture_year' => 2019,
                'capacity' => 25.00,
                'capacity_unit' => 'tonnes',
                'applicable_standard' => 'LEEA 051, EN 14492-2',
                'inspection_class' => 'Thorough Examination',
                'certification_body' => 'LEEA',
                'previous_certificate_number' => 'LEEA-2023-001',
                'last_inspection_date' => '2023-09-15',
                'next_inspection_due' => '2025-09-15',
                'overall_result' => 'pass',
                'defects_found' => null,
                'recommendations' => 'Continue routine maintenance schedule',
                'limitations' => 'Inspection limited to accessible areas only',
                'lead_inspector_name' => 'John Anderson',
                'lead_inspector_certification' => 'LEEA Inspector Certificate No. 12345',
                'inspector_signature' => 'J.Anderson',
                'report_date' => '2024-09-16',
                'general_notes' => 'Crane in excellent condition. All safety systems functioning correctly.',
                'attachments' => json_encode(['load_test_certificate.pdf', 'inspection_photos.zip']),
                'status' => 'qa_approved',
                'qa_status' => 'qa_approved',
                'qa_reviewer_id' => $qaUsers->isNotEmpty() ? $qaUsers->first()->id : null,
                'qa_reviewed_at' => '2024-09-17 14:30:00',
                'qa_comments' => 'Inspection completed to standard. Documentation comprehensive.',
            ],

            // Under QA Review
            [
                'inspection_number' => 'INS-2024-002',
                'client_id' => $clients->skip(1)->first() ? $clients->skip(1)->first()->id : $clients->first()->id,
                'project_name' => 'Pressure Vessel NDT Inspection',
                'location' => 'TotalEnergies - Processing Unit B',
                'inspection_date' => '2024-09-20',
                'weather_conditions' => 'Overcast, No precipitation',
                'temperature' => '26°C',
                'humidity' => '70%',
                'equipment_type' => 'Pressure Vessel',
                'equipment_description' => 'Horizontal Storage Tank 50m³',
                'manufacturer' => 'PressureTech',
                'model' => 'HST-50',
                'serial_number' => 'PT-HST-2020-005',
                'asset_tag' => 'TANK-B-05',
                'manufacture_year' => 2020,
                'capacity' => 50.00,
                'capacity_unit' => 'm³',
                'applicable_standard' => 'ASME VIII, API 510',
                'inspection_class' => 'In-Service Inspection',
                'certification_body' => 'API',
                'previous_certificate_number' => 'API-510-2023-002',
                'last_inspection_date' => '2023-09-20',
                'next_inspection_due' => '2025-09-20',
                'overall_result' => 'conditional_pass',
                'defects_found' => 'Minor surface corrosion on exterior shell - below action threshold',
                'recommendations' => 'Monitor corrosion areas during next scheduled maintenance',
                'limitations' => 'Internal inspection not performed - vessel in service',
                'lead_inspector_name' => 'Sarah Williams',
                'lead_inspector_certification' => 'API 510 Inspector Certificate No. 67890',
                'inspector_signature' => 'S.Williams',
                'report_date' => '2024-09-21',
                'general_notes' => 'Ultrasonic thickness measurements show acceptable wall thickness.',
                'attachments' => json_encode(['ut_data.xlsx', 'vessel_photos.zip', 'thickness_report.pdf']),
                'status' => 'under_qa_review',
                'qa_status' => 'under_qa_review',
                'qa_reviewer_id' => $qaUsers->skip(1)->first() ? $qaUsers->skip(1)->first()->id : $qaUsers->first()->id,
                'qa_reviewed_at' => null,
                'qa_comments' => null,
            ],

            // Submitted for QA
            [
                'inspection_number' => 'INS-2024-003',
                'client_id' => $clients->skip(2)->first() ? $clients->skip(2)->first()->id : $clients->first()->id,
                'project_name' => 'Lifting Equipment Certification',
                'location' => 'ENI Exploration - Drilling Rig Charlie',
                'inspection_date' => '2024-09-25',
                'weather_conditions' => 'Sunny, Calm',
                'temperature' => '30°C',
                'humidity' => '60%',
                'equipment_type' => 'Wire Rope Slings',
                'equipment_description' => '4-Leg Wire Rope Sling Set',
                'manufacturer' => 'SlingTech',
                'model' => 'WRS-4L-20T',
                'serial_number' => 'ST-WRS-2023-015',
                'asset_tag' => 'SLING-015',
                'manufacture_year' => 2023,
                'capacity' => 20.00,
                'capacity_unit' => 'tonnes',
                'applicable_standard' => 'LEEA 051, BS EN 13414',
                'inspection_class' => 'Annual Thorough Examination',
                'certification_body' => 'LEEA',
                'previous_certificate_number' => 'LEEA-2023-015',
                'last_inspection_date' => '2023-09-25',
                'next_inspection_due' => '2025-09-25',
                'overall_result' => 'pass',
                'defects_found' => null,
                'recommendations' => 'No action required',
                'limitations' => 'Visual examination only - no load testing performed',
                'lead_inspector_name' => 'Michael Chen',
                'lead_inspector_certification' => 'LEEA Inspector Certificate No. 11223',
                'inspector_signature' => 'M.Chen',
                'report_date' => '2024-09-26',
                'general_notes' => 'Slings show minimal wear. All terminations secure.',
                'attachments' => json_encode(['visual_inspection_report.pdf', 'sling_photos.zip']),
                'status' => 'submitted_for_qa',
                'qa_status' => 'pending_qa',
                'qa_reviewer_id' => null,
                'qa_reviewed_at' => null,
                'qa_comments' => null,
            ],

            // Draft Inspection
            [
                'inspection_number' => 'INS-2024-004',
                'client_id' => $clients->skip(3)->first() ? $clients->skip(3)->first()->id : $clients->first()->id,
                'project_name' => 'Radiographic Testing - Welds',
                'location' => 'Petroci Holding - Pipeline Section 7',
                'inspection_date' => '2024-09-28',
                'weather_conditions' => null,
                'temperature' => null,
                'humidity' => null,
                'equipment_type' => 'Pipeline Welds',
                'equipment_description' => 'Circumferential Butt Welds 12" Pipeline',
                'manufacturer' => 'Pipeline Constructors Inc',
                'model' => null,
                'serial_number' => 'PCI-WLD-SEC7-001-020',
                'asset_tag' => null,
                'manufacture_year' => 2024,
                'capacity' => null,
                'capacity_unit' => null,
                'applicable_standard' => 'ASME B31.4, API 1104',
                'inspection_class' => 'Production Radiography',
                'certification_body' => 'ASNT',
                'previous_certificate_number' => null,
                'last_inspection_date' => null,
                'next_inspection_due' => null,
                'overall_result' => 'pass',
                'defects_found' => null,
                'recommendations' => null,
                'limitations' => null,
                'lead_inspector_name' => 'David Rodriguez',
                'lead_inspector_certification' => 'ASNT Level II RT Certificate No. 33445',
                'inspector_signature' => null,
                'report_date' => null,
                'general_notes' => 'Draft - inspection in progress',
                'attachments' => null,
                'status' => 'draft',
                'qa_status' => 'pending_qa',
                'qa_reviewer_id' => null,
                'qa_reviewed_at' => null,
                'qa_comments' => null,
            ],

            // QA Rejected - Revision Required
            [
                'inspection_number' => 'INS-2024-005',
                'client_id' => $clients->last()->id,
                'project_name' => 'Magnetic Particle Testing - Structural Welds',
                'location' => 'FOXTROT International - Platform Delta',
                'inspection_date' => '2024-09-18',
                'weather_conditions' => 'Partly cloudy, Light breeze',
                'temperature' => '27°C',
                'humidity' => '68%',
                'equipment_type' => 'Structural Welds',
                'equipment_description' => 'Fillet Welds on Support Beams',
                'manufacturer' => 'Structural Steel Works',
                'model' => null,
                'serial_number' => 'SSW-BEAM-2024-100-150',
                'asset_tag' => 'BEAM-100-150',
                'manufacture_year' => 2024,
                'capacity' => null,
                'capacity_unit' => null,
                'applicable_standard' => 'AWS D1.1, ASME Sec V',
                'inspection_class' => 'Production Testing',
                'certification_body' => 'AWS',
                'previous_certificate_number' => null,
                'last_inspection_date' => null,
                'next_inspection_due' => null,
                'overall_result' => 'fail',
                'defects_found' => 'Linear indication found in weld W-145, length 15mm',
                'recommendations' => 'Repair weld W-145 and re-test',
                'limitations' => 'Access limited by scaffolding configuration',
                'lead_inspector_name' => 'Lisa Brown',
                'lead_inspector_certification' => 'ASNT Level II MT Certificate No. 55667',
                'inspector_signature' => 'L.Brown',
                'report_date' => '2024-09-19',
                'general_notes' => 'Defect exceeds acceptance criteria per AWS D1.1',
                'attachments' => json_encode(['mt_results.pdf', 'defect_photos.zip']),
                'status' => 'qa_rejected',
                'qa_status' => 'qa_rejected',
                'qa_reviewer_id' => $qaUsers->first()->id,
                'qa_reviewed_at' => '2024-09-20 09:15:00',
                'qa_comments' => 'Report requires additional documentation of repair procedure.',
                'qa_rejection_reason' => 'Insufficient detail on recommended repair method. Please specify welding procedure and post-repair testing requirements.',
            ],

            // Completed Inspection
            [
                'inspection_number' => 'INS-2024-006',
                'client_id' => $clients->first()->id,
                'project_name' => 'Load Testing - Mobile Crane',
                'location' => 'Saipem Trechville - Yard Area 3',
                'inspection_date' => '2024-09-12',
                'weather_conditions' => 'Clear, No wind',
                'temperature' => '29°C',
                'humidity' => '55%',
                'equipment_type' => 'Mobile Crane',
                'equipment_description' => '80 Tonne All Terrain Crane',
                'manufacturer' => 'Liebherr',
                'model' => 'LTM 1080-5.1',
                'serial_number' => 'LBR-LTM-2018-042',
                'asset_tag' => 'CRANE-042',
                'manufacture_year' => 2018,
                'capacity' => 80.00,
                'capacity_unit' => 'tonnes',
                'applicable_standard' => 'LEEA 053, EN 13000',
                'inspection_class' => 'Annual Load Test',
                'certification_body' => 'LEEA',
                'previous_certificate_number' => 'LEEA-2023-042',
                'last_inspection_date' => '2023-09-12',
                'next_inspection_due' => '2025-09-12',
                'overall_result' => 'pass',
                'defects_found' => null,
                'recommendations' => 'Replace hydraulic hoses as per maintenance schedule',
                'limitations' => 'Load test performed at 100m radius only',
                'lead_inspector_name' => 'James Wilson',
                'lead_inspector_certification' => 'LEEA Load Test Engineer Certificate No. 77889',
                'inspector_signature' => 'J.Wilson',
                'report_date' => '2024-09-13',
                'general_notes' => 'Load test successful. All functions operate within specifications.',
                'attachments' => json_encode(['load_test_chart.pdf', 'crane_photos.zip', 'hydraulic_pressure_log.xlsx']),
                'status' => 'completed',
                'qa_status' => 'qa_approved',
                'qa_reviewer_id' => $qaUsers->first()->id,
                'qa_reviewed_at' => '2024-09-14 11:45:00',
                'qa_comments' => 'Load test documentation complete and satisfactory.',
            ],
        ];

        foreach ($inspections as $inspectionData) {
            Inspection::create($inspectionData);
        }

        $this->command->info('✓ Inspections seeded successfully (' . count($inspections) . ' inspections)');
        $this->command->info('  - 2 QA Approved inspections');
        $this->command->info('  - 1 Under QA Review');
        $this->command->info('  - 1 Submitted for QA');
        $this->command->info('  - 1 Draft inspection');
        $this->command->info('  - 1 QA Rejected inspection');
    }
}
