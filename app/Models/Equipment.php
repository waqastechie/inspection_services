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
    ];

    protected $casts = [
        'calibration_date' => 'date',
        'calibration_due' => 'date',
        'usage_hours' => 'decimal:2',
        'specifications' => 'json',
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
}
