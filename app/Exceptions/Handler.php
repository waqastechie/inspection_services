<?php

namespace App\Exceptions;

use App\Models\SystemLog;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $this->logToSystemLog($e);
        });
    }

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Log the error if it's not already being reported
        if (!in_array(get_class($exception), $this->dontReport)) {
            $this->logErrorToSystemLog($exception, $request);
        }

        // For AJAX requests, return JSON response
        if ($request->expectsJson()) {
            return $this->renderJsonException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Log exceptions to our custom system log
     */
    protected function logToSystemLog(Throwable $exception): void
    {
        try {
            // Don't log validation exceptions as errors
            if ($exception instanceof ValidationException) {
                return;
            }

            $level = $this->getLogLevel($exception);
            $type = $this->getLogType($exception);

            SystemLog::createLog(
                $level,
                $type,
                $exception->getMessage(),
                [
                    'exception_class' => get_class($exception),
                    'code' => $exception->getCode(),
                ],
                $exception
            );
        } catch (\Exception $e) {
            // Fallback to regular logging if our custom logging fails
            \Log::error('Failed to log exception to SystemLog', [
                'original_exception' => $exception->getMessage(),
                'logging_error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log errors that occur during request handling
     */
    protected function logErrorToSystemLog(Throwable $exception, Request $request): void
    {
        try {
            // Skip if already logged or if it's a validation exception
            if ($exception instanceof ValidationException) {
                return;
            }

            $level = $this->getLogLevel($exception);
            $type = SystemLog::TYPE_SYSTEM;

            // Determine more specific type based on exception
            if ($exception instanceof \Illuminate\Database\QueryException) {
                $type = SystemLog::TYPE_DATABASE;
            } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                $type = SystemLog::TYPE_AUTHENTICATION;
            } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                $type = SystemLog::TYPE_AUTHORIZATION;
            } elseif ($exception instanceof HttpException) {
                $type = SystemLog::TYPE_API;
            }

            SystemLog::createLog(
                $level,
                $type,
                $exception->getMessage(),
                [
                    'exception_class' => get_class($exception),
                    'code' => $exception->getCode(),
                    'request_url' => $request->fullUrl(),
                    'request_method' => $request->method(),
                    'request_data' => $request->except(['password', 'password_confirmation', '_token']),
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                ],
                $exception
            );
        } catch (\Exception $e) {
            // Fallback logging
            \Log::error('Failed to log request exception', [
                'original_exception' => $exception->getMessage(),
                'logging_error' => $e->getMessage(),
                'url' => $request->fullUrl()
            ]);
        }
    }

    /**
     * Render JSON exception response
     */
    protected function renderJsonException(Request $request, Throwable $exception)
    {
        $status = 500;
        $message = 'An error occurred';

        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
            $message = $exception->getMessage() ?: 'An error occurred';
        } elseif ($exception instanceof ValidationException) {
            $status = 422;
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $exception->errors()
            ], $status);
        }

        return response()->json([
            'message' => $message,
            'error' => config('app.debug') ? [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ] : null
        ], $status);
    }

    /**
     * Determine log level based on exception type
     */
    protected function getLogLevel(Throwable $exception): string
    {
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            if ($statusCode >= 500) {
                return SystemLog::LEVEL_ERROR;
            } elseif ($statusCode >= 400) {
                return SystemLog::LEVEL_WARNING;
            }
        }

        if ($exception instanceof ValidationException) {
            return SystemLog::LEVEL_WARNING;
        }

        if ($exception instanceof \Illuminate\Database\QueryException) {
            return SystemLog::LEVEL_ERROR;
        }

        return SystemLog::LEVEL_ERROR;
    }

    /**
     * Determine log type based on exception
     */
    protected function getLogType(Throwable $exception): string
    {
        if ($exception instanceof \Illuminate\Database\QueryException) {
            return SystemLog::TYPE_DATABASE;
        }

        if ($exception instanceof ValidationException) {
            return SystemLog::TYPE_VALIDATION;
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return SystemLog::TYPE_AUTHENTICATION;
        }

        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return SystemLog::TYPE_AUTHORIZATION;
        }

        if ($exception instanceof HttpException) {
            return SystemLog::TYPE_API;
        }

        return SystemLog::TYPE_SYSTEM;
    }
}
