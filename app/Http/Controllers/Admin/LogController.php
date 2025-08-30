<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogController extends Controller
{

    /**
     * Display log dashboard
     */
    public function dashboard(): View
    {
        // Get recent logs
        $recentErrors = SystemLog::where('level', 'error')
                                ->latest()
                                ->limit(5)
                                ->get();
        
        $recentActivities = ActivityLog::with('user')
                                      ->latest()
                                      ->limit(5)
                                      ->get();

        // Get statistics for today and overall
        $stats = [
            'errors' => SystemLog::where('level', 'error')->count(),
            'warnings' => SystemLog::where('level', 'warning')->count(),
            'activities' => ActivityLog::count(),
            'resolved' => SystemLog::whereNotNull('resolved_at')->count(),
        ];

        // Get chart data for last 7 days
        $dates = collect();
        $errors = collect();
        $activities = collect();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates->push($date->format('M j'));
            
            $errors->push(SystemLog::where('level', 'error')
                                  ->whereDate('created_at', $date)
                                  ->count());
                                  
            $activities->push(ActivityLog::whereDate('created_at', $date)->count());
        }

        // Error distribution
        $distribution = [
            SystemLog::where('level', 'error')->count(),
            SystemLog::where('level', 'warning')->count(),
            SystemLog::where('level', 'info')->count(),
            SystemLog::where('level', 'debug')->count(),
        ];

        $chartData = [
            'dates' => $dates->toArray(),
            'errors' => $errors->toArray(),
            'activities' => $activities->toArray(),
            'distribution' => $distribution
        ];

        return view('admin.logs.dashboard', compact('recentErrors', 'recentActivities', 'stats', 'chartData'));
    }

    /**
     * Display system logs
     */
    public function systemLogs(Request $request): View
    {
        $query = SystemLog::with('user')->latest();

        // Apply filters
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'resolved') {
                $query->whereNotNull('resolved_at');
            } else {
                $query->whereNull('resolved_at');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->appends($request->query());

        // Get filter options
        $logTypes = SystemLog::distinct()->whereNotNull('type')->pluck('type');

        return view('admin.logs.system', compact('logs', 'logTypes'));
    }

    /**
     * Display activity logs
     */
    public function activityLogs(Request $request): View
    {
        $query = ActivityLog::with('user')->latest();

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $activities = $query->paginate(20)->appends($request->query());

        // Get filter options
        $actions = ActivityLog::distinct()->whereNotNull('action')->pluck('action');
        $modelTypes = ActivityLog::distinct()->whereNotNull('model_type')->pluck('model_type');
        $users = \App\Models\User::orderBy('name')->get();

        return view('admin.logs.activity', compact('activities', 'actions', 'modelTypes', 'users'));
    }

    /**
     * Export system logs
     */
    public function exportSystemLogs()
    {
        $filename = 'system_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Level', 'Type', 'Message', 'User', 'IP Address', 'URL', 'Status', 'Created At']);

            SystemLog::with('user')->chunk(1000, function ($logs) use ($file) {
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->level,
                        $log->type,
                        $log->message,
                        $log->user ? $log->user->name : 'System',
                        $log->ip_address,
                        $log->url,
                        $log->resolved_at ? 'Resolved' : 'Unresolved',
                        $log->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export activity logs
     */
    public function exportActivityLogs()
    {
        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Action', 'Description', 'Model Type', 'Model ID', 'User', 'IP Address', 'Created At']);

            ActivityLog::with('user')->chunk(1000, function ($logs) use ($file) {
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->action,
                        $log->description,
                        $log->model_type,
                        $log->model_id,
                        $log->user ? $log->user->name : 'System',
                        $log->ip_address,
                        $log->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Resolve system log
     */
    public function resolveSystemLog(SystemLog $id)
    {
        $id->update([
            'resolved_at' => now(),
            'resolved_by_user_id' => auth()->id()
        ]);

        return back()->with('success', 'Log marked as resolved.');
    }

    /**
     * Delete system log
     */
    public function deleteSystemLog(SystemLog $id)
    {
        $id->delete();
        return back()->with('success', 'System log deleted.');
    }

    /**
     * Delete activity log
     */
    public function deleteActivityLog(ActivityLog $id)
    {
        $id->delete();
        return back()->with('success', 'Activity log deleted.');
    }
}
