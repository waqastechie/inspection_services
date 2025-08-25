<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionService extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'service_type',
        'service_data',
        'status',
        'notes',
    ];

    protected $casts = [
        'service_data' => 'array',
    ];

    /**
     * Get the inspection that owns the service.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the results for the service.
     */
    public function results(): HasMany
    {
        return $this->hasMany(InspectionResult::class);
    }

    /**
     * Get service type display name
     */
    public function getServiceTypeNameAttribute(): string
    {
        return match($this->service_type) {
            'mpi' => 'Magnetic Particle Inspection',
            'load_test' => 'Load Test',
            'visual_inspection' => 'Visual Inspection',
            'measurement' => 'Measurement',
            'calibration' => 'Calibration',
            'lifting_examination' => 'Lifting Examination',
            'ultrasonic_testing' => 'Ultrasonic Testing',
            'penetrant_testing' => 'Penetrant Testing',
            'radiographic_testing' => 'Radiographic Testing',
            'hardness_testing' => 'Hardness Testing',
            'thickness_measurement' => 'Thickness Measurement',
            'torque_testing' => 'Torque Testing',
            default => ucfirst(str_replace('_', ' ', $this->service_type))
        };
    }

    /**
     * Get status with color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        };
    }
}
