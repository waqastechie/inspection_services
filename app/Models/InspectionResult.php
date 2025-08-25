<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'service_type',
        'component_area',
        'test_parameters',
        'acceptance_criteria',
        'result_status',
        'findings',
        'measurements',
        'defect_description',
        'defect_size',
        'defect_location',
        'severity_level',
        'action_required',
        'repair_recommendations',
        'next_inspection_due',
        'inspector_notes',
        'test_results_file',
        'photos_attachments',
    ];

    protected $casts = [
        'test_parameters' => 'array',
        'measurements' => 'array',
        'next_inspection_due' => 'date',
        'photos_attachments' => 'array',
    ];

    /**
     * Get the inspection that owns the result.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get result status display name
     */
    public function getResultStatusNameAttribute(): string
    {
        return match($this->result_status) {
            'pass' => 'Pass',
            'pass_with_notes' => 'Pass with Notes',
            'conditional_pass' => 'Conditional Pass',
            'fail' => 'Fail',
            'not_tested' => 'Not Tested',
            'incomplete' => 'Incomplete',
            'requires_further_testing' => 'Requires Further Testing',
            default => ucfirst(str_replace('_', ' ', $this->result_status))
        };
    }

    /**
     * Get result status color
     */
    public function getResultStatusColorAttribute(): string
    {
        return match($this->result_status) {
            'pass' => 'success',
            'pass_with_notes' => 'info',
            'conditional_pass' => 'warning',
            'fail' => 'danger',
            'not_tested' => 'secondary',
            'incomplete' => 'warning',
            'requires_further_testing' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get severity level display name
     */
    public function getSeverityLevelNameAttribute(): string
    {
        return match($this->severity_level) {
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
            'informational' => 'Informational',
            default => $this->severity_level ? ucfirst($this->severity_level) : 'Not Specified'
        };
    }

    /**
     * Get severity level color
     */
    public function getSeverityLevelColorAttribute(): string
    {
        return match($this->severity_level) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'danger',
            'informational' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Check if result requires immediate action
     */
    public function getRequiresImmediateActionAttribute(): bool
    {
        return in_array($this->result_status, ['fail', 'requires_further_testing']) ||
               in_array($this->severity_level, ['high', 'critical']);
    }

    /**
     * Get service type display name
     */
    public function getServiceTypeNameAttribute(): string
    {
        return match($this->service_type) {
            'mpi' => 'Magnetic Particle Inspection (MPI)',
            'dye_penetrant' => 'Dye Penetrant Testing (PT)',
            'ultrasonic' => 'Ultrasonic Testing (UT)',
            'radiographic' => 'Radiographic Testing (RT)',
            'visual' => 'Visual Inspection (VT)',
            'eddy_current' => 'Eddy Current Testing (ET)',
            'acoustic_emission' => 'Acoustic Emission Testing (AE)',
            'thermographic' => 'Thermographic Testing (IRT)',
            'pressure_test' => 'Pressure Testing',
            'dimensional' => 'Dimensional Inspection',
            'hardness' => 'Hardness Testing',
            'chemical_analysis' => 'Chemical Analysis',
            'metallography' => 'Metallographic Examination',
            'fatigue_analysis' => 'Fatigue Analysis',
            'stress_analysis' => 'Stress Analysis',
            default => ucfirst(str_replace('_', ' ', $this->service_type))
        };
    }

    /**
     * Scope for failed results
     */
    public function scopeFailed($query)
    {
        return $query->where('result_status', 'fail');
    }

    /**
     * Scope for results requiring action
     */
    public function scopeRequiresAction($query)
    {
        return $query->whereIn('result_status', ['fail', 'requires_further_testing'])
                    ->orWhereIn('severity_level', ['high', 'critical']);
    }

    /**
     * Scope for results by service type
     */
    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }
}
