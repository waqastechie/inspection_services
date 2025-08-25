<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    use HasFactory;

    protected $table = 'consumables';

    protected $fillable = [
        'name',
        'type',
        'brand_manufacturer',
        'product_code',
        'batch_lot_number',
        'expiry_date',
        'quantity_available',
        'unit',
        'unit_cost',
        'supplier',
        'condition',
        'storage_requirements',
        'safety_notes',
        'is_active',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_available' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the condition color for badges
     */
    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'new' => 'success',
            'good' => 'primary',
            'expired' => 'danger',
            'damaged' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get the status color for badges
     */
    public function getStatusColorAttribute(): string
    {
        return $this->is_active ? 'success' : 'secondary';
    }

    /**
     * Get the status text
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Check if expiry is soon (within 30 days)
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Check if expired
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isPast();
    }

    /**
     * Scope for active consumables
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for consumables by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for consumables by condition
     */
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope for low stock items
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('quantity_available', '<=', $threshold);
    }
}
