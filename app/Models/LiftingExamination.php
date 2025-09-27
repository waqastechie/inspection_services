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
        // Remove inspection_service_id since we don't need the intermediate table
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
