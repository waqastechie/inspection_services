<?php
namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log certain routes and methods
        if ($this->shouldLog($request, $response)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Determine if this request should be logged
     */
    private function shouldLog(Request $request, Response $response): bool
    {
        // Don't log if user is not authenticated
        if (!auth()->check()) {
            return false;
        }

        // Don't log certain routes
        $excludedRoutes = [
            'logs.*',
            'api.*',
            '_debugbar.*',
            'horizon.*'
        ];

        $route = $request->route();
        $routeName = $route && $route->getName() ? $route->getName() : null;

        if ($routeName) {
            foreach ($excludedRoutes as $pattern) {
                if (fnmatch($pattern, $routeName)) {
                    return false;
                }
            }
        }

        // Don't log GET requests unless they are for important views
        if ($request->isMethod('GET')) {
            $importantRoutes = [
                'inspections.show',
                'inspections.edit',
                'clients.show',
                'clients.edit',
                'personnel.show',
                'personnel.edit',
                'admin.*'
            ];

            $shouldLogGet = false;
            if ($routeName) {
                foreach ($importantRoutes as $pattern) {
                    if (fnmatch($pattern, $routeName)) {
                        $shouldLogGet = true;
                        break;
                    }
                }
            }

            if (!$shouldLogGet) {
                return false;
            }
        }

        // Don't log failed requests (4xx, 5xx) - these should be handled by error logging
        if ($response->getStatusCode() >= 400) {
            return false;
        }

        return true;
    }

    /**
     * Log the activity
     */
    private function logActivity(Request $request, Response $response): void
    {
        try {
            $route = $request->route();
            $routeName = $route && $route->getName() ? $route->getName() : 'unknown';
            $action = $this->determineAction($request, $routeName);
            $description = $this->generateDescription($request, $action, $routeName);

            ActivityLog::log(
                $action,
                $description,
                null, // model will be set in specific controllers if needed
                [],   // old values
                [],   // new values
                [
                    'route_name' => $routeName,
                    'response_status' => $response->getStatusCode(),
                    'request_method' => $request->method()
                ]
            );
        } catch (\Exception $e) {
            // Don't let logging failures break the application
            // But log the logging error itself
            \Log::error('Failed to log activity', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);
        }
    }

    /**
     * Determine the action based on the request
     */
    private function determineAction(Request $request, ?string $routeName): string
    {
        // Handle null route name
        if (!$routeName) {
            $routeName = 'unknown';
        }

        // Map route patterns to actions
        if (str_contains($routeName, '.store') || $request->isMethod('POST')) {
            return ActivityLog::ACTION_CREATED;
        }

        if (str_contains($routeName, '.update') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            return ActivityLog::ACTION_UPDATED;
        }

        if (str_contains($routeName, '.destroy') || $request->isMethod('DELETE')) {
            return ActivityLog::ACTION_DELETED;
        }

        if (str_contains($routeName, '.show') || str_contains($routeName, '.edit')) {
            return ActivityLog::ACTION_VIEWED;
        }

        if (str_contains($routeName, 'export') || str_contains($routeName, 'pdf')) {
            return ActivityLog::ACTION_EXPORTED;
        }

        if (str_contains($routeName, 'import')) {
            return ActivityLog::ACTION_IMPORTED;
        }

        return ActivityLog::ACTION_VIEWED;
    }

    /**
     * Generate a human-readable description
     */
    private function generateDescription(Request $request, string $action, ?string $routeName): string
    {
        $route = $request->route();
        $parameters = $route ? $route->parameters() : [];

        // Extract model information from route parameters
        $modelInfo = '';
        foreach ($parameters as $key => $value) {
            if (is_numeric($value) || is_string($value)) {
                $modelInfo .= " {$key}: {$value}";
            }
        }

        // Generate description based on route name
        $resourceName = $this->extractResourceName($routeName ?: 'unknown');
        
        return match($action) {
            ActivityLog::ACTION_CREATED => "Created {$resourceName}{$modelInfo}",
            ActivityLog::ACTION_UPDATED => "Updated {$resourceName}{$modelInfo}",
            ActivityLog::ACTION_DELETED => "Deleted {$resourceName}{$modelInfo}",
            ActivityLog::ACTION_VIEWED => "Viewed {$resourceName}{$modelInfo}",
            ActivityLog::ACTION_EXPORTED => "Exported {$resourceName}{$modelInfo}",
            ActivityLog::ACTION_IMPORTED => "Imported {$resourceName}{$modelInfo}",
            default => "Performed {$action} on {$resourceName}{$modelInfo}"
        };
    }

    /**
     * Extract resource name from route name
     */
    private function extractResourceName(string $routeName): string
    {
        $parts = explode('.', $routeName);
        
        if (count($parts) >= 2) {
            return ucfirst(str_replace('-', ' ', $parts[count($parts) - 2]));
        }

        return ucfirst(str_replace(['.', '-'], ' ', $routeName));
    }
}
