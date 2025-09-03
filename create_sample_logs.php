<?php

use App\Models\SystemLog;
use App\Models\ActivityLog;
use App\Models\User;

// Create some sample system logs
echo "Creating sample system logs...\n";

$users = User::all();
if ($users->isEmpty()) {
    echo "No users found. Please create users first.\n";
    exit;
}

$user = $users->first();

// Sample system errors
$systemLogs = [
    [
        'level' => 'error',
        'type' => 'Database Error',
        'message' => 'Connection timeout to database server',
        'context' => ['timeout' => 30, 'server' => 'localhost'],
        'stack_trace' => 'PDOException: SQLSTATE[HY000] [2002] Connection timed out at line 42',
        'user_id' => $user->id,
        'ip_address' => '192.168.1.100',
        'url' => '/inspections/create',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'level' => 'warning',
        'type' => 'Validation Warning',
        'message' => 'Invalid inspection data submitted',
        'context' => ['field' => 'client_id', 'value' => null],
        'user_id' => $user->id,
        'ip_address' => '192.168.1.101',
        'url' => '/inspections/store',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'level' => 'info',
        'type' => 'System Info',
        'message' => 'Backup completed successfully',
        'context' => ['backup_size' => '2.5GB', 'duration' => '15 minutes'],
        'ip_address' => '127.0.0.1',
        'url' => '/admin/backup',
        'user_agent' => 'Cron Job'
    ],
    [
        'level' => 'error',
        'type' => 'File System Error',
        'message' => 'Failed to upload PDF file',
        'context' => ['file_size' => '10MB', 'max_size' => '5MB'],
        'stack_trace' => 'FilesystemException: File size exceeds limit at line 156',
        'user_id' => $user->id,
        'ip_address' => '192.168.1.102',
        'url' => '/inspections/upload',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'level' => 'warning',
        'type' => 'Security Warning',
        'message' => 'Multiple failed login attempts detected',
        'context' => ['attempts' => 5, 'ip' => '192.168.1.200'],
        'ip_address' => '192.168.1.200',
        'url' => '/login',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]
];

foreach ($systemLogs as $log) {
    SystemLog::create($log);
}

// Sample activity logs
echo "Creating sample activity logs...\n";

$activityLogs = [
    [
        'user_id' => $user->id,
        'action' => 'Created Inspection',
        'description' => 'Created new inspection for Client ABC Corp',
        'model_type' => 'App\Models\Inspection',
        'model_id' => 1,
        'properties' => [
            'client' => 'ABC Corp',
            'type' => 'Pressure Vessel',
            'status' => 'draft'
        ],
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'user_id' => $user->id,
        'action' => 'Updated User',
        'description' => 'Updated user profile information',
        'model_type' => 'App\Models\User',
        'model_id' => $user->id,
        'properties' => [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ],
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'user_id' => $user->id,
        'action' => 'Deleted Equipment',
        'description' => 'Removed equipment from inventory',
        'model_type' => 'App\Models\Equipment',
        'model_id' => 5,
        'properties' => [
            'name' => 'Ultrasonic Tester UT-2000',
            'serial_number' => 'UT2000-001',
            'reason' => 'End of life'
        ],
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'user_id' => $user->id,
        'action' => 'Login',
        'description' => 'User logged into the system',
        'properties' => [
            'remember_me' => false,
            'two_factor' => false
        ],
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'user_id' => $user->id,
        'action' => 'Generated Report',
        'description' => 'Generated PDF inspection report',
        'model_type' => 'App\Models\Inspection',
        'model_id' => 1,
        'properties' => [
            'format' => 'PDF',
            'pages' => 15,
            'file_size' => '2.3MB'
        ],
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]
];

foreach ($activityLogs as $log) {
    ActivityLog::create($log);
}

echo "Sample logs created successfully!\n";
echo "System Logs: " . SystemLog::count() . "\n";
echo "Activity Logs: " . ActivityLog::count() . "\n";
echo "Visit /admin/logs to see the log dashboard.\n";
