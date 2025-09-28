<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InspectionComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'user_id',
        'comment_type',
        'comment',
        'status',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the inspection that owns the comment
     */
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the user who wrote the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active comments only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get formatted comment type
     */
    public function getFormattedTypeAttribute()
    {
        return match($this->comment_type) {
            'qa_review' => 'QA Review',
            'revision_response' => 'Revision Response',
            'general' => 'General Comment',
            'system' => 'System Message',
            default => 'Comment'
        };
    }

    /**
     * Get comment type color
     */
    public function getTypeColorAttribute()
    {
        return match($this->comment_type) {
            'qa_review' => 'warning',
            'revision_response' => 'info',
            'general' => 'primary',
            'system' => 'secondary',
            default => 'primary'
        };
    }

    /**
     * Get comment type icon
     */
    public function getTypeIconAttribute()
    {
        return match($this->comment_type) {
            'qa_review' => 'fas fa-shield-check',
            'revision_response' => 'fas fa-reply',
            'general' => 'fas fa-comment',
            'system' => 'fas fa-cog',
            default => 'fas fa-comment'
        };
    }
}