<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        // Drop Test fields
        'drop_test_load',
        'drop_type',
        'drop_distance',
        'drop_suspended',
        'drop_impact_speed',
        'drop_result',
        'drop_notes',
        // Tilt Test fields
        'tilt_test_load',
        'loaded_tilt',
        'empty_tilt',
        'tilt_results',
        'tilt_stability',
        'tilt_direction',
        'tilt_duration',
        'tilt_notes',
        // Lowering Test fields
        'lowering_test_load',
        'lowering_impact_speed',
        'lowering_result',
        'lowering_method',
        'lowering_distance',
        'lowering_duration',
        'lowering_cycles',
        'brake_efficiency',
        'control_response',
        'lowering_notes',
        // Other Test fields
        'other_test_inspector',
        'other_test_method',
        'other_test_equipment',
        'other_test_conditions',
        'other_test_results',
        'other_test_comments',
        'thorough_examination_comments',
    ];

    /**
     * Get the inspection that owns this other test.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
