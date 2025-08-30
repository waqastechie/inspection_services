<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\ClientController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    
    // Inspection routes
    Route::prefix('inspections')->name('inspections.')->group(function () {
        Route::get('/', [InspectionController::class, 'index'])->name('index');
        Route::get('/create', [InspectionController::class, 'create'])->name('create');
        Route::post('/store', [InspectionController::class, 'store'])->name('store');
        Route::post('/save-draft', [InspectionController::class, 'saveDraft'])->name('save-draft');
        Route::put('/update-draft/{id}', [InspectionController::class, 'updateDraft'])->name('update-draft');
        Route::get('/{id}', [InspectionController::class, 'show'])->name('show');
        Route::get('/{id}/pdf', [InspectionController::class, 'generatePDF'])->name('pdf');
        Route::get('/{id}/preview-pdf', [InspectionController::class, 'previewPDF'])->name('preview-pdf');
        
        // API Routes for dropdowns
        Route::get('/api/personnel', [InspectionController::class, 'getPersonnel'])->name('api.personnel');
        Route::get('/api/inspectors', [InspectionController::class, 'getInspectors'])->name('api.inspectors');
        Route::get('/api/clients', [ClientController::class, 'getClients'])->name('api.clients');
        
        // Simple test routes
        Route::get('/test-users', function() {
            try {
                $users = App\Models\User::all();
                return response()->json(['success' => true, 'count' => $users->count(), 'users' => $users]);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        });
        
        // Test route for API debugging
        Route::get('/test-api', function () {
            return view('test-api');
        });
        
        // Debug route for dropdown testing
        Route::get('/debug-dropdowns', function () {
            return view('debug-dropdowns');
        });
        
        // Test route to populate personnel data
        Route::get('/test/populate-personnel', function () {
            try {
                \App\Models\Personnel::truncate();
                
                $personnel = [
                    [
                        'first_name' => 'John',
                        'last_name' => 'Smith',
                        'position' => 'Senior Inspector',
                        'department' => 'Quality Assurance',
                        'employee_id' => 'EMP001',
                        'email' => 'john.smith@company.com',
                        'phone' => '+1-555-0101',
                        'qualifications' => 'ASNT Level III, API 510',
                        'certifications' => 'NDT Level 3 (UT, RT, MT, PT)',
                        'is_active' => true,
                    ],
                    [
                        'first_name' => 'Sarah',
                        'last_name' => 'Johnson',
                        'position' => 'Lead Inspector',
                        'department' => 'NDT Services',
                        'employee_id' => 'EMP002',
                        'email' => 'sarah.johnson@company.com',
                        'phone' => '+1-555-0102',
                        'qualifications' => 'ASNT Level II, LEEA Inspector',
                        'certifications' => 'NDT Level 2 (UT, MT, PT)',
                        'is_active' => true,
                    ],
                    [
                        'first_name' => 'Michael',
                        'last_name' => 'Chen',
                        'position' => 'Quality Technician',
                        'department' => 'Quality Control',
                        'employee_id' => 'EMP003',
                        'email' => 'michael.chen@company.com',
                        'phone' => '+1-555-0103',
                        'qualifications' => 'ASNT Level I, AWS Certified',
                        'certifications' => 'NDT Level 1 (MT, PT)',
                        'is_active' => true,
                    ]
                ];
                
                foreach ($personnel as $person) {
                    \App\Models\Personnel::create($person);
                }
                
                return response()->json(['message' => 'Personnel created successfully', 'count' => count($personnel)]);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        });
        
        // Super Admin only routes for edit/update/delete
        Route::middleware('super_admin')->group(function () {
            Route::get('/{id}/edit', [InspectionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InspectionController::class, 'update'])->name('update');
            Route::delete('/{id}', [InspectionController::class, 'destroy'])->name('destroy');
        });
    });
    
    // User Management Routes (Admin only)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    // Admin Resource Management Routes (Super Admin only)
    Route::middleware('super_admin')->prefix('admin')->name('admin.')->group(function () {
        // Client Management
        Route::resource('clients', ClientController::class);
        Route::patch('clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');
        
        // Personnel Management
        Route::resource('personnel', PersonnelController::class);
        Route::patch('personnel/{personnel}/toggle-status', [PersonnelController::class, 'toggleStatus'])->name('personnel.toggle-status');
        
        // Equipment Management
        Route::resource('equipment', EquipmentController::class);
        Route::patch('equipment/{equipment}/toggle-status', [EquipmentController::class, 'toggleStatus'])->name('equipment.toggle-status');
        
        // Consumables Management
        Route::resource('consumables', ConsumableController::class);
        Route::patch('consumables/{consumable}/toggle-status', [ConsumableController::class, 'toggleStatus'])->name('consumables.toggle-status');
        
        // Log Management (Super Admin only)
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LogController::class, 'dashboard'])->name('dashboard');
            Route::get('/system', [\App\Http\Controllers\Admin\LogController::class, 'systemLogs'])->name('system');
            Route::get('/activity', [\App\Http\Controllers\Admin\LogController::class, 'activityLogs'])->name('activity');
            Route::get('/export/system', [\App\Http\Controllers\Admin\LogController::class, 'exportSystemLogs'])->name('export.system');
            Route::get('/export/activity', [\App\Http\Controllers\Admin\LogController::class, 'exportActivityLogs'])->name('export.activity');
            Route::delete('/system/{id}', [\App\Http\Controllers\Admin\LogController::class, 'deleteSystemLog'])->name('system.delete');
            Route::delete('/activity/{id}', [\App\Http\Controllers\Admin\LogController::class, 'deleteActivityLog'])->name('activity.delete');
            Route::patch('/system/{id}/resolve', [\App\Http\Controllers\Admin\LogController::class, 'resolveSystemLog'])->name('system.resolve');
        });
    });
    
    // API Routes for dynamic loading
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('clients', [ClientController::class, 'getClients'])->name('clients');
        Route::get('clients/{id}', [ClientController::class, 'getClientData'])->name('clients.data');
        Route::get('personnel', [PersonnelController::class, 'getPersonnel'])->name('personnel');
        Route::get('equipment', [EquipmentController::class, 'getEquipment'])->name('equipment');
        Route::get('consumables', [ConsumableController::class, 'getConsumables'])->name('consumables');
    });
    
    // Temporary debug route for testing logs dashboard
    Route::get('/debug/logs', function() {
        $stats = [
            'errors' => \App\Models\SystemLog::where('level', 'error')->count(),
            'warnings' => \App\Models\SystemLog::where('level', 'warning')->count(),
            'activities' => \App\Models\ActivityLog::count(),
            'resolved' => \App\Models\SystemLog::whereNotNull('resolved_at')->count(),
        ];
        
        $recentErrors = \App\Models\SystemLog::where('level', 'error')->latest()->limit(5)->get();
        $recentActivities = \App\Models\ActivityLog::with('user')->latest()->limit(5)->get();
        
        return response()->json([
            'stats' => $stats,
            'recent_errors_count' => $recentErrors->count(),
            'recent_activities_count' => $recentActivities->count(),
            'sample_error' => $recentErrors->first(),
            'sample_activity' => $recentActivities->first()
        ]);
    });
    
    // Check current user role and log access
    Route::get('/debug/user-role', function() {
        if (!auth()->check()) {
            return response()->json(['message' => 'Not logged in']);
        }
        
        $user = auth()->user();
        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_super_admin' => $user->isSuperAdmin(),
            'can_access_logs' => $user->isSuperAdmin(),
            'log_dashboard_url' => route('admin.logs.dashboard')
        ]);
    });
});
