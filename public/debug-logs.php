<?php

use App\Models\SystemLog;
use App\Models\ActivityLog;

// Test the log dashboard data
echo "<h1>Log Dashboard Debug</h1>";

echo "<h2>Statistics</h2>";
$stats = [
    'errors' => SystemLog::where('level', 'error')->count(),
    'warnings' => SystemLog::where('level', 'warning')->count(),
    'activities' => ActivityLog::count(),
    'resolved' => SystemLog::whereNotNull('resolved_at')->count(),
];

echo "<pre>";
print_r($stats);
echo "</pre>";

echo "<h2>Recent Errors</h2>";
$recentErrors = SystemLog::where('level', 'error')->latest()->limit(5)->get();
echo "Found " . $recentErrors->count() . " recent errors:<br>";
foreach ($recentErrors as $error) {
    echo "- " . $error->type . ": " . $error->message . " (" . $error->created_at . ")<br>";
}

echo "<h2>Recent Activities</h2>";
$recentActivities = ActivityLog::with('user')->latest()->limit(5)->get();
echo "Found " . $recentActivities->count() . " recent activities:<br>";
foreach ($recentActivities as $activity) {
    echo "- " . $activity->action . ": " . $activity->description . " by " . ($activity->user->name ?? 'System') . " (" . $activity->created_at . ")<br>";
}

echo "<h2>Chart Data</h2>";
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

$chartData = [
    'dates' => $dates->toArray(),
    'errors' => $errors->toArray(),
    'activities' => $activities->toArray(),
];

echo "<pre>";
print_r($chartData);
echo "</pre>";

echo "<p><a href='/admin/logs'>Visit Log Dashboard</a></p>";
