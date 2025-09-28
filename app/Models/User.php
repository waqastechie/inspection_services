<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'department',
        'certification',
        'certification_expiry',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'certification_expiry' => 'date',
            'last_login' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is admin or super admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Check if user is inspector
     */
    public function isInspector(): bool
    {
        return $this->role === 'inspector';
    }

    /**
     * Check if user is QA (Quality Assurance)
     */
    public function isQA(): bool
    {
        return $this->role === 'qa';
    }

    /**
     * Check if user can approve inspections (QA or Admin)
     */
    public function canApproveInspections(): bool
    {
        return in_array($this->role, ['qa', 'admin', 'super_admin']);
    }

    /**
     * Check if user has one of the specified roles
     */
    public function hasRole($roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles);
    }

    /**
     * Get role color for badges
     */
    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'danger',
            'admin' => 'warning',
            'inspector' => 'primary',
            'qa' => 'success',
            'viewer' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get role display name
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Administrator',
            'admin' => 'Administrator',
            'inspector' => 'Inspector',
            'qa' => 'Quality Assurance',
            'viewer' => 'Viewer',
            default => 'Unknown'
        };
    }

    /**
     * Check if certification is expiring soon (within 30 days)
     */
    public function getIsCertificationExpiringAttribute(): bool
    {
        if (!$this->certification_expiry) {
            return false;
        }
        
        return $this->certification_expiry->diffInDays(now()) <= 30;
    }

    /**
     * Check if certification is expired
     */
    public function getIsCertificationExpiredAttribute(): bool
    {
        if (!$this->certification_expiry) {
            return false;
        }
        
        return $this->certification_expiry->isPast();
    }
}
