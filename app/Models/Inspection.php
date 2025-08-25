<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        // Basic Information
        'inspection_number',
        'client_name',
        'project_name',
        'location',
        'inspection_date',
        'weather_conditions',
        'temperature',
        'humidity',
        
        // Equipment Under Test
        'equipment_type',
        'equipment_description',
        'manufacturer',
        'model',
        'serial_number',
        'asset_tag',
        'manufacture_year',
        'capacity',
        'capacity_unit',
        
        // Certification & Standards
        'applicable_standard',
        'inspection_class',
        'certification_body',
        'previous_certificate_number',
        'last_inspection_date',
        'next_inspection_due',
        
        // Test Results
        'overall_result',
        'defects_found',
        'recommendations',
        'limitations',
        
        // Inspector Information
        'lead_inspector_name',
        'lead_inspector_certification',
        'inspector_signature',
        'report_date',
        
        // Additional Notes
        'general_notes',
        'attachments',
        'inspection_images',
        'status',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'last_inspection_date' => 'date',
        'next_inspection_due' => 'date',
        'report_date' => 'date',
        'inspection_images' => 'array',
        'attachments' => 'array',
        'capacity' => 'decimal:2',
    ];

    /**
     * Get the services for the inspection.
     */
    public function services(): HasMany
    {
        return $this->hasMany(InspectionService::class);
    }

    /**
     * Get the personnel assignments for the inspection.
     */
    public function personnelAssignments(): HasMany
    {
        return $this->hasMany(PersonnelAssignment::class);
    }

    /**
     * Get the equipment assignments for the inspection.
     */
    public function equipmentAssignments(): HasMany
    {
        return $this->hasMany(EquipmentAssignment::class);
    }

    /**
     * Get the consumable assignments for the inspection.
     */
    public function consumableAssignments(): HasMany
    {
        return $this->hasMany(ConsumableAssignment::class);
    }

    /**
     * Get the inspection results for the inspection.
     */
    public function inspectionResults(): HasMany
    {
        return $this->hasMany(InspectionResult::class);
    }

    /**
     * Generate unique inspection number
     */
    public static function generateInspectionNumber(): string
    {
        $prefix = 'INS';
        $date = now()->format('Ymd');
        $lastInspection = static::whereDate('created_at', today())
            ->latest('id')
            ->first();
        
        $sequence = $lastInspection ? (int)substr($lastInspection->inspection_number, -3) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get inspection status with color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get overall result with color
     */
    public function getResultColorAttribute(): string
    {
        return match($this->overall_result) {
            'pass' => 'success',
            'fail' => 'danger',
            'conditional_pass' => 'warning',
            default => 'secondary'
        };
    }
}
