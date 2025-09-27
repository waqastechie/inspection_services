<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\ClientController;
use App\Models\Client;
use App\Models\Inspection;

// Client Portal - Only for users with 'client' role
Route::middleware(['auth', 'verified'])->prefix('client')->name('client.')->group(function () {
    Route::get('/inspections', [\App\Http\Controllers\InspectionController::class, 'clientIndex'])
        ->middleware('can:viewClientPortal')
        ->name('inspections.index');
    Route::get('/inspections/{id}', [\App\Http\Controllers\InspectionController::class, 'clientShow'])
        ->middleware('can:viewClientPortal')
        ->name('inspections.show');
});


// Test route for database relationships
Route::get('/test-db', function () {
    try {
        $clientCount = Client::count();
        $inspectionCount = Inspection::count();
        
        $output = "Database Test Results:\n";
        $output .= "Clients: {$clientCount}\n";
        $output .= "Inspections: {$inspectionCount}\n\n";
        
        if ($clientCount > 0) {
            $client = Client::first();
            $inspectionCount = $client->inspections()->count();
            $output .= "First client '{$client->client_name}' has {$inspectionCount} inspections\n";
        }
        
        if ($inspectionCount > 0) {
            $inspection = Inspection::with('client')->first();
            $clientName = $inspection->client ? $inspection->client->client_name : 'No Client';
            $output .= "First inspection '{$inspection->inspection_number}' belongs to client: {$clientName}\n";
        }
        
        return response($output)->header('Content-Type', 'text/plain');
        
    } catch (Exception $e) {
        return response("Error: " . $e->getMessage())->header('Content-Type', 'text/plain');
    }
});

// API Routes for dynamic loading (accessibl        
        // Inspection routesthentication)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('clients', [ClientController::class, 'getClients'])->name('clients');
    Route::get('clients/{id}', [ClientController::class, 'getClientData'])->name('clients.data');
    Route::get('personnel', [PersonnelController::class, 'getPersonnel'])->name('personnel');
    Route::get('equipment', [EquipmentController::class, 'getEquipment'])->name('equipment');
    Route::get('consumables', [ConsumableController::class, 'getConsumables'])->name('consumables');
    Route::get('equipment-types', [InspectionController::class, 'getEquipmentTypes'])->name('equipment-types');
});
    // API Routes for dynamic loading (accessible for authentication)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('clients', [ClientController::class, 'getClients'])->name('clients');
        Route::get('clients/{id}', [ClientController::class, 'getClientData'])->name('clients.data');
        Route::get('personnel', [PersonnelController::class, 'getPersonnel'])->name('personnel');
        Route::get('equipment', [EquipmentController::class, 'getEquipment'])->name('equipment');
        Route::get('consumables', [ConsumableController::class, 'getConsumables'])->name('consumables');
        Route::get('equipment-types', [InspectionController::class, 'getEquipmentTypes'])->name('equipment-types');
    });

