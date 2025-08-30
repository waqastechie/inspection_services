<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'description',
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'additional_data'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'additional_data' => 'array'
    ];

    // Action constants
    const ACTION_CREATED = 'created';
    const ACTION_UPDATED = 'updated';
    const ACTION_DELETED = 'deleted';
    const ACTION_VIEWED = 'viewed';
    const ACTION_EXPORTED = 'exported';
    const ACTION_IMPORTED = 'imported';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';

    /**
     * Get the user who performed this action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the model that was acted upon
     */
    public function model()
    {
        if ($this->model_type && class_exists($this->model_type) && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Log an activity
     */
    public static function log(string $action, string $description, $model = null, array $oldValues = [], array $newValues = [], array $additionalData = []): self
    {
        $log = new self();
        $log->action = $action;
        $log->description = $description;
        $log->old_values = $oldValues;
        $log->new_values = $newValues;
        $log->additional_data = $additionalData;

        // Model information
        if ($model) {
            $log->model_type = get_class($model);
            $log->model_id = $model->getKey();
        }

        // Request information
        if (request()) {
            $log->ip_address = request()->ip();
            $log->user_agent = request()->userAgent();
            $log->url = request()->fullUrl();
        }

        // User information
        if (auth()->check()) {
            $log->user_id = auth()->id();
        }

        $log->save();

        return $log;
    }

    /**
     * Get action color for display
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            self::ACTION_CREATED => 'success',
            self::ACTION_UPDATED => 'warning',
            self::ACTION_DELETED => 'danger',
            self::ACTION_VIEWED => 'info',
            self::ACTION_EXPORTED => 'primary',
            self::ACTION_IMPORTED => 'secondary',
            self::ACTION_LOGIN => 'success',
            self::ACTION_LOGOUT => 'secondary',
            default => 'info'
        };
    }

    /**
     * Get action icon for display
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            self::ACTION_CREATED => 'fa-plus',
            self::ACTION_UPDATED => 'fa-edit',
            self::ACTION_DELETED => 'fa-trash',
            self::ACTION_VIEWED => 'fa-eye',
            self::ACTION_EXPORTED => 'fa-download',
            self::ACTION_IMPORTED => 'fa-upload',
            self::ACTION_LOGIN => 'fa-sign-in-alt',
            self::ACTION_LOGOUT => 'fa-sign-out-alt',
            default => 'fa-info'
        };
    }
}
