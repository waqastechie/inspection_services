<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\EquipmentType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed initial equipment types based on the inspection requirements
        $equipmentTypes = [
            [
                'name' => 'Gas Rack',
                'code' => 'GAS_RACK',
                'description' => 'Gas storage and distribution equipment',
                'category' => 'pressure_equipment',
                'default_services' => ['visual_inspection', 'pressure_testing'],
                'required_fields' => ['serial_number', 'capacity', 'working_pressure'],
                'requires_calibration' => false,
                'specifications' => ['working_pressure', 'test_pressure', 'capacity']
            ],
            [
                'name' => 'Wire Rope',
                'code' => 'WIRE_ROPE',
                'description' => 'Wire rope lifting equipment',
                'category' => 'lifting_equipment',
                'default_services' => ['visual_inspection', 'ndt_testing'],
                'required_fields' => ['diameter', 'swl', 'construction'],
                'requires_calibration' => false,
                'specifications' => ['diameter', 'construction', 'swl', 'length']
            ],
            [
                'name' => 'Bow Shackle',
                'code' => 'BOW_SHACKLE',
                'description' => 'Bow shackle lifting accessory',
                'category' => 'lifting_equipment',
                'default_services' => ['visual_inspection', 'load_testing'],
                'required_fields' => ['swl', 'pin_diameter'],
                'requires_calibration' => false,
                'specifications' => ['swl', 'pin_diameter', 'material']
            ],
            [
                'name' => 'Crane Scale',
                'code' => 'CRANE_SCALE',
                'description' => 'Weighing equipment for crane operations',
                'category' => 'measuring_equipment',
                'default_services' => ['calibration', 'visual_inspection'],
                'required_fields' => ['capacity', 'accuracy', 'serial_number'],
                'requires_calibration' => true,
                'calibration_frequency_months' => 12,
                'specifications' => ['capacity', 'accuracy', 'resolution']
            ],
            [
                'name' => 'Load Cell',
                'code' => 'LOAD_CELL',
                'description' => 'Force measurement device',
                'category' => 'measuring_equipment',
                'default_services' => ['calibration', 'visual_inspection'],
                'required_fields' => ['capacity', 'accuracy', 'serial_number'],
                'requires_calibration' => true,
                'calibration_frequency_months' => 12,
                'specifications' => ['capacity', 'accuracy', 'excitation_voltage']
            ],
            [
                'name' => 'Pressure Gauge',
                'code' => 'PRESSURE_GAUGE',
                'description' => 'Pressure measuring instrument',
                'category' => 'measuring_equipment',
                'default_services' => ['calibration', 'visual_inspection'],
                'required_fields' => ['range', 'accuracy', 'serial_number'],
                'requires_calibration' => true,
                'calibration_frequency_months' => 12,
                'specifications' => ['range', 'accuracy', 'connection_type']
            ],
            [
                'name' => 'Torque Wrench',
                'code' => 'TORQUE_WRENCH',
                'description' => 'Torque measurement and application tool',
                'category' => 'measuring_equipment',
                'default_services' => ['calibration', 'visual_inspection'],
                'required_fields' => ['range', 'accuracy', 'serial_number'],
                'requires_calibration' => true,
                'calibration_frequency_months' => 12,
                'specifications' => ['torque_range', 'accuracy', 'drive_size']
            ]
        ];

        foreach ($equipmentTypes as $type) {
            EquipmentType::create($type);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        EquipmentType::truncate();
    }
};