<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpiInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'inspector_id',
        'contrast_paint_method',
        'ink_powder_1_method',
        'magnetic_particle_concentration',
        'current_flow',
        'ink_powder_1_carrier',
        'contact_spacing',
        'magnetic_flow',
        'ink_powder_2_method',
        'field_application_time',
        'ink_powder_2_carrier',
        'black_light_intensity_begin',
        'black_light_intensity_end',
        'test_temperature',
        'pull_test',
        'post_test_cleaning',
        'initial_demagnetisation',
        'final_demagnetisation',
        'mpi_results',
        // New MPI service fields
        'mpi_service_inspector',
        'visual_inspector',
        'visual_comments',
        'visual_method',
        'visual_lighting',
        'visual_equipment',
        'visual_conditions',
        'visual_results',
    ];

    /**
     * Get the inspection that owns this MPI inspection.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the inspector assigned to this MPI inspection.
     */
    public function inspector(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'inspector_id');
    }

    /**
     * Get the inspector's full name.
     */
    public function getInspectorNameAttribute(): string
    {
        if ($this->inspector) {
            return $this->inspector->first_name . ' ' . $this->inspector->last_name;
        }
        return 'Not assigned';
    }

    /**
     * Get display names for enum values.
     */
    public function getContrastPaintMethodNameAttribute(): string
    {
        return match($this->contrast_paint_method) {
            'spray' => 'Spray Application',
            'brush' => 'Brush Application',
            'roller' => 'Roller Application',
            'dip' => 'Dip Application',
            default => ucfirst(str_replace('_', ' ', $this->contrast_paint_method ?? ''))
        };
    }

    public function getInkPowder1MethodNameAttribute(): string
    {
        return match($this->ink_powder_1_method) {
            'wet_continuous' => 'Wet Continuous',
            'wet_residual' => 'Wet Residual',
            'dry_continuous' => 'Dry Continuous',
            'dry_residual' => 'Dry Residual',
            default => ucfirst(str_replace('_', ' ', $this->ink_powder_1_method ?? ''))
        };
    }

    public function getCurrentFlowNameAttribute(): string
    {
        return match($this->current_flow) {
            'longitudinal' => 'Longitudinal',
            'circular' => 'Circular',
            'multidirectional' => 'Multidirectional',
            default => ucfirst($this->current_flow ?? '')
        };
    }

    public function getMagneticFlowNameAttribute(): string
    {
        return match($this->magnetic_flow) {
            'ac' => 'AC (Alternating Current)',
            'dc' => 'DC (Direct Current)',
            'pulsed_dc' => 'Pulsed DC',
            'three_phase' => 'Three Phase AC',
            default => strtoupper($this->magnetic_flow ?? '')
        };
    }

    /**
     * Check if MPI results indicate defects found.
     */
    public function hasDefectsFound(): bool
    {
        if (!$this->mpi_results) {
            return false;
        }
        
        $resultsLower = strtolower($this->mpi_results);
        $defectKeywords = ['defect', 'crack', 'flaw', 'indication', 'discontinuity', 'reject'];
        
        foreach ($defectKeywords as $keyword) {
            if (strpos($resultsLower, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get the overall test result status.
     */
    public function getOverallStatusAttribute(): string
    {
        if ($this->hasDefectsFound()) {
            return 'defects_found';
        }
        
        if ($this->mpi_results) {
            return 'completed';
        }
        
        return 'pending';
    }
}
