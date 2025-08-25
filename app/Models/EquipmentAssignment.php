<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'equipment_id',
        'equipment_name',
        'equipment_type',
        'make_model',
        'serial_number',
        'condition',
        'calibration_status',
        'last_calibration_date',
        'next_calibration_date',
        'calibration_certificate',
        'assigned_services',
        'notes',
    ];

    protected $casts = [
        'last_calibration_date' => 'date',
        'next_calibration_date' => 'date',
        'assigned_services' => 'array',
    ];

    /**
     * Get the inspection that owns the equipment assignment.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the equipment associated with this assignment.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Get equipment type display name
     */
    public function getEquipmentTypeNameAttribute(): string
    {
        return match($this->equipment_type) {
            'ut_flaw_detector' => 'UT Flaw Detector',
            'mt_yoke' => 'MT Yoke',
            'pt_kit' => 'PT Kit',
            'thickness_gauge' => 'Thickness Gauge',
            'hardness_tester' => 'Hardness Tester',
            'borescope' => 'Borescope',
            'crane_scale' => 'Crane Scale',
            'load_cell' => 'Load Cell',
            'wire_rope_tester' => 'Wire Rope Tester',
            'multimeter' => 'Digital Multimeter',
            'torque_wrench' => 'Torque Wrench',
            'pressure_gauge' => 'Pressure Gauge',
            default => ucfirst(str_replace('_', ' ', $this->equipment_type))
        };
    }

    /**
     * Check if calibration is near due (within 30 days)
     */
    public function getIsCalibrationDueAttribute(): bool
    {
        if (!$this->next_calibration_date) {
            return false;
        }
        
        return $this->next_calibration_date->diffInDays(now()) <= 30;
    }

    /**
     * Check if calibration is overdue
     */
    public function getIsCalibrationOverdueAttribute(): bool
    {
        if (!$this->next_calibration_date) {
            return false;
        }
        
        return $this->next_calibration_date->isPast();
    }

    /**
     * Get calibration status color
     */
    public function getCalibrationStatusColorAttribute(): string
    {
        return match($this->calibration_status) {
            'current' => 'success',
            'due_soon' => 'warning',
            'overdue' => 'danger',
            'not_required' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get condition color
     */
    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'excellent' => 'success',
            'good' => 'success',
            'fair' => 'warning',
            'poor' => 'danger',
            default => 'secondary'
        };
    }
}
