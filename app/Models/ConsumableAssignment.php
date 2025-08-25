<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumableAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'consumable_id',
        'consumable_name',
        'consumable_type',
        'brand_manufacturer',
        'product_code',
        'batch_lot_number',
        'expiry_date',
        'quantity_used',
        'unit',
        'unit_cost',
        'total_cost',
        'supplier',
        'condition',
        'assigned_services',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_used' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'assigned_services' => 'array',
    ];

    /**
     * Get the inspection that owns the consumable assignment.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the consumable associated with this assignment.
     */
    public function consumable(): BelongsTo
    {
        return $this->belongsTo(Consumable::class);
    }

    /**
     * Get consumable type display name
     */
    public function getConsumableTypeNameAttribute(): string
    {
        return match($this->consumable_type) {
            'ut_couplant' => 'UT Couplant',
            'pt_penetrant' => 'PT Penetrant',
            'pt_developer' => 'PT Developer',
            'pt_remover' => 'PT Remover',
            'mt_ink' => 'MT Ink',
            'mt_powder' => 'MT Powder',
            'contrast_paint' => 'Contrast Paint',
            'cleaning_solvent' => 'Cleaning Solvent',
            'disposable_gloves' => 'Disposable Gloves',
            'face_masks' => 'Face Masks',
            'coveralls' => 'Disposable Coveralls',
            'shoe_covers' => 'Shoe Covers',
            'safety_glasses' => 'Safety Glasses',
            'cleaning_rags' => 'Cleaning Rags',
            'paper_towels' => 'Paper Towels',
            'masking_tape' => 'Masking Tape',
            'plastic_sheeting' => 'Plastic Sheeting',
            'marking_pen' => 'Marking Pen',
            'labels_tags' => 'Labels/Tags',
            'batteries' => 'Batteries',
            default => ucfirst(str_replace('_', ' ', $this->consumable_type))
        };
    }

    /**
     * Check if consumable is near expiry (within 30 days)
     */
    public function getIsExpiringAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Check if consumable is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isPast();
    }

    /**
     * Get condition color
     */
    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'new' => 'success',
            'good' => 'success',
            'acceptable' => 'warning',
            'near_expiry' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Calculate total cost automatically
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            if ($model->unit_cost && $model->quantity_used) {
                $model->total_cost = $model->unit_cost * $model->quantity_used;
            }
        });
    }
}
