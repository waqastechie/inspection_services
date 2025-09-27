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
        'quantity_used' => 'decimal:3',
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
     * Get consumable type display name (retrieved from related consumable)
     */
    public function getConsumableTypeNameAttribute(): string
    {
        if ($this->consumable) {
            return $this->consumable->type;
        }
        return 'Unknown';
    }

    /**
     * Check if consumable is near expiry (retrieved from related consumable)
     */
    public function getIsExpiringAttribute(): bool
    {
        if ($this->consumable && $this->consumable->expiry_date) {
            return $this->consumable->expiry_date->diffInDays(now()) <= 30;
        }
        return false;
    }

    /**
     * Check if consumable is expired (retrieved from related consumable)
     */
    public function getIsExpiredAttribute(): bool
    {
        if ($this->consumable && $this->consumable->expiry_date) {
            return $this->consumable->expiry_date->isPast();
        }
        return false;
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
        
        // Note: total cost calculation removed since unit_cost field no longer exists
        // Cost information can be retrieved from the related consumable if needed
    }
}
