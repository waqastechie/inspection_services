<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'personnel_id',
        'personnel_name',
        'role',
        'certification_level',
        'certification_number',
        'certification_expiry',
        'assigned_services',
        'notes',
    ];

    protected $casts = [
        'certification_expiry' => 'date',
        'assigned_services' => 'array',
    ];

    /**
     * Get the inspection that owns the personnel assignment.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the personnel associated with this assignment.
     */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    /**
     * Get role display name
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'lead_inspector' => 'Lead Inspector',
            'assistant_inspector' => 'Assistant Inspector',
            'witness' => 'Witness',
            'client_representative' => 'Client Representative',
            'safety_officer' => 'Safety Officer',
            'quality_controller' => 'Quality Controller',
            'technician' => 'Technician',
            'trainee' => 'Trainee',
            default => ucfirst(str_replace('_', ' ', $this->role))
        };
    }

    /**
     * Check if certification is near expiry (within 30 days)
     */
    public function getIsExpiringAttribute(): bool
    {
        if (!$this->certification_expiry) {
            return false;
        }
        
        return $this->certification_expiry->diffInDays(now()) <= 30;
    }

    /**
     * Check if certification is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->certification_expiry) {
            return false;
        }
        
        return $this->certification_expiry->isPast();
    }

    /**
     * Get certification status color
     */
    public function getCertificationStatusColorAttribute(): string
    {
        if ($this->is_expired) {
            return 'danger';
        }
        
        if ($this->is_expiring) {
            return 'warning';
        }
        
        return 'success';
    }
}
