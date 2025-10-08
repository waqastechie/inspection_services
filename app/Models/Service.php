<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    // Explicitly map to the existing 'services' table
    protected $table = 'services';

    protected $fillable = [
        'inspection_id',
        'service_type',
        'service_data',
        'notes',
    ];

    protected $casts = [
        'service_data' => 'array',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(InspectionResult::class);
    }

    /**
     * Get service type display name similar to legacy model
     */
    public function getServiceTypeNameAttribute(): string
    {
        return match($this->service_type) {
            'mpi' => 'Magnetic Particle Inspection',
            'mpi_service' => 'Magnetic Particle Inspection',
            'load_test' => 'Load Test',
            'visual_inspection' => 'Visual Inspection',
            'visual_examination' => 'Visual Examination',
            'visual' => 'Visual',
            'measurement' => 'Measurement',
            'calibration' => 'Calibration',
            'lifting_examination' => 'Lifting Examination',
            'lifting-examination' => 'Lifting Examination',
            'thorough_examination' => 'Thorough Examination',
            'thorough-examination' => 'Thorough Examination',
            'ultrasonic_testing' => 'Ultrasonic Testing',
            'penetrant_testing' => 'Penetrant Testing',
            'radiographic_testing' => 'Radiographic Testing',
            'hardness_testing' => 'Hardness Testing',
            'thickness_measurement' => 'Thickness Measurement',
            'torque_testing' => 'Torque Testing',
            default => ucfirst(str_replace(['_', '-'], ' ', $this->service_type))
        };
    }
}
