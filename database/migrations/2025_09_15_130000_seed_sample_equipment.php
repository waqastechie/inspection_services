<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EquipmentType;
use App\Models\Equipment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed sample equipment items
        $equipmentTypes = EquipmentType::all()->keyBy('code');
        
        $sampleEquipment = [
            [
                'name' => 'High Pressure Gas Storage Rack A1',
                'type' => 'gas_rack',
                'equipment_type_id' => $equipmentTypes['GAS_RACK']->id,
                'brand_model' => 'SafetyFirst SPR-2000',
                'serial_number' => 'GSR-001-2023',
                'condition' => 'good',
                'is_active' => true,
                'equipment_category' => 'asset',
            ],
            [
                'name' => 'Lifting Wire Rope 16mm',
                'type' => 'wire_rope',
                'equipment_type_id' => $equipmentTypes['WIRE_ROPE']->id,
                'brand_model' => 'LiftMaster WR-16-300',
                'serial_number' => 'WR-16-001',
                'condition' => 'good',
                'is_active' => true,
                'equipment_category' => 'asset',
                'swl' => '10000',
            ],
            [
                'name' => 'Main Lifting Shackle 25T',
                'type' => 'bow_shackle',
                'equipment_type_id' => $equipmentTypes['BOW_SHACKLE']->id,
                'brand_model' => 'HeavyDuty BS-25T',
                'serial_number' => 'BS-25-001',
                'condition' => 'excellent',
                'is_active' => true,
                'equipment_category' => 'asset',
                'swl' => '25000',
            ],
            [
                'name' => 'Digital Crane Scale 10T',
                'type' => 'crane_scale',
                'equipment_type_id' => $equipmentTypes['CRANE_SCALE']->id,
                'brand_model' => 'PrecisionLoad CS-10000',
                'serial_number' => 'CS-001-2023',
                'condition' => 'good',
                'is_active' => true,
                'equipment_category' => 'asset',
                'calibration_date' => '2023-06-15',
                'calibration_due' => '2024-06-15',
            ],
            [
                'name' => 'Load Cell Array LC500',
                'type' => 'load_cell',
                'equipment_type_id' => $equipmentTypes['LOAD_CELL']->id,
                'brand_model' => 'ForceMax LC-500-4',
                'serial_number' => 'LC-500-001',
                'condition' => 'good',
                'is_active' => true,
                'equipment_category' => 'asset',
                'calibration_date' => '2023-08-20',
                'calibration_due' => '2024-08-20',
            ],
            [
                'name' => 'Hydraulic Pressure Gauge 0-700 bar',
                'type' => 'pressure_gauge',
                'equipment_type_id' => $equipmentTypes['PRESSURE_GAUGE']->id,
                'brand_model' => 'HydroMax PG-700-A',
                'serial_number' => 'PG-001-2023',
                'condition' => 'excellent',
                'is_active' => true,
                'equipment_category' => 'asset',
                'calibration_date' => '2023-09-10',
                'calibration_due' => '2024-09-10',
            ],
            [
                'name' => 'Torque Wrench 500-2000 Nm',
                'type' => 'torque_wrench',
                'equipment_type_id' => $equipmentTypes['TORQUE_WRENCH']->id,
                'brand_model' => 'TorquePro TW-2000-D',
                'serial_number' => 'TW-001-2023',
                'condition' => 'good',
                'is_active' => true,
                'equipment_category' => 'asset',
                'calibration_date' => '2023-07-05',
                'calibration_due' => '2024-07-05',
            ]
        ];

        foreach ($sampleEquipment as $equipment) {
            Equipment::create($equipment);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove sample equipment
        Equipment::whereIn('serial_number', [
            'GSR-001-2023',
            'WR-16-001',
            'BS-25-001',
            'CS-001-2023',
            'LC-500-001',
            'PG-001-2023',
            'TW-001-2023'
        ])->delete();
    }
};