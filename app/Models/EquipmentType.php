<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'default_services',
        'required_fields',
        'specifications',
        'requires_calibration',
        'calibration_frequency_months',
        'is_active',
    ];

    protected $casts = [
        'default_services' => 'array',
        'required_fields' => 'array',
        'specifications' => 'array',
        'requires_calibration' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the equipment assignments for this equipment type.
     */
    public function equipmentAssignments(): HasMany
    {
        return $this->hasMany(EquipmentAssignment::class);
    }

    /**
     * Get active equipment types only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get equipment types by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get default calibration interval in days
     */
    public function getCalibrationIntervalDaysAttribute(): ?int
    {
        return $this->calibration_frequency_months ? $this->calibration_frequency_months * 30 : null;
    }

    /**
     * Get formatted display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ($this->description ? ' - ' . $this->description : '');
    }
}