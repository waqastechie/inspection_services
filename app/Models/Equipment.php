<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'type',
        'brand_model',
        'serial_number',
        'calibration_date',
        'calibration_due',
        'calibration_certificate',
        'condition',
        'usage_hours',
        'maintenance_notes',
        'specifications',
        'is_active',
        'parent_equipment_id',
        'equipment_category',
        'swl',
        'test_load_applied',
        'reason_for_examination',
        'manufacture_date',
        'last_examination_date',
        'next_examination_date',
        'examination_status',
        'examination_notes',
    ];

    protected $casts = [
        'calibration_date' => 'date',
        'calibration_due' => 'date',
        'manufacture_date' => 'date',
        'last_examination_date' => 'date',
        'next_examination_date' => 'date',
        'usage_hours' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the condition color for badges
     */
    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'excellent' => 'success',
            'good' => 'primary',
            'fair' => 'warning',
            'needs_maintenance' => 'danger',
            'out_of_service' => 'dark',
            default => 'secondary'
        };
    }

    /**
     * Get the status color for badges
     */
    public function getStatusColorAttribute(): string
    {
        return $this->is_active ? 'success' : 'secondary';
    }

    /**
     * Get the status text
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Check if calibration is due soon (within 30 days)
     */
    public function getIsCalibrationDueAttribute(): bool
    {
        if (!$this->calibration_due) {
            return false;
        }
        
        return $this->calibration_due->diffInDays(now()) <= 30;
    }

    /**
     * Check if calibration is overdue
     */
    public function getIsCalibrationOverdueAttribute(): bool
    {
        if (!$this->calibration_due) {
            return false;
        }
        
        return $this->calibration_due->isPast();
    }

    /**
     * Scope for active equipment
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for equipment by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for equipment by condition
     */
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Get parent equipment (for items)
     */
    public function parentEquipment()
    {
        return $this->belongsTo(Equipment::class, 'parent_equipment_id');
    }

    /**
     * Get child equipment/items (for assets)
     */
    public function childEquipment()
    {
        return $this->hasMany(Equipment::class, 'parent_equipment_id');
    }

    /**
     * Get all items under this asset
     */
    public function items()
    {
        return $this->hasMany(Equipment::class, 'parent_equipment_id')->where('equipment_category', 'item');
    }

    /**
     * Scope for assets only
     */
    public function scopeAssets($query)
    {
        return $query->where('equipment_category', 'asset');
    }

    /**
     * Scope for items only
     */
    public function scopeItems($query)
    {
        return $query->where('equipment_category', 'item');
    }

    /**
     * Check if this equipment is an asset
     */
    public function getIsAssetAttribute(): bool
    {
        return $this->equipment_category === 'asset';
    }

    /**
     * Check if this equipment is an item
     */
    public function getIsItemAttribute(): bool
    {
        return $this->equipment_category === 'item';
    }

    /**
     * Get examination status color
     */
    public function getExaminationStatusColorAttribute(): string
    {
        return match($this->examination_status) {
            'Pass' => 'success',
            'Fail' => 'danger',
            'C' => 'primary', // Current/Compliant
            'ND' => 'secondary', // Not Done
            default => 'secondary'
        };
    }
}
