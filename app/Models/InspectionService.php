<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InspectionService extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'service_type',
        'service_name',
        'service_description',
        'equipment_type',
        'equipment_description',
        'test_method',
        'acceptance_criteria',
        'test_load',
        'test_load_unit',
        'safe_working_load',
        'swl_unit',
        'service_data', // Keep for additional custom fields
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
     * Get the lifting examination data if this is a lifting examination service.
     */
    public function liftingExamination(): HasOne
    {
        return $this->hasOne(LiftingExamination::class);
    }

    /**
     * Get the MPI inspection data if this is an MPI service.
     */
    public function mpiInspection(): HasOne
    {
        return $this->hasOne(MpiInspection::class);
    }

    /**
     * Get service type display name
     */
    public function getServiceTypeNameAttribute(): string
    {
        return match($this->service_type) {
            'mpi' => 'Magnetic Particle Inspection',
            'mpi_service' => 'Magnetic Particle Inspection',
            'load_test' => 'Load Test',
            'visual_inspection' => 'Visual Inspection',
            'visual_examination' => 'Visual Examination',
            'measurement' => 'Measurement',
            'calibration' => 'Calibration',
            'lifting_examination' => 'Lifting Examination',
            'lifting-examination' => 'Lifting Examination',
            'thorough_examination' => 'Thorough Examination',
            'ultrasonic_testing' => 'Ultrasonic Testing',
            'penetrant_testing' => 'Penetrant Testing',
            'radiographic_testing' => 'Radiographic Testing',
            'hardness_testing' => 'Hardness Testing',
            'thickness_measurement' => 'Thickness Measurement',
            'torque_testing' => 'Torque Testing',
            default => ucfirst(str_replace(['_', '-'], ' ', $this->service_type))
        };
    }

    /**
     * Get the display name for the service
     * Falls back to service_type_name if service_name is not set
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->service_name ?: $this->service_type_name;
    }

    /**
     * Get formatted test load with unit
     */
    public function getFormattedTestLoadAttribute(): string
    {
        if ($this->test_load) {
            return $this->test_load . ($this->test_load_unit ? ' ' . $this->test_load_unit : '');
        }
        return '';
    }

    /**
     * Get formatted safe working load with unit
     */
    public function getFormattedSwlAttribute(): string
    {
        if ($this->safe_working_load) {
            return $this->safe_working_load . ($this->swl_unit ? ' ' . $this->swl_unit : '');
        }
        return '';
    }

    /**
     * Get legacy service_data format for backward compatibility
     * Combines normalized fields back into array format
     */
    public function getLegacyServiceDataAttribute(): array
    {
        $data = $this->service_data ?: [];
        
        // Add normalized fields to the array
        if ($this->service_name) $data['name'] = $this->service_name;
        if ($this->service_description) $data['description'] = $this->service_description;
        if ($this->equipment_type) $data['equipment_type'] = $this->equipment_type;
        if ($this->equipment_description) $data['equipment_description'] = $this->equipment_description;
        if ($this->test_method) $data['test_method'] = $this->test_method;
        if ($this->acceptance_criteria) $data['acceptance_criteria'] = $this->acceptance_criteria;
        if ($this->test_load) $data['test_load'] = $this->test_load;
        if ($this->test_load_unit) $data['test_load_unit'] = $this->test_load_unit;
        if ($this->safe_working_load) $data['safe_working_load'] = $this->safe_working_load;
        if ($this->swl_unit) $data['swl_unit'] = $this->swl_unit;
        
        return $data;
    }

    /**
     * Set service data from array (for backward compatibility)
     * Extracts known fields to normalized columns
     */
    public function setServiceDataFromArray(array $data): void
    {
        // Extract known fields to normalized columns
        $this->service_name = $data['name'] ?? null;
        $this->service_description = $data['description'] ?? null;
        $this->equipment_type = $data['equipment_type'] ?? null;
        $this->equipment_description = $data['equipment_description'] ?? null;
        $this->test_method = $data['test_method'] ?? null;
        $this->acceptance_criteria = $data['acceptance_criteria'] ?? null;
        $this->test_load = isset($data['test_load']) ? (float)$data['test_load'] : null;
        $this->test_load_unit = $data['test_load_unit'] ?? null;
        $this->safe_working_load = isset($data['safe_working_load']) ? (float)$data['safe_working_load'] : null;
        $this->swl_unit = $data['swl_unit'] ?? null;
        
        // Store remaining fields in service_data JSON
        $knownFields = ['name', 'description', 'equipment_type', 'equipment_description', 'test_method', 
                       'acceptance_criteria', 'test_load', 'test_load_unit', 'safe_working_load', 'swl_unit'];
        $remainingData = array_diff_key($data, array_flip($knownFields));
        $this->service_data = $remainingData ?: null;
    }

    /**
     * Get status with color
     */
    // Status was removed from inspection services; keep color logic out
}