// Create test user route (temporary for debugging)
Route::get('/create-test-user', function () {
    try {
        // Create multiple test users based on the demo credentials shown in login page
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@inspectionservices.com',
                'password' => 'admin123',
                'role' => 'super_admin',
                'is_active' => true
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@company.com',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true
            ],
            [
                'name' => 'Inspector User',
                'email' => 'inspector@company.com',
                'password' => 'password',
                'role' => 'inspector',
                'is_active' => true
            ],
            [
                'name' => 'QA Manager',
                'email' => 'qa@company.com',
                'password' => 'password',
                'role' => 'qa',
                'is_active' => true
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'role' => 'super_admin',
                'is_active' => true
            ]
        ];

        $created = [];
        $existing = [];

        foreach ($users as $userData) {
            $existingUser = \App\Models\User::where('email', $userData['email'])->first();
            
            if ($existingUser) {
                $existing[] = $userData['email'];
            } else {
                $user = \App\Models\User::create($userData);
                $created[] = $userData['email'];
            }
        }

        return response()->json([
            'message' => 'Demo users setup completed!',
            'created_users' => $created,
            'existing_users' => $existing,
            'total_users' => \App\Models\User::count(),
            'credentials' => [
                'Super Admin: admin@inspectionservices.com / admin123',
                'Admin: admin@company.com / password',
                'Inspector: inspector@company.com / password',
                'QA Manager: qa@company.com / password',
                'Test: test@example.com / password'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Test form without authentication (temporary for debugging)
Route::get('/test-form', function () {
    try {
        return view('inspections.create-wizard', [
            'steps' => [
                ['title' => 'Client Information', 'route' => 'inspections.wizard.step', 'params' => ['step' => 1]],
                ['title' => 'Services', 'route' => 'inspections.wizard.step', 'params' => ['step' => 2]],
                ['title' => 'Personnel', 'route' => 'inspections.wizard.step', 'params' => ['step' => 3]],
                ['title' => 'Equipment', 'route' => 'inspections.wizard.step', 'params' => ['step' => 4]],
                ['title' => 'Results', 'route' => 'inspections.wizard.step', 'params' => ['step' => 5]],
                ['title' => 'Review', 'route' => 'inspections.wizard.step', 'params' => ['step' => 6]]
            ],
            'currentStep' => 1,
            'totalSteps' => 6,
            'inspection' => null,
            'currentSection' => 'client-information'
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Debug login credentials (temporary for debugging)
Route::get('/debug-login', function () {
    try {
        $user = \App\Models\User::where('email', 'test@example.com')->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found']);
        }
        
        $testPassword = 'password';
        $passwordCheck = \Hash::check($testPassword, $user->password);
        
        return response()->json([
            'user_exists' => true,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'password_hash' => $user->password,
            'password_check_result' => $passwordCheck,
            'test_password' => $testPassword,
            'hash_info' => password_get_info($user->password)
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Test login attempt (temporary for debugging)
Route::get('/test-login-attempt', function () {
    try {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password'
        ];
        
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found']);
        }
        
        $passwordValid = \Hash::check($credentials['password'], $user->password);
        $authAttempt = \Auth::attempt($credentials);
        
        return response()->json([
            'user_found' => true,
            'user_active' => $user->is_active,
            'password_valid' => $passwordValid,
            'auth_attempt_result' => $authAttempt,
            'auth_check' => \Auth::check(),
            'authenticated_user' => \Auth::user() ? \Auth::user()->email : null
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Check database schema (temporary for debugging)
Route::get('/check-db-schema', function () {
    try {
        // Check if users table has required columns
        $hasRoleColumn = \Schema::hasColumn('users', 'role');
        $hasIsActiveColumn = \Schema::hasColumn('users', 'is_active');
        
        // Get table columns
        $columns = \Schema::getColumnListing('users');
        
        // Count users
        $userCount = \App\Models\User::count();
        
        return response()->json([
            'has_role_column' => $hasRoleColumn,
            'has_is_active_column' => $hasIsActiveColumn,
            'all_columns' => $columns,
            'user_count' => $userCount,
            'database_path' => database_path('database.sqlite'),
            'database_exists' => file_exists(database_path('database.sqlite'))
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Force run migrations (temporary for debugging)
Route::get('/run-migrations', function () {
    try {
        \Artisan::call('migrate', ['--force' => true]);
        $output = \Artisan::output();
        
        return response()->json([
            'message' => 'Migrations completed',
            'output' => $output,
            'artisan_call_result' => 'success'
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Complete seeding summary
Route::get('/seeding-summary', function () {
    try {
        $summary = [];
        
        // Get counts
        $summary['database_counts'] = [
            'users' => \DB::table('users')->count(),
            'clients' => \DB::table('clients')->count(),
            'personnel' => \DB::table('personnels')->count(),
            'equipment' => \DB::table('equipment')->count(),
            'consumables' => \DB::table('consumables')->count(),
            'inspections' => \DB::table('inspections')->count()
        ];
        
        // Get sample users with roles
        $summary['users'] = \DB::table('users')
            ->select('name', 'email', 'role', 'is_active')
            ->get();
        
        // Get sample clients
        $summary['clients'] = \DB::table('clients')
            ->select('client_name', 'client_code', 'contact_person', 'industry')
            ->get();
        
        // Get sample personnel
        $summary['personnel'] = \DB::table('personnels')
            ->select('first_name', 'last_name', 'position', 'department', 'employee_id')
            ->get();
        
        // Get sample equipment
        $summary['equipment'] = \DB::table('equipment')
            ->select('name', 'type', 'serial_number', 'status')
            ->get();
        
        // Get sample consumables
        $summary['consumables'] = \DB::table('consumables')
            ->select('name', 'type', 'quantity_available', 'unit')
            ->get();
        
        $summary['credentials'] = [
            'Super Admin: admin@inspectionservices.com / admin123',
            'Admin: admin@company.com / password',
            'Inspector: inspector@company.com / password',
            'QA Manager: qa@company.com / password',
            'Test: test@example.com / password'
        ];
        
        $summary['seeders_run'] = [
            'SuperAdminSeeder',
            'TestUserSeeder', 
            'ClientSeeder',
            'PersonnelSeeder',
            'EquipmentSeeder',
            'ConsumableSeeder',
            'InspectionDataSeeder',
            'CompleteInspectionSeeder'
        ];
        
        $summary['success_message'] = 'All Laravel seeders have been executed successfully using php artisan db:seed!';
        
        return response()->json($summary);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Run database seeders via web route
Route::get('/run-seeders', function () {
    try {
        // Run migrations first
        \Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Artisan::output();
        
        // Run seeders
        \Artisan::call('db:seed');
        $seedOutput = \Artisan::output();
        
        return response()->json([
            'message' => 'Migrations and seeders completed successfully!',
            'migrate_output' => $migrateOutput,
            'seed_output' => $seedOutput,
            'final_counts' => [
                'users' => \DB::table('users')->count(),
                'clients' => \DB::table('clients')->count(),
                'personnel' => \DB::table('personnels')->count(),
                'equipment' => \DB::table('equipment')->count(),
                'consumables' => \DB::table('consumables')->count()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Simple data verification
Route::get('/verify-data', function () {
    try {
        $data = [];
        
        // Check clients
        $data['clients_count'] = \DB::table('clients')->count();
        $data['clients_data'] = \DB::table('clients')->get();
        
        // Check personnel  
        $data['personnel_count'] = \DB::table('personnels')->count();
        $data['personnel_data'] = \DB::table('personnels')->get();
        
        // Check users
        $data['users_count'] = \DB::table('users')->count();
        $data['users_data'] = \DB::table('users')->select('name', 'email', 'role')->get();
        
        return response()->json($data);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

// Force seed clients and personnel with error checking
Route::get('/force-seed-data', function () {
    try {
        $results = [];
        
        // Check if tables exist
        $clientsExist = \Schema::hasTable('clients');
        $personnelExist = \Schema::hasTable('personnels');
        
        $results['tables_exist'] = [
            'clients' => $clientsExist,
            'personnel' => $personnelExist
        ];
        
        if (!$clientsExist) {
            return response()->json(['error' => 'Clients table does not exist. Run migrations first.']);
        }
        
        if (!$personnelExist) {
            return response()->json(['error' => 'Personnel table does not exist. Run migrations first.']);
        }
        
        // Clear existing data first (optional)
        $clearData = request()->get('clear', false);
        if ($clearData) {
            \DB::table('clients')->truncate();
            \DB::table('personnels')->truncate();
            $results['cleared_tables'] = true;
        }
        
        // Insert clients one by one
        $clientsData = [
            ['client_name' => 'Acme Corporation', 'client_code' => 'ACME001', 'contact_person' => 'John Smith', 'contact_email' => 'john@acme.com', 'phone' => '+1-555-0101', 'is_active' => 1],
            ['client_name' => 'Global Industries', 'client_code' => 'GLOB001', 'contact_person' => 'Jane Doe', 'contact_email' => 'jane@global.com', 'phone' => '+1-555-0102', 'is_active' => 1],
            ['client_name' => 'Tech Solutions', 'client_code' => 'TECH001', 'contact_person' => 'Bob Johnson', 'contact_email' => 'bob@tech.com', 'phone' => '+1-555-0103', 'is_active' => 1],
            ['client_name' => 'Petro Chemical Ltd', 'client_code' => 'PETRO001', 'contact_person' => 'Sarah Wilson', 'contact_email' => 'sarah@petrochemical.com', 'phone' => '+1-555-0104', 'is_active' => 1],
            ['client_name' => 'Marine Services Inc', 'client_code' => 'MARINE001', 'contact_person' => 'Mike Rodriguez', 'contact_email' => 'mike@marine.com', 'phone' => '+1-555-0105', 'is_active' => 1]
        ];
        
        $clientResults = [];
        foreach ($clientsData as $client) {
            try {
                $client['created_at'] = now();
                $client['updated_at'] = now();
                
                $exists = \DB::table('clients')->where('client_code', $client['client_code'])->exists();
                if (!$exists) {
                    $id = \DB::table('clients')->insertGetId($client);
                    $clientResults[] = "Created: {$client['client_name']} (ID: {$id})";
                } else {
                    $clientResults[] = "Exists: {$client['client_name']}";
                }
            } catch (\Exception $e) {
                $clientResults[] = "Error: {$client['client_name']} - " . $e->getMessage();
            }
        }
        
        // Insert personnel one by one
        $personnelData = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'position' => 'Senior Inspector', 'department' => 'Quality Assurance', 'employee_id' => 'EMP001', 'email' => 'john.smith@company.com', 'phone' => '+1-555-0101', 'is_active' => 1],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'position' => 'Lead Inspector', 'department' => 'NDT Services', 'employee_id' => 'EMP002', 'email' => 'sarah.johnson@company.com', 'phone' => '+1-555-0102', 'is_active' => 1],
            ['first_name' => 'Michael', 'last_name' => 'Chen', 'position' => 'Quality Technician', 'department' => 'Quality Control', 'employee_id' => 'EMP003', 'email' => 'michael.chen@company.com', 'phone' => '+1-555-0103', 'is_active' => 1],
            ['first_name' => 'David', 'last_name' => 'Williams', 'position' => 'Senior NDT Technician', 'department' => 'NDT Services', 'employee_id' => 'EMP004', 'email' => 'david.williams@company.com', 'phone' => '+1-555-0104', 'is_active' => 1],
            ['first_name' => 'Lisa', 'last_name' => 'Brown', 'position' => 'Inspection Coordinator', 'department' => 'Quality Assurance', 'employee_id' => 'EMP005', 'email' => 'lisa.brown@company.com', 'phone' => '+1-555-0105', 'is_active' => 1]
        ];
        
        $personnelResults = [];
        foreach ($personnelData as $person) {
            try {
                $person['created_at'] = now();
                $person['updated_at'] = now();
                
                $exists = \DB::table('personnels')->where('employee_id', $person['employee_id'])->exists();
                if (!$exists) {
                    $id = \DB::table('personnels')->insertGetId($person);
                    $personnelResults[] = "Created: {$person['first_name']} {$person['last_name']} (ID: {$id})";
                } else {
                    $personnelResults[] = "Exists: {$person['first_name']} {$person['last_name']}";
                }
            } catch (\Exception $e) {
                $personnelResults[] = "Error: {$person['first_name']} {$person['last_name']} - " . $e->getMessage();
            }
        }
        
        // Get final counts
        $results['client_operations'] = $clientResults;
        $results['personnel_operations'] = $personnelResults;
        $results['final_counts'] = [
            'clients' => \DB::table('clients')->count(),
            'personnel' => \DB::table('personnels')->count()
        ];
        
        // Verify data exists
        $results['sample_clients'] = \DB::table('clients')->select('client_name', 'client_code')->get();
        $results['sample_personnel'] = \DB::table('personnels')->select('first_name', 'last_name', 'employee_id')->get();
        
        return response()->json($results);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Database summary after seeding
Route::get('/database-summary', function () {
    try {
        $summary = [];
        
        // Get counts
        $summary['counts'] = [
            'users' => \DB::table('users')->count(),
            'clients' => \DB::table('clients')->count(),
            'personnel' => \DB::table('personnels')->count(),
            'equipment' => \DB::table('equipment')->count(),
            'consumables' => \DB::table('consumables')->count(),
            'inspections' => \DB::table('inspections')->count()
        ];
        
        // Get sample data
        $summary['sample_data'] = [
            'users' => \DB::table('users')->select('name', 'email', 'role')->get(),
            'clients' => \DB::table('clients')->select('client_name', 'client_code', 'contact_person')->get(),
            'personnel' => \DB::table('personnels')->select('first_name', 'last_name', 'position', 'employee_id')->get(),
            'equipment' => \DB::table('equipment')->select('name', 'type', 'serial_number')->get(),
            'consumables' => \DB::table('consumables')->select('name', 'type', 'quantity_available')->get()
        ];
        
        $summary['credentials'] = [
            'Super Admin: admin@inspectionservices.com / admin123',
            'Admin: admin@company.com / password',
            'Inspector: inspector@company.com / password',
            'QA Manager: qa@company.com / password',
            'Test: test@example.com / password'
        ];
        
        $summary['ready_for_testing'] = 'Database is fully seeded and ready for inspection workflow testing with QA approval!';
        
        return response()->json($summary);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Complete database seeding - all tables
Route::get('/seed-everything', function () {
    try {
        $results = [];
        
        // 1. Seed Users
        $users = [
            ['name' => 'Super Admin', 'email' => 'admin@inspectionservices.com', 'password' => \Hash::make('admin123'), 'role' => 'super_admin', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin User', 'email' => 'admin@company.com', 'password' => \Hash::make('password'), 'role' => 'admin', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inspector User', 'email' => 'inspector@company.com', 'password' => \Hash::make('password'), 'role' => 'inspector', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'QA Manager', 'email' => 'qa@company.com', 'password' => \Hash::make('password'), 'role' => 'qa', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Test User', 'email' => 'test@example.com', 'password' => \Hash::make('password'), 'role' => 'super_admin', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $userCount = 0;
        foreach ($users as $user) {
            if (!\DB::table('users')->where('email', $user['email'])->exists()) {
                \DB::table('users')->insert($user);
                $userCount++;
            }
        }
        $results['users_created'] = $userCount;
        
        // 2. Seed Clients
        $clients = [
            ['client_name' => 'Acme Corporation', 'client_code' => 'ACME001', 'contact_person' => 'John Smith', 'contact_email' => 'john@acme.com', 'phone' => '+1-555-0101', 'address' => '123 Main St', 'city' => 'Houston', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77001', 'company_type' => 'Corporation', 'industry' => 'Oil & Gas', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['client_name' => 'Global Industries', 'client_code' => 'GLOB001', 'contact_person' => 'Jane Doe', 'contact_email' => 'jane@global.com', 'phone' => '+1-555-0102', 'address' => '456 Oak Ave', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '75201', 'company_type' => 'Corporation', 'industry' => 'Manufacturing', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['client_name' => 'Tech Solutions', 'client_code' => 'TECH001', 'contact_person' => 'Bob Johnson', 'contact_email' => 'bob@tech.com', 'phone' => '+1-555-0103', 'address' => '789 Pine Rd', 'city' => 'Austin', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '73301', 'company_type' => 'LLC', 'industry' => 'Technology', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['client_name' => 'Petro Chemical Ltd', 'client_code' => 'PETRO001', 'contact_person' => 'Sarah Wilson', 'contact_email' => 'sarah@petrochemical.com', 'phone' => '+1-555-0104', 'address' => '321 Industrial Blvd', 'city' => 'Baytown', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77520', 'company_type' => 'Corporation', 'industry' => 'Petrochemical', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['client_name' => 'Marine Services Inc', 'client_code' => 'MARINE001', 'contact_person' => 'Mike Rodriguez', 'contact_email' => 'mike@marine.com', 'phone' => '+1-555-0105', 'address' => '555 Harbor Dr', 'city' => 'Galveston', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77550', 'company_type' => 'Corporation', 'industry' => 'Marine', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $clientCount = 0;
        foreach ($clients as $client) {
            if (!\DB::table('clients')->where('client_code', $client['client_code'])->exists()) {
                \DB::table('clients')->insert($client);
                $clientCount++;
            }
        }
        $results['clients_created'] = $clientCount;
        
        // 3. Seed Personnel
        $personnel = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'position' => 'Senior Inspector', 'department' => 'Quality Assurance', 'employee_id' => 'EMP001', 'email' => 'john.smith@company.com', 'phone' => '+1-555-0101', 'qualifications' => 'ASNT Level III, API 510', 'certifications' => 'NDT Level 3 (UT, RT, MT, PT)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'position' => 'Lead Inspector', 'department' => 'NDT Services', 'employee_id' => 'EMP002', 'email' => 'sarah.johnson@company.com', 'phone' => '+1-555-0102', 'qualifications' => 'ASNT Level II, LEEA Inspector', 'certifications' => 'NDT Level 2 (UT, MT, PT)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Michael', 'last_name' => 'Chen', 'position' => 'Quality Technician', 'department' => 'Quality Control', 'employee_id' => 'EMP003', 'email' => 'michael.chen@company.com', 'phone' => '+1-555-0103', 'qualifications' => 'ASNT Level I, AWS Certified', 'certifications' => 'NDT Level 1 (MT, PT)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'David', 'last_name' => 'Williams', 'position' => 'Senior NDT Technician', 'department' => 'NDT Services', 'employee_id' => 'EMP004', 'email' => 'david.williams@company.com', 'phone' => '+1-555-0104', 'qualifications' => 'ASNT Level II, PCN Level 2', 'certifications' => 'NDT Level 2 (UT, RT, ET)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Lisa', 'last_name' => 'Brown', 'position' => 'Inspection Coordinator', 'department' => 'Quality Assurance', 'employee_id' => 'EMP005', 'email' => 'lisa.brown@company.com', 'phone' => '+1-555-0105', 'qualifications' => 'CSWIP 3.1, IWE', 'certifications' => 'Welding Inspector Level 3', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Robert', 'last_name' => 'Davis', 'position' => 'Materials Engineer', 'department' => 'Engineering', 'employee_id' => 'EMP006', 'email' => 'robert.davis@company.com', 'phone' => '+1-555-0106', 'qualifications' => 'P.E., NACE Level 3', 'certifications' => 'Corrosion Specialist', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Amanda', 'last_name' => 'Garcia', 'position' => 'QA Manager', 'department' => 'Quality Assurance', 'employee_id' => 'EMP007', 'email' => 'amanda.garcia@company.com', 'phone' => '+1-555-0107', 'qualifications' => 'ISO 9001 Lead Auditor, Six Sigma Black Belt', 'certifications' => 'Quality Management Systems', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Thomas', 'last_name' => 'Miller', 'position' => 'Lifting Equipment Inspector', 'department' => 'Mechanical Services', 'employee_id' => 'EMP008', 'email' => 'thomas.miller@company.com', 'phone' => '+1-555-0108', 'qualifications' => 'LEEA Inspector, LOLER', 'certifications' => 'Lifting Equipment Specialist', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $personnelCount = 0;
        foreach ($personnel as $person) {
            if (!\DB::table('personnels')->where('employee_id', $person['employee_id'])->exists()) {
                \DB::table('personnels')->insert($person);
                $personnelCount++;
            }
        }
        $results['personnel_created'] = $personnelCount;
        
        // 4. Seed Equipment
        $equipment = [
            ['name' => 'Ultrasonic Testing Device', 'type' => 'NDT Equipment', 'serial_number' => 'UT001', 'brand_model' => 'UT-Pro-2000', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Magnetic Particle Tester', 'type' => 'NDT Equipment', 'serial_number' => 'MT001', 'brand_model' => 'MT-Master-500', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Digital Caliper', 'type' => 'Measuring Tool', 'serial_number' => 'CAL001', 'brand_model' => 'DC-Precision-150', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Radiographic Testing Unit', 'type' => 'NDT Equipment', 'serial_number' => 'RT001', 'brand_model' => 'RT-X-Ray-300', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Penetrant Testing Kit', 'type' => 'NDT Equipment', 'serial_number' => 'PT001', 'brand_model' => 'PT-Dye-Kit', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Eddy Current Tester', 'type' => 'NDT Equipment', 'serial_number' => 'ET001', 'brand_model' => 'ET-Scan-400', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $equipmentCount = 0;
        foreach ($equipment as $equip) {
            if (!\DB::table('equipment')->where('serial_number', $equip['serial_number'])->exists()) {
                \DB::table('equipment')->insert($equip);
                $equipmentCount++;
            }
        }
        $results['equipment_created'] = $equipmentCount;
        
        // 5. Seed Consumables
        $consumables = [
            ['name' => 'Ultrasonic Gel', 'type' => 'Coupling Agent', 'unit' => 'bottle', 'quantity_available' => 50, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Magnetic Particles', 'type' => 'Test Medium', 'unit' => 'kg', 'quantity_available' => 25, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Penetrant Spray', 'type' => 'Test Chemical', 'unit' => 'can', 'quantity_available' => 30, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'X-Ray Film', 'type' => 'Imaging Medium', 'unit' => 'sheet', 'quantity_available' => 100, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Developer Solution', 'type' => 'Processing Chemical', 'unit' => 'liter', 'quantity_available' => 20, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fixer Solution', 'type' => 'Processing Chemical', 'unit' => 'liter', 'quantity_available' => 15, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $consumableCount = 0;
        foreach ($consumables as $consumable) {
            if (!\DB::table('consumables')->where('name', $consumable['name'])->exists()) {
                \DB::table('consumables')->insert($consumable);
                $consumableCount++;
            }
        }
        $results['consumables_created'] = $consumableCount;
        
        // Final counts
        $results['totals'] = [
            'users' => \DB::table('users')->count(),
            'clients' => \DB::table('clients')->count(),
            'personnel' => \DB::table('personnels')->count(),
            'equipment' => \DB::table('equipment')->count(),
            'consumables' => \DB::table('consumables')->count()
        ];
        
        $results['message'] = 'All database tables seeded successfully!';
        $results['credentials'] = [
            'Super Admin: admin@inspectionservices.com / admin123',
            'Admin: admin@company.com / password',
            'Inspector: inspector@company.com / password',
            'QA Manager: qa@company.com / password',
            'Test: test@example.com / password'
        ];
        
        return response()->json($results);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Direct SQL insert for personnel
Route::get('/sql-insert-personnel', function () {
    try {
        $results = [];
        
        // Check if personnel table exists
        if (!\Schema::hasTable('personnels')) {
            return response()->json(['error' => 'Personnel table does not exist']);
        }
        
        // Direct SQL inserts for personnel
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
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
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
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
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
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Williams',
                'position' => 'Senior NDT Technician',
                'department' => 'NDT Services',
                'employee_id' => 'EMP004',
                'email' => 'david.williams@company.com',
                'phone' => '+1-555-0104',
                'qualifications' => 'ASNT Level II, PCN Level 2',
                'certifications' => 'NDT Level 2 (UT, RT, ET)',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Brown',
                'position' => 'Inspection Coordinator',
                'department' => 'Quality Assurance',
                'employee_id' => 'EMP005',
                'email' => 'lisa.brown@company.com',
                'phone' => '+1-555-0105',
                'qualifications' => 'CSWIP 3.1, IWE',
                'certifications' => 'Welding Inspector Level 3',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        foreach ($personnel as $person) {
            try {
                // Check if exists
                $exists = \DB::table('personnels')->where('employee_id', $person['employee_id'])->exists();
                
                if (!$exists) {
                    $id = \DB::table('personnels')->insertGetId($person);
                    $results[] = "Inserted {$person['first_name']} {$person['last_name']} with ID: {$id}";
                } else {
                    $results[] = "{$person['first_name']} {$person['last_name']} already exists";
                }
            } catch (\Exception $e) {
                $results[] = "Error inserting {$person['first_name']} {$person['last_name']}: " . $e->getMessage();
            }
        }
        
        // Get final count
        $totalPersonnel = \DB::table('personnels')->count();
        $allPersonnel = \DB::table('personnels')->get();
        
        return response()->json([
            'results' => $results,
            'total_personnel' => $totalPersonnel,
            'all_personnel' => $allPersonnel
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Direct SQL insert for clients
Route::get('/sql-insert-clients', function () {
    try {
        $results = [];
        
        // Check if clients table exists
        if (!\Schema::hasTable('clients')) {
            return response()->json(['error' => 'Clients table does not exist']);
        }
        
        // Direct SQL inserts
        $clients = [
            [
                'client_name' => 'Acme Corporation',
                'client_code' => 'ACME001',
                'contact_person' => 'John Smith',
                'contact_email' => 'john@acme.com',
                'phone' => '+1-555-0101',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'client_name' => 'Global Industries',
                'client_code' => 'GLOB001',
                'contact_person' => 'Jane Doe',
                'contact_email' => 'jane@global.com',
                'phone' => '+1-555-0102',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'client_name' => 'Tech Solutions',
                'client_code' => 'TECH001',
                'contact_person' => 'Bob Johnson',
                'contact_email' => 'bob@tech.com',
                'phone' => '+1-555-0103',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        foreach ($clients as $client) {
            try {
                // Check if exists
                $exists = \DB::table('clients')->where('client_code', $client['client_code'])->exists();
                
                if (!$exists) {
                    $id = \DB::table('clients')->insertGetId($client);
                    $results[] = "Inserted {$client['client_name']} with ID: {$id}";
                } else {
                    $results[] = "{$client['client_name']} already exists";
                }
            } catch (\Exception $e) {
                $results[] = "Error inserting {$client['client_name']}: " . $e->getMessage();
            }
        }
        
        // Get final count
        $totalClients = \DB::table('clients')->count();
        $allClients = \DB::table('clients')->get();
        
        return response()->json([
            'results' => $results,
            'total_clients' => $totalClients,
            'all_clients' => $allClients
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Complete database debug
Route::get('/debug-database', function () {
    try {
        $info = [];
        
        // Check database connection
        try {
            \DB::connection()->getPdo();
            $info['database_connected'] = true;
        } catch (\Exception $e) {
            $info['database_connected'] = false;
            $info['connection_error'] = $e->getMessage();
        }
        
        // Check if tables exist
        $tables = ['users', 'clients', 'personnel', 'equipment', 'consumables', 'inspections'];
        foreach ($tables as $table) {
            $info['tables'][$table] = \Schema::hasTable($table);
            if ($info['tables'][$table]) {
                try {
                    $count = \DB::table($table)->count();
                    $info['counts'][$table] = $count;
                } catch (\Exception $e) {
                    $info['counts'][$table] = 'Error: ' . $e->getMessage();
                }
            }
        }
        
        // Get clients table structure if it exists
        if (\Schema::hasTable('clients')) {
            $info['clients_columns'] = \Schema::getColumnListing('clients');
        }
        
        // Try direct database query
        try {
            $clients = \DB::table('clients')->get();
            $info['direct_query_clients'] = $clients->toArray();
        } catch (\Exception $e) {
            $info['direct_query_error'] = $e->getMessage();
        }
        
        // Try using the model
        try {
            $modelClients = \App\Models\Client::all();
            $info['model_query_clients'] = $modelClients->toArray();
        } catch (\Exception $e) {
            $info['model_query_error'] = $e->getMessage();
        }
        
        return response()->json($info);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Force create clients with minimal data
Route::get('/force-create-clients', function () {
    try {
        // Simple client data that matches the table structure
        $clients = [
            [
                'client_name' => 'Acme Corporation',
                'client_code' => 'ACME001',
                'contact_person' => 'John Smith',
                'contact_email' => 'john@acme.com',
                'phone' => '+1-555-0101',
                'is_active' => true
            ],
            [
                'client_name' => 'Global Industries',
                'client_code' => 'GLOB001',
                'contact_person' => 'Jane Doe',
                'contact_email' => 'jane@global.com',
                'phone' => '+1-555-0102',
                'is_active' => true
            ],
            [
                'client_name' => 'Tech Solutions',
                'client_code' => 'TECH001',
                'contact_person' => 'Bob Johnson',
                'contact_email' => 'bob@tech.com',
                'phone' => '+1-555-0103',
                'is_active' => true
            ]
        ];

        $created = [];
        $errors = [];

        foreach ($clients as $clientData) {
            try {
                // Check if already exists
                $existing = \App\Models\Client::where('client_code', $clientData['client_code'])->first();
                
                if (!$existing) {
                    $client = \App\Models\Client::create($clientData);
                    $created[] = $client->client_name;
                } else {
                    $created[] = $existing->client_name . ' (already exists)';
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'client' => $clientData['client_name'],
                    'error' => $e->getMessage()
                ];
            }
        }

        $totalClients = \App\Models\Client::count();
        $allClients = \App\Models\Client::all();

        return response()->json([
            'message' => 'Client creation attempt completed',
            'created' => $created,
            'errors' => $errors,
            'total_clients' => $totalClients,
            'all_clients' => $allClients->toArray()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Debug clients table structure
Route::get('/debug-clients-table', function () {
    try {
        // Check if clients table exists
        $tableExists = \Schema::hasTable('clients');
        
        if (!$tableExists) {
            return response()->json(['error' => 'Clients table does not exist']);
        }
        
        // Get table columns
        $columns = \Schema::getColumnListing('clients');
        
        // Try to get clients count
        $clientCount = \App\Models\Client::count();
        
        // Try to create a test client
        $testClient = [
            'client_name' => 'Test Client',
            'client_code' => 'TEST001',
            'contact_person' => 'Test Person',
            'contact_email' => 'test@test.com',
            'phone' => '+1-555-0000',
            'is_active' => true
        ];
        
        $created = null;
        try {
            $existing = \App\Models\Client::where('client_code', 'TEST001')->first();
            if (!$existing) {
                $created = \App\Models\Client::create($testClient);
            } else {
                $created = $existing;
            }
        } catch (\Exception $createError) {
            return response()->json([
                'table_exists' => $tableExists,
                'columns' => $columns,
                'client_count' => $clientCount,
                'create_error' => $createError->getMessage(),
                'create_trace' => $createError->getTraceAsString()
            ]);
        }
        
        return response()->json([
            'table_exists' => $tableExists,
            'columns' => $columns,
            'client_count' => $clientCount,
            'test_client_created' => $created ? true : false,
            'test_client' => $created
        ]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Debug client API (temporary for debugging)
Route::get('/debug-client-api', function () {
    try {
        // Test basic Client query
        $clientCount = \App\Models\Client::count();
        echo "Total clients: " . $clientCount . "\n";
        
        if ($clientCount > 0) {
            $client = \App\Models\Client::first();
            echo "First client: " . json_encode($client->toArray()) . "\n";
        }
        
        // Test the same query as in ClientController
        $query = \App\Models\Client::active();
        $clients = $query->select([
            'id',
            'client_name',
            'client_code',
            'company_type',
            'industry',
            'address',
            'city',
            'state',
            'country',
            'postal_code',
            'phone',
            'email',
            'website',
            'contact_person',
            'contact_position',
            'contact_phone',
            'contact_email',
            'payment_terms',
            'preferred_currency',
            'notes'
        ])
        ->orderBy('client_name')
        ->limit(50)
        ->get();
        
        echo "Query result count: " . $clients->count() . "\n";
        
        return response()->json([
            'success' => true,
            'total_clients' => $clientCount,
            'active_clients' => $clients->count(),
            'clients' => $clients
        ]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Temporary API routes without authentication for testing
Route::get('/test-api/clients', function () {
    try {
        $clients = \App\Models\Client::active()
            ->select(['id', 'client_name', 'client_code', 'contact_person', 'phone', 'email'])
            ->orderBy('client_name')
            ->get();
        
        return response()->json($clients);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/fix-equipment-data', function () {
    $equipment = \App\Models\Equipment::all();
    $updated = [];
    
    foreach ($equipment as $eq) {
        $changes = [];
        
        if ($eq->is_active !== true) {
            $eq->is_active = true;
            $changes[] = 'set is_active to true';
        }
        
        if (empty($eq->equipment_category) || $eq->equipment_category !== 'asset') {
            $eq->equipment_category = 'asset';
            $changes[] = 'set equipment_category to asset';
        }
        
        if (!empty($changes)) {
            $eq->save();
            $updated[] = [
                'id' => $eq->id,
                'name' => $eq->name,
                'changes' => $changes
            ];
        }
    }
    
    return response()->json([
        'message' => 'Equipment data updated',
        'updated_count' => count($updated),
        'updated_equipment' => $updated,
        'final_active_asset_count' => \App\Models\Equipment::active()->assets()->count()
    ], 200, [], JSON_PRETTY_PRINT);
});

// Complete database seeding route
Route::get('/seed-all-data', function () {
    try {
        $results = [];
        
        // 1. Create Users (including QA)
        $users = [
            ['name' => 'Super Admin', 'email' => 'admin@inspectionservices.com', 'password' => 'admin123', 'role' => 'super_admin', 'is_active' => true],
            ['name' => 'Admin User', 'email' => 'admin@company.com', 'password' => 'password', 'role' => 'admin', 'is_active' => true],
            ['name' => 'Inspector User', 'email' => 'inspector@company.com', 'password' => 'password', 'role' => 'inspector', 'is_active' => true],
            ['name' => 'QA Manager', 'email' => 'qa@company.com', 'password' => 'password', 'role' => 'qa', 'is_active' => true],
            ['name' => 'Test User', 'email' => 'test@example.com', 'password' => 'password', 'role' => 'super_admin', 'is_active' => true]
        ];
        
        $userCount = 0;
        foreach ($users as $userData) {
            if (!\App\Models\User::where('email', $userData['email'])->exists()) {
                \App\Models\User::create($userData);
                $userCount++;
            }
        }
        $results['users_created'] = $userCount;
        
        // 2. Create Clients
        $clients = [
            ['client_name' => 'Acme Corporation', 'client_code' => 'ACME001', 'contact_person' => 'John Smith', 'contact_email' => 'john@acme.com', 'phone' => '+1-555-0101', 'address' => '123 Main St', 'city' => 'Houston', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77001', 'company_type' => 'Corporation', 'industry' => 'Oil & Gas', 'is_active' => true],
            ['client_name' => 'Global Industries', 'client_code' => 'GLOB001', 'contact_person' => 'Jane Doe', 'contact_email' => 'jane@global.com', 'phone' => '+1-555-0102', 'address' => '456 Oak Ave', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '75201', 'company_type' => 'Corporation', 'industry' => 'Manufacturing', 'is_active' => true],
            ['client_name' => 'Tech Solutions', 'client_code' => 'TECH001', 'contact_person' => 'Bob Johnson', 'contact_email' => 'bob@tech.com', 'phone' => '+1-555-0103', 'address' => '789 Pine Rd', 'city' => 'Austin', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '73301', 'company_type' => 'LLC', 'industry' => 'Technology', 'is_active' => true],
            ['client_name' => 'Petro Chemical Ltd', 'client_code' => 'PETRO001', 'contact_person' => 'Sarah Wilson', 'contact_email' => 'sarah@petrochemical.com', 'phone' => '+1-555-0104', 'address' => '321 Industrial Blvd', 'city' => 'Baytown', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77520', 'company_type' => 'Corporation', 'industry' => 'Petrochemical', 'is_active' => true],
            ['client_name' => 'Marine Services Inc', 'client_code' => 'MARINE001', 'contact_person' => 'Mike Rodriguez', 'contact_email' => 'mike@marine.com', 'phone' => '+1-555-0105', 'address' => '555 Harbor Dr', 'city' => 'Galveston', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77550', 'company_type' => 'Corporation', 'industry' => 'Marine', 'is_active' => true]
        ];
        
        $clientCount = 0;
        foreach ($clients as $clientData) {
            if (!\App\Models\Client::where('client_code', $clientData['client_code'])->exists()) {
                \App\Models\Client::create($clientData);
                $clientCount++;
            }
        }
        $results['clients_created'] = $clientCount;
        
        // 3. Create Personnel
        $personnel = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'position' => 'Senior Inspector', 'department' => 'Quality Assurance', 'employee_id' => 'EMP001', 'email' => 'john.smith@company.com', 'phone' => '+1-555-0101', 'qualifications' => 'ASNT Level III, API 510', 'certifications' => 'NDT Level 3 (UT, RT, MT, PT)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'position' => 'Lead Inspector', 'department' => 'NDT Services', 'employee_id' => 'EMP002', 'email' => 'sarah.johnson@company.com', 'phone' => '+1-555-0102', 'qualifications' => 'ASNT Level II, LEEA Inspector', 'certifications' => 'NDT Level 2 (UT, MT, PT)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Michael', 'last_name' => 'Chen', 'position' => 'Quality Technician', 'department' => 'Quality Control', 'employee_id' => 'EMP003', 'email' => 'michael.chen@company.com', 'phone' => '+1-555-0103', 'qualifications' => 'ASNT Level I, AWS Certified', 'certifications' => 'NDT Level 1 (MT, PT)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'David', 'last_name' => 'Williams', 'position' => 'Senior NDT Technician', 'department' => 'NDT Services', 'employee_id' => 'EMP004', 'email' => 'david.williams@company.com', 'phone' => '+1-555-0104', 'qualifications' => 'ASNT Level II, PCN Level 2', 'certifications' => 'NDT Level 2 (UT, RT, ET)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Lisa', 'last_name' => 'Brown', 'position' => 'Inspection Coordinator', 'department' => 'Quality Assurance', 'employee_id' => 'EMP005', 'email' => 'lisa.brown@company.com', 'phone' => '+1-555-0105', 'qualifications' => 'CSWIP 3.1, IWE', 'certifications' => 'Welding Inspector Level 3', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Robert', 'last_name' => 'Davis', 'position' => 'Materials Engineer', 'department' => 'Engineering', 'employee_id' => 'EMP006', 'email' => 'robert.davis@company.com', 'phone' => '+1-555-0106', 'qualifications' => 'P.E., NACE Level 3', 'certifications' => 'Corrosion Specialist', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Amanda', 'last_name' => 'Garcia', 'position' => 'QA Manager', 'department' => 'Quality Assurance', 'employee_id' => 'EMP007', 'email' => 'amanda.garcia@company.com', 'phone' => '+1-555-0107', 'qualifications' => 'ISO 9001 Lead Auditor, Six Sigma Black Belt', 'certifications' => 'Quality Management Systems', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Thomas', 'last_name' => 'Miller', 'position' => 'Lifting Equipment Inspector', 'department' => 'Mechanical Services', 'employee_id' => 'EMP008', 'email' => 'thomas.miller@company.com', 'phone' => '+1-555-0108', 'qualifications' => 'LEEA Inspector, LOLER', 'certifications' => 'Lifting Equipment Specialist', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $personnelCount = 0;
        foreach ($personnel as $personData) {
            if (!\App\Models\Personnel::where('employee_id', $personData['employee_id'])->exists()) {
                \App\Models\Personnel::create($personData);
                $personnelCount++;
            }
        }
        $results['personnel_created'] = $personnelCount;
        
        // 4. Create Equipment
        $equipment = [
            ['name' => 'Ultrasonic Testing Device', 'type' => 'NDT Equipment', 'serial_number' => 'UT001', 'brand_model' => 'UT-Pro-2000', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Magnetic Particle Tester', 'type' => 'NDT Equipment', 'serial_number' => 'MT001', 'brand_model' => 'MT-Master-500', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Digital Caliper', 'type' => 'Measuring Tool', 'serial_number' => 'CAL001', 'brand_model' => 'DC-Precision-150', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Radiographic Testing Unit', 'type' => 'NDT Equipment', 'serial_number' => 'RT001', 'brand_model' => 'RT-X-Ray-300', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Penetrant Testing Kit', 'type' => 'NDT Equipment', 'serial_number' => 'PT001', 'brand_model' => 'PT-Dye-Kit', 'equipment_category' => 'asset', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $equipmentCount = 0;
        foreach ($equipment as $equipData) {
            if (!\App\Models\Equipment::where('serial_number', $equipData['serial_number'])->exists()) {
                \App\Models\Equipment::create($equipData);
                $equipmentCount++;
            }
        }
        $results['equipment_created'] = $equipmentCount;
        
        // 5. Create Consumables
        $consumables = [
            ['name' => 'Ultrasonic Gel', 'type' => 'Coupling Agent', 'unit' => 'bottle', 'quantity_available' => 50, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Magnetic Particles', 'type' => 'Test Medium', 'unit' => 'kg', 'quantity_available' => 25, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Penetrant Spray', 'type' => 'Test Chemical', 'unit' => 'can', 'quantity_available' => 30, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'X-Ray Film', 'type' => 'Imaging Medium', 'unit' => 'sheet', 'quantity_available' => 100, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Developer Solution', 'type' => 'Processing Chemical', 'unit' => 'liter', 'quantity_available' => 20, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fixer Solution', 'type' => 'Processing Chemical', 'unit' => 'liter', 'quantity_available' => 15, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        
        $consumableCount = 0;
        foreach ($consumables as $consumableData) {
            if (!\App\Models\Consumable::where('name', $consumableData['name'])->exists()) {
                \App\Models\Consumable::create($consumableData);
                $consumableCount++;
            }
        }
        $results['consumables_created'] = $consumableCount;
        
        // Add totals
        $results['total_users'] = \App\Models\User::count();
        $results['total_clients'] = \App\Models\Client::count();
        $results['total_personnel'] = \App\Models\Personnel::count();
        $results['total_equipment'] = \App\Models\Equipment::count();
        $results['total_consumables'] = \App\Models\Consumable::count();
        
        $results['message'] = 'Complete database seeded successfully!';
        $results['credentials'] = [
            'Super Admin: admin@inspectionservices.com / admin123',
            'Admin: admin@company.com / password',
            'Inspector: inspector@company.com / password',
            'QA Manager: qa@company.com / password',
            'Test: test@example.com / password'
        ];
        
        return response()->json($results);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Seed specific data routes
Route::get('/seed-clients-personnel', function () {
    try {
        // Extended client data
        $clients = [
            ['client_name' => 'Acme Corporation', 'client_code' => 'ACME001', 'contact_person' => 'John Smith', 'contact_email' => 'john@acme.com', 'phone' => '+1-555-0101', 'address' => '123 Main St', 'city' => 'Houston', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77001', 'company_type' => 'Corporation', 'industry' => 'Oil & Gas', 'is_active' => true],
            ['client_name' => 'Global Industries', 'client_code' => 'GLOB001', 'contact_person' => 'Jane Doe', 'contact_email' => 'jane@global.com', 'phone' => '+1-555-0102', 'address' => '456 Oak Ave', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '75201', 'company_type' => 'Corporation', 'industry' => 'Manufacturing', 'is_active' => true],
            ['client_name' => 'Tech Solutions', 'client_code' => 'TECH001', 'contact_person' => 'Bob Johnson', 'contact_email' => 'bob@tech.com', 'phone' => '+1-555-0103', 'address' => '789 Pine Rd', 'city' => 'Austin', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '73301', 'company_type' => 'LLC', 'industry' => 'Technology', 'is_active' => true],
            ['client_name' => 'Petro Chemical Ltd', 'client_code' => 'PETRO001', 'contact_person' => 'Sarah Wilson', 'contact_email' => 'sarah@petrochemical.com', 'phone' => '+1-555-0104', 'address' => '321 Industrial Blvd', 'city' => 'Baytown', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77520', 'company_type' => 'Corporation', 'industry' => 'Petrochemical', 'is_active' => true],
            ['client_name' => 'Marine Services Inc', 'client_code' => 'MARINE001', 'contact_person' => 'Mike Rodriguez', 'contact_email' => 'mike@marine.com', 'phone' => '+1-555-0105', 'address' => '555 Harbor Dr', 'city' => 'Galveston', 'state' => 'TX', 'country' => 'USA', 'postal_code' => '77550', 'company_type' => 'Corporation', 'industry' => 'Marine', 'is_active' => true]
        ];

        $clientCount = 0;
        foreach ($clients as $clientData) {
            $existing = \App\Models\Client::where('client_code', $clientData['client_code'])->first();
            if (!$existing) {
                \App\Models\Client::create($clientData);
                $clientCount++;
            }
        }

        // Extended personnel data
        $personnel = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'position' => 'Senior Inspector', 'department' => 'Quality Assurance', 'employee_id' => 'EMP001', 'email' => 'john.smith@company.com', 'phone' => '+1-555-0101', 'qualifications' => 'ASNT Level III, API 510', 'certifications' => 'NDT Level 3 (UT, RT, MT, PT)', 'is_active' => true],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'position' => 'Lead Inspector', 'department' => 'NDT Services', 'employee_id' => 'EMP002', 'email' => 'sarah.johnson@company.com', 'phone' => '+1-555-0102', 'qualifications' => 'ASNT Level II, LEEA Inspector', 'certifications' => 'NDT Level 2 (UT, MT, PT)', 'is_active' => true],
            ['first_name' => 'Michael', 'last_name' => 'Chen', 'position' => 'Quality Technician', 'department' => 'Quality Control', 'employee_id' => 'EMP003', 'email' => 'michael.chen@company.com', 'phone' => '+1-555-0103', 'qualifications' => 'ASNT Level I, AWS Certified', 'certifications' => 'NDT Level 1 (MT, PT)', 'is_active' => true],
            ['first_name' => 'David', 'last_name' => 'Williams', 'position' => 'Senior NDT Technician', 'department' => 'NDT Services', 'employee_id' => 'EMP004', 'email' => 'david.williams@company.com', 'phone' => '+1-555-0104', 'qualifications' => 'ASNT Level II, PCN Level 2', 'certifications' => 'NDT Level 2 (UT, RT, ET)', 'is_active' => true],
            ['first_name' => 'Robert', 'last_name' => 'Davis', 'position' => 'Materials Engineer', 'department' => 'Engineering', 'employee_id' => 'EMP006', 'email' => 'robert.davis@company.com', 'phone' => '+1-555-0106', 'qualifications' => 'P.E., NACE Level 3', 'certifications' => 'Corrosion Specialist', 'is_active' => true],
            ['first_name' => 'Amanda', 'last_name' => 'Garcia', 'position' => 'QA Manager', 'department' => 'Quality Assurance', 'employee_id' => 'EMP007', 'email' => 'amanda.garcia@company.com', 'phone' => '+1-555-0107', 'qualifications' => 'ISO 9001 Lead Auditor, Six Sigma Black Belt', 'certifications' => 'Quality Management Systems', 'is_active' => true],
            ['first_name' => 'Thomas', 'last_name' => 'Miller', 'position' => 'Lifting Equipment Inspector', 'department' => 'Mechanical Services', 'employee_id' => 'EMP008', 'email' => 'thomas.miller@company.com', 'phone' => '+1-555-0108', 'qualifications' => 'LEEA Inspector, LOLER', 'certifications' => 'Lifting Equipment Specialist', 'is_active' => true]
        ];

        $personnelCount = 0;
        foreach ($personnel as $personData) {
            $existing = \App\Models\Personnel::where('employee_id', $personData['employee_id'])->first();
            if (!$existing) {
                \App\Models\Personnel::create($personData);
                $personnelCount++;
            }
        }

        return response()->json([
            'message' => 'Clients and Personnel seeded successfully!',
            'clients_created' => $clientCount,
            'personnel_created' => $personnelCount,
            'total_clients' => \App\Models\Client::count(),
            'total_personnel' => \App\Models\Personnel::count()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Other routes
Route::get('/create-test-data', function () {
    try {
        // Create test clients
        $clients = [
            ['client_name' => 'Acme Corporation', 'client_code' => 'ACME001', 'contact_person' => 'John Smith', 'contact_email' => 'john@acme.com', 'phone' => '+1-555-0101', 'address' => '123 Main St', 'city' => 'Houston', 'state' => 'TX', 'is_active' => true],
            ['client_name' => 'Global Industries', 'client_code' => 'GLOB001', 'contact_person' => 'Jane Doe', 'contact_email' => 'jane@global.com', 'phone' => '+1-555-0102', 'address' => '456 Oak Ave', 'city' => 'Dallas', 'state' => 'TX', 'is_active' => true],
            ['client_name' => 'Tech Solutions', 'client_code' => 'TECH001', 'contact_person' => 'Bob Johnson', 'contact_email' => 'bob@tech.com', 'phone' => '+1-555-0103', 'address' => '789 Pine Rd', 'city' => 'Austin', 'state' => 'TX', 'is_active' => true]
        ];

        $clientCount = 0;
        foreach ($clients as $clientData) {
            $existing = \App\Models\Client::where('client_code', $clientData['client_code'])->first();
            if (!$existing) {
                \App\Models\Client::create($clientData);
                $clientCount++;
            }
        }

        // Create test equipment
        $equipment = [
            ['name' => 'Ultrasonic Testing Device', 'type' => 'NDT Equipment', 'serial_number' => 'UT001', 'brand_model' => 'UT-Pro-2000', 'equipment_category' => 'asset', 'is_active' => true],
            ['name' => 'Magnetic Particle Tester', 'type' => 'NDT Equipment', 'serial_number' => 'MT001', 'brand_model' => 'MT-Master-500', 'equipment_category' => 'asset', 'is_active' => true],
            ['name' => 'Digital Caliper', 'type' => 'Measuring Tool', 'serial_number' => 'CAL001', 'brand_model' => 'DC-Precision-150', 'equipment_category' => 'asset', 'is_active' => true]
        ];

        $equipmentCount = 0;
        foreach ($equipment as $equipData) {
            $existing = \App\Models\Equipment::where('serial_number', $equipData['serial_number'])->first();
            if (!$existing) {
                \App\Models\Equipment::create($equipData);
                $equipmentCount++;
            }
        }

        // Create test consumables
        $consumables = [
            ['name' => 'Ultrasonic Gel', 'type' => 'Coupling Agent', 'unit' => 'bottle', 'quantity_available' => 50, 'is_active' => true],
            ['name' => 'Magnetic Particles', 'type' => 'Test Medium', 'unit' => 'kg', 'quantity_available' => 25, 'is_active' => true],
            ['name' => 'Penetrant Spray', 'type' => 'Test Chemical', 'unit' => 'can', 'quantity_available' => 30, 'is_active' => true]
        ];

        $consumableCount = 0;
        foreach ($consumables as $consumableData) {
            $existing = \App\Models\Consumable::where('name', $consumableData['name'])->first();
            if (!$existing) {
                \App\Models\Consumable::create($consumableData);
                $consumableCount++;
            }
        }

        // Create test personnel
        $personnel = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'position' => 'Senior Inspector', 'department' => 'Quality Assurance', 'employee_id' => 'EMP001', 'email' => 'john.smith@company.com', 'phone' => '+1-555-0101', 'qualifications' => 'ASNT Level III, API 510', 'certifications' => 'NDT Level 3 (UT, RT, MT, PT)', 'is_active' => true],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'position' => 'Lead Inspector', 'department' => 'NDT Services', 'employee_id' => 'EMP002', 'email' => 'sarah.johnson@company.com', 'phone' => '+1-555-0102', 'qualifications' => 'ASNT Level II, LEEA Inspector', 'certifications' => 'NDT Level 2 (UT, MT, PT)', 'is_active' => true],
            ['first_name' => 'Michael', 'last_name' => 'Chen', 'position' => 'Quality Technician', 'department' => 'Quality Control', 'employee_id' => 'EMP003', 'email' => 'michael.chen@company.com', 'phone' => '+1-555-0103', 'qualifications' => 'ASNT Level I, AWS Certified', 'certifications' => 'NDT Level 1 (MT, PT)', 'is_active' => true]
        ];

        $personnelCount = 0;
        foreach ($personnel as $personData) {
            $existing = \App\Models\Personnel::where('employee_id', $personData['employee_id'])->first();
            if (!$existing) {
                \App\Models\Personnel::create($personData);
                $personnelCount++;
            }
        }

        return response()->json([
            'message' => 'Test data created successfully!',
            'clients_created' => $clientCount,
            'equipment_created' => $equipmentCount,
            'consumables_created' => $consumableCount,
            'personnel_created' => $personnelCount,
            'total_clients' => \App\Models\Client::count(),
            'total_equipment' => \App\Models\Equipment::count(),
            'total_consumables' => \App\Models\Consumable::count(),
            'total_personnel' => \App\Models\Personnel::count()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

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
        
        // Wizard Routes
        Route::prefix('wizard')->name('wizard.')->group(function () {
            Route::get('/step/{step?}/{inspection?}', [InspectionController::class, 'createWizard'])->name('step');
            Route::post('/save', [InspectionController::class, 'saveWizardStep'])->name('save');
            Route::post('/link-items', [InspectionController::class, 'linkItemsToEquipment'])->name('link-items');
        });
        
        // Equipment save route
        Route::post('/save-equipment', [InspectionController::class, 'saveEquipmentOnly'])->name('save-equipment');
        
        Route::post('/store', [InspectionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [InspectionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InspectionController::class, 'update'])->name('update');
        Route::delete('/{id}', [InspectionController::class, 'destroy'])->name('destroy');
        Route::post('/save-draft', [InspectionController::class, 'saveDraft'])->name('save-draft');
        Route::put('/update-draft/{id}', [InspectionController::class, 'updateDraft'])->name('update-draft');
        Route::get('/{id}', [InspectionController::class, 'show'])->name('show');
        Route::get('/{id}/pdf', [InspectionController::class, 'generatePDF'])->name('pdf');
        Route::get('/{id}/preview-pdf', [InspectionController::class, 'previewPDF'])->name('preview-pdf');
        
        // QA Management
        Route::patch('/{id}/complete', [InspectionController::class, 'markAsCompleted'])->name('complete');
        Route::patch('/{id}/submit-qa', [InspectionController::class, 'submitForQA'])->name('submit-qa');
        
        // Image Management
        Route::delete('/images/{imageId}', [InspectionController::class, 'deleteImage'])->name('images.delete');
        Route::patch('/images/{imageId}/caption', [InspectionController::class, 'updateImageCaption'])->name('images.caption');
        
        // API Routes for dropdowns
        Route::get('/api/personnel', [InspectionController::class, 'getPersonnel'])->name('api.personnel');
        Route::get('/api/inspectors', [InspectionController::class, 'getInspectors'])->name('api.inspectors');
        Route::get('/api/clients', [ClientController::class, 'getClients'])->name('api.clients');
        Route::get('/api/equipment', [EquipmentController::class, 'getEquipment'])->name('api.equipment');
        Route::get('/api/consumables', [ConsumableController::class, 'getConsumables'])->name('api.consumables');
        Route::get('/api/equipment/{id}/items', [EquipmentController::class, 'getEquipmentItems'])->name('api.equipment.items');
        
        // Draft management API routes
        Route::post('/api/drafts/save', [InspectionController::class, 'saveDraft'])->name('api.drafts.save');
        Route::get('/api/drafts/{draftId}', [InspectionController::class, 'getDraft'])->name('api.drafts.get');
        Route::delete('/api/drafts/{draftId}', [InspectionController::class, 'deleteDraft'])->name('api.drafts.delete');
        
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
        
        // Test client API specifically
        Route::get('/test-client-api', function () {
            return view('test-client-api');
        });
        
        // Client debug test page
        Route::get('/test-client-debug', function () {
            return view('test-client-debug');
        });
        
        // Test inspection form without errors
        Route::get('/test-form-js', function () {
            // Create a dummy inspection for testing
            $inspection = new \App\Models\Inspection();
            $inspection->id = 999;
            $inspection->client_name = "Test Client";
            return view('inspections.edit', compact('inspection'));
        });
        
        // Minimal JS test
        Route::get('/test-js-minimal', function () {
            return view('test-js-minimal');
        });

        // Test route for form submission
        Route::get('/test-form-submission', function () {
            return view('test-form-submission');
        });
        
        // Test route for AJAX submission
        Route::get('/test-ajax-submission', function () {
            return view('test-ajax-submission');
        });
        
        // Simple form test
        Route::get('/simple-form-test', function () {
            return view('simple-form-test');
        });
        
                // Debug auth status
        Route::get('/debug-auth', function () {
            return response()->json([
                'authenticated' => auth()->check(),
                'user' => auth()->user() ? auth()->user()->email : null,
                'session_id' => session()->getId(),
                'csrf_token' => csrf_token()
            ]);
        });
        
        // Test route for direct service creation (bypassing auth for testing)
        Route::post('/test-store', [InspectionController::class, 'store'])->withoutMiddleware(['auth']);
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
        
        // Test route to check personnel data in wizard
        Route::get('/test/personnel-data', function () {
            try {
                $personnel = \App\Models\Personnel::where('is_active', true)->orderBy('first_name')->get();
                $clients = \App\Models\Client::where('is_active', true)->orderBy('client_name')->get();
                
                return response()->json([
                    'personnel_count' => $personnel->count(),
                    'personnel_data' => $personnel->toArray(),
                    'clients_count' => $clients->count(),
                    'clients_data' => $clients->toArray(),
                    'personnel_fields' => $personnel->first() ? array_keys($personnel->first()->toArray()) : [],
                    'expected_fields' => ['id', 'first_name', 'last_name', 'position', 'department', 'employee_id', 'email', 'phone', 'is_active']
                ]);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
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
    
    // QA Management Routes (QA users only)
    Route::middleware('qa')->prefix('qa')->name('qa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\QAController::class, 'dashboard'])->name('dashboard');
        Route::get('/pending', [\App\Http\Controllers\QAController::class, 'pending'])->name('pending');
        Route::get('/under-review', [\App\Http\Controllers\QAController::class, 'underReview'])->name('under-review');
        Route::get('/history', [\App\Http\Controllers\QAController::class, 'history'])->name('history');
        Route::get('/review/{inspection}', [\App\Http\Controllers\QAController::class, 'review'])->name('review');
        Route::patch('/approve/{inspection}', [\App\Http\Controllers\QAController::class, 'approve'])->name('approve');
        Route::patch('/reject/{inspection}', [\App\Http\Controllers\QAController::class, 'reject'])->name('reject');
        Route::patch('/request-revision/{inspection}', [\App\Http\Controllers\QAController::class, 'requestRevision'])->name('request-revision');
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
    

    
    // Debug route for equipment API
    Route::get('/debug/equipment', function() {
        try {
            $equipment = \App\Models\Equipment::active()->get();
            return response()->json([
                'success' => true,
                'count' => $equipment->count(),
                'data' => $equipment->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
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

// Debug route to test API endpoints
Route::get('/debug-api', function() {
    $html = '<h2>API Debug</h2>';
    
    // Test equipment API
    $html .= '<h3>Equipment API Test</h3>';
    $html .= '<a href="' . url('/inspections/api/equipment') . '" target="_blank">Test Equipment API</a><br>';
    
    // Test base URL generation
    $html .= '<h3>Base URL Test</h3>';
    $html .= '<p>Laravel url(\'/\'): ' . url('/') . '</p>';
    $html .= '<p>Request URL: ' . request()->url() . '</p>';
    $html .= '<p>App URL: ' . config('app.url') . '</p>';
    
    // Test equipment data directly
    $html .= '<h3>Direct Equipment Query</h3>';
    try {
        $equipment = \App\Models\Equipment::active()->assets()->get();
        $html .= '<p>Equipment count: ' . $equipment->count() . '</p>';
        $html .= '<pre>' . json_encode($equipment->toArray(), JSON_PRETTY_PRINT) . '</pre>';
    } catch (\Exception $e) {
        $html .= '<p>Error: ' . $e->getMessage() . '</p>';
    }
    
    return $html;
});

// Test route for equipment modal
Route::get('/test-equipment-modal', function () {
    return view('test-equipment-modal');
});
