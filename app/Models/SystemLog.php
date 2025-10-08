<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
    protected $fillable = [
        'level',
        'type',
        'message',
        'context',
        'file',
        'line',
        'stack_trace',
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'request_data',
        'resolved',
        'resolution_notes',
        'resolved_at',
        'resolved_by'
    ];

    protected $casts = [
        'context' => 'array',
        'request_data' => 'array',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime'
    ];

    // Log levels constants
    const LEVEL_ERROR = 'error';
    const LEVEL_WARNING = 'warning';
    const LEVEL_INFO = 'info';
    const LEVEL_DEBUG = 'debug';

    // Log types constants
    const TYPE_SYSTEM = 'system';
    const TYPE_USER_ACTION = 'user_action';
    const TYPE_API = 'api';
    const TYPE_DATABASE = 'database';
    const TYPE_VALIDATION = 'validation';
    const TYPE_AUTHENTICATION = 'authentication';
    const TYPE_AUTHORIZATION = 'authorization';

    /**
     * Get the user who caused this log entry
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who resolved this log entry
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Static method to log errors
     */
    public static function logError(string $message, array $context = [], ?\Throwable $exception = null): self
    {
        return self::createLog(self::LEVEL_ERROR, self::TYPE_SYSTEM, $message, $context, $exception);
    }

    /**
     * Static method to log warnings
     */
    public static function logWarning(string $message, array $context = []): self
    {
        return self::createLog(self::LEVEL_WARNING, self::TYPE_SYSTEM, $message, $context);
    }

    /**
     * Static method to log info
     */
    public static function logInfo(string $message, array $context = []): self
    {
        return self::createLog(self::LEVEL_INFO, self::TYPE_SYSTEM, $message, $context);
    }

    /**
     * Static method to log user actions
     */
    public static function logUserAction(string $message, array $context = []): self
    {
        return self::createLog(self::LEVEL_INFO, self::TYPE_USER_ACTION, $message, $context);
    }

    /**
     * Create a log entry
     */
    private static function createLog(string $level, string $type, string $message, array $context = [], ?\Throwable $exception = null): self
    {
        $log = new self();
        $log->level = $level;
        $log->type = $type;
        $log->message = $message;
        $log->context = $context;

        // Add request information if available
        if (request()) {
            $log->ip_address = request()->ip();
            $log->user_agent = request()->userAgent();
            $log->url = request()->fullUrl();
            $log->method = request()->method();
            
            // Store request data for certain log types (be careful with sensitive data)
            if (in_array($type, [self::TYPE_USER_ACTION, self::TYPE_API])) {
                $requestData = request()->except(['password', 'password_confirmation', '_token']);
                $log->request_data = $requestData;
            }
        }

        // Add user information if available
        if (auth()->check()) {
            $log->user_id = auth()->id();
        }

        // Add exception information if provided
        if ($exception) {
            $log->file = $exception->getFile();
            $log->line = $exception->getLine();
            $log->stack_trace = $exception->getTraceAsString();
        }

        $log->save();

        return $log;
    }

    /**
     * Mark this log as resolved
     */
    public function markAsResolved(string $notes = ''): bool
    {
        $this->resolved = true;
        $this->resolution_notes = $notes;
        $this->resolved_at = now();
        if (auth()->check()) {
            $this->resolved_by = auth()->id();
        }

        return $this->save();
    }

    /**
     * Get color class for log level
     */
    public function getLevelColorAttribute(): string
    {
        return match($this->level) {
            self::LEVEL_ERROR => 'danger',
            self::LEVEL_WARNING => 'warning',
            self::LEVEL_INFO => 'info',
            self::LEVEL_DEBUG => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get icon for log level
     */
    public function getLevelIconAttribute(): string
    {
        return match($this->level) {
            self::LEVEL_ERROR => 'fa-exclamation-triangle',
            self::LEVEL_WARNING => 'fa-exclamation-circle',
            self::LEVEL_INFO => 'fa-info-circle',
            self::LEVEL_DEBUG => 'fa-bug',
            default => 'fa-question-circle'
        };
    }
}
