<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding equipment...');

        $equipment = [
            // NDT Equipment
            [
                'name' => 'Ultrasonic Testing Device UT-2000',
                'type' => 'ultrasonic_tester',
                'brand_model' => 'UT-Pro-2000 by NDTech',
                'serial_number' => 'UT001-2024',
                'equipment_category' => 'asset',
                'calibration_date' => '2024-01-15',
                'calibration_due' => '2025-01-15',
                'calibration_certificate' => 'CAL-UT-001-2024',
                'condition' => 'excellent',
                'usage_hours' => 1250.50,
                'swl' => null,
                'test_load_applied' => null,
                'reason_for_examination' => 'Routine NDT Testing',
                'manufacture_date' => '2023-06-15',
                'last_examination_date' => '2024-08-15',
                'next_examination_date' => '2025-02-15',
                'examination_status' => 'Pass',
                'examination_notes' => 'All systems functioning within specifications',
                'maintenance_notes' => 'Probe calibrated and software updated',
                'specifications' => json_encode([
                    'frequency_range' => '0.5-20 MHz',
                    'display' => 'Digital color LCD',
                    'connectivity' => 'USB, Ethernet',
                    'power' => 'Rechargeable Li-ion battery'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Magnetic Particle Testing Unit MT-500',
                'type' => 'magnetic_particle_tester',
                'brand_model' => 'MT-Master-500 by MagTest',
                'serial_number' => 'MT001-2023',
                'equipment_category' => 'asset',
                'calibration_date' => '2024-03-20',
                'calibration_due' => '2025-03-20',
                'calibration_certificate' => 'CAL-MT-001-2024',
                'condition' => 'good',
                'usage_hours' => 850.25,
                'manufacture_date' => '2023-01-10',
                'last_examination_date' => '2024-07-20',
                'next_examination_date' => '2025-01-20',
                'examination_status' => 'Pass',
                'examination_notes' => 'AC/DC current levels verified within tolerance',
                'maintenance_notes' => 'Yoke cleaned and UV lamp replaced',
                'specifications' => json_encode([
                    'current_type' => 'AC/DC switchable',
                    'max_current' => '6000A AC, 4500A DC',
                    'uv_light' => 'LED UV-A 365nm',
                    'weight' => '12.5 kg'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Digital Radiography System DR-X300',
                'type' => 'radiography_equipment',
                'brand_model' => 'DR-X300 by RadiTech',
                'serial_number' => 'DR001-2022',
                'equipment_category' => 'asset',
                'calibration_date' => '2024-06-01',
                'calibration_due' => '2025-06-01',
                'calibration_certificate' => 'CAL-DR-001-2024',
                'condition' => 'good',
                'usage_hours' => 2100.75,
                'manufacture_date' => '2022-03-15',
                'last_examination_date' => '2024-09-01',
                'next_examination_date' => '2025-03-01',
                'examination_status' => 'Pass',
                'examination_notes' => 'X-ray source and detector functioning normally',
                'maintenance_notes' => 'Annual safety inspection completed, lead shielding verified',
                'specifications' => json_encode([
                    'voltage_range' => '50-300 kV',
                    'current_range' => '0.1-10 mA',
                    'detector_type' => 'Flat panel digital',
                    'image_resolution' => '2048x2048 pixels'
                ]),
                'is_active' => true,
            ],

            // Load Testing Equipment
            [
                'name' => 'Overhead Crane 25T',
                'type' => 'overhead_crane',
                'brand_model' => 'CraneTech CT-25',
                'serial_number' => 'CT25-001-2021',
                'equipment_category' => 'asset',
                'swl' => '25 tonnes',
                'test_load_applied' => '31.25 tonnes (125% SWL)',
                'reason_for_examination' => 'Annual thorough examination',
                'manufacture_date' => '2021-08-15',
                'last_examination_date' => '2024-08-15',
                'next_examination_date' => '2025-08-15',
                'examination_status' => 'Pass',
                'examination_notes' => 'Load test completed successfully, all safety systems operational',
                'condition' => 'good',
                'usage_hours' => 3250.0,
                'maintenance_notes' => 'Wire ropes inspected, brake system tested',
                'specifications' => json_encode([
                    'span' => '20 meters',
                    'lift_height' => '12 meters',
                    'hoist_speed' => '2.5 m/min',
                    'travel_speed' => '20 m/min'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Chain Block 5T',
                'type' => 'chain_block',
                'brand_model' => 'LiftMaster LM-5T',
                'serial_number' => 'LM5T-002-2023',
                'equipment_category' => 'asset',
                'swl' => '5 tonnes',
                'test_load_applied' => '6.25 tonnes (125% SWL)',
                'reason_for_examination' => '6-month inspection',
                'manufacture_date' => '2023-02-20',
                'last_examination_date' => '2024-09-10',
                'next_examination_date' => '2025-03-10',
                'examination_status' => 'Pass',
                'examination_notes' => 'Chain wear within acceptable limits, load brake functioning correctly',
                'condition' => 'excellent',
                'usage_hours' => 450.0,
                'maintenance_notes' => 'Chain lubricated, load brake adjusted',
                'specifications' => json_encode([
                    'chain_grade' => 'Grade 80',
                    'chain_diameter' => '10mm',
                    'lift_height' => '3 meters',
                    'weight' => '28 kg'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Wire Rope Sling Set 10T',
                'type' => 'wire_rope_sling',
                'brand_model' => 'SlingTech ST-10T-4L',
                'serial_number' => 'ST10T-003-2024',
                'equipment_category' => 'asset',
                'swl' => '10 tonnes (4-leg configuration)',
                'test_load_applied' => '12.5 tonnes (125% SWL)',
                'reason_for_examination' => 'Initial certification',
                'manufacture_date' => '2024-01-05',
                'last_examination_date' => '2024-01-15',
                'next_examination_date' => '2025-01-15',
                'examination_status' => 'Pass',
                'examination_notes' => 'New sling set, all terminations secure, wire condition excellent',
                'condition' => 'excellent',
                'usage_hours' => 125.0,
                'maintenance_notes' => 'Visual inspection completed, no defects found',
                'specifications' => json_encode([
                    'rope_diameter' => '20mm',
                    'rope_construction' => '6x36 IWRC',
                    'leg_length' => '3 meters',
                    'angle_factor' => '60 degrees max'
                ]),
                'is_active' => true,
            ],

            // Measuring Equipment
            [
                'name' => 'Digital Caliper 300mm',
                'type' => 'measuring_device',
                'brand_model' => 'PrecisionTool PT-300D',
                'serial_number' => 'PT300-004-2024',
                'equipment_category' => 'asset',
                'calibration_date' => '2024-07-01',
                'calibration_due' => '2025-07-01',
                'calibration_certificate' => 'CAL-PT-004-2024',
                'condition' => 'excellent',
                'usage_hours' => 200.0,
                'manufacture_date' => '2024-05-10',
                'last_examination_date' => '2024-07-01',
                'next_examination_date' => '2025-07-01',
                'examination_status' => 'Pass',
                'examination_notes' => 'Accuracy verified against master gauge blocks',
                'maintenance_notes' => 'Battery replaced, display cleaned',
                'specifications' => json_encode([
                    'measurement_range' => '0-300mm',
                    'resolution' => '0.01mm',
                    'accuracy' => '±0.02mm',
                    'display_type' => 'Digital LCD'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Load Testing Frame 50T',
                'type' => 'load_testing_frame',
                'brand_model' => 'LoadMaster LM-50T',
                'serial_number' => 'LM50-005-2020',
                'equipment_category' => 'asset',
                'swl' => '50 tonnes',
                'test_load_applied' => '55 tonnes (110% capacity)',
                'reason_for_examination' => 'Bi-annual recertification',
                'manufacture_date' => '2020-11-20',
                'last_examination_date' => '2024-05-15',
                'next_examination_date' => '2026-05-15',
                'examination_status' => 'Pass',
                'examination_notes' => 'Frame structural integrity verified, load cells calibrated',
                'condition' => 'good',
                'usage_hours' => 1850.0,
                'maintenance_notes' => 'Hydraulic system serviced, safety valves tested',
                'specifications' => json_encode([
                    'frame_height' => '6 meters',
                    'frame_width' => '4 meters',
                    'hydraulic_pressure' => '700 bar',
                    'load_accuracy' => '±0.5%'
                ]),
                'is_active' => true,
            ],

            // Specialized Equipment
            [
                'name' => 'Penetrant Testing Kit Complete',
                'type' => 'penetrant_testing_kit',
                'brand_model' => 'PenTest PT-Complete',
                'serial_number' => 'PTC-006-2023',
                'equipment_category' => 'asset',
                'calibration_date' => '2024-04-10',
                'calibration_due' => '2025-04-10',
                'calibration_certificate' => 'CAL-PTC-006-2024',
                'condition' => 'good',
                'usage_hours' => 680.0,
                'manufacture_date' => '2023-08-15',
                'last_examination_date' => '2024-04-10',
                'next_examination_date' => '2025-04-10',
                'examination_status' => 'Pass',
                'examination_notes' => 'UV light intensity verified, penetrant performance tested',
                'maintenance_notes' => 'UV bulbs replaced, developer spray nozzles cleaned',
                'specifications' => json_encode([
                    'uv_intensity' => '1000 μW/cm² at 15 inches',
                    'penetrant_type' => 'Visible and fluorescent',
                    'developer_type' => 'Solvent removable',
                    'kit_contents' => 'Cleaner, penetrant, developer, UV light'
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($equipment as $equipmentData) {
            Equipment::create($equipmentData);
        }

        $this->command->info('✓ Equipment seeded successfully (' . count($equipment) . ' items)');
    }
}
