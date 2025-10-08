<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadTest extends Model
{
    protected $fillable = [
        'inspection_id',
        'duration_held',
        'two_points_diagonal',
        'four_points',
        'deflection',
        'deformation',
        'distance_from_ground',
        'result',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
