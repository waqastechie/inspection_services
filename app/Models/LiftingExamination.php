<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftingExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'inspector_id',
        'first_examination',
        'equipment_installation_details',
        'safe_to_operate',
        'equipment_defect',
        'defect_description',
        'existing_danger',
        'potential_danger',
        'defect_timeline',
        'repair_details',
        'test_details',
        // New lifting examination fields
        'lifting_examination_inspector',
        'thorough_examination_inspector',
        'thorough_examination_comments',
        'thorough_method',
        'thorough_equipment',
        'thorough_conditions',
        'thorough_results',
        // New conditional defect fields
        'defect_location',
        'defect_type',
        'defect_severity',
        'defect_extent',
        'existing_danger_details',
        'potential_danger_details',
        'defect_urgency',
        'estimated_repair_cost',
        'estimated_repair_duration',
        'specialist_required',
        'specialist_type',
        'out_of_service_required',
        'out_of_service_reason',
        'followup_retest',
        'followup_reinspection',
        'followup_monitoring',
        'followup_notes',
    ];

    protected $casts = [
        'defect_timeline' => 'date',
    ];

    /**
     * Get the inspection that owns this lifting examination.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the inspector assigned to this examination.
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
     * Check if this is a first examination.
     */
    public function isFirstExamination(): bool
    {
        return $this->first_examination === 'yes';
    }

    /**
     * Check if equipment is safe to operate.
     */
    public function isSafeToOperate(): bool
    {
        return $this->safe_to_operate === 'yes';
    }

    /**
     * Check if equipment has defects.
     */
    public function hasDefects(): bool
    {
        return $this->equipment_defect === 'yes';
    }

    /**
     * Get the overall status of the examination.
     */
    public function getOverallStatusAttribute(): string
    {
        if (!$this->isSafeToOperate() || $this->hasDefects()) {
            return 'unsatisfactory';
        }
        return 'satisfactory';
    }
}
