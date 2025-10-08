<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\User;
use App\Models\Client;
use App\Models\Inspection;
use App\Models\LiftingExamination;
use App\Models\MpiInspection;

echo "=== DATABASE STATUS AFTER SEEDING ===\n\n";

try {
    // Check Users
    $userCount = User::count();
    echo "👤 USERS: $userCount\n";
    if ($userCount > 0) {
        User::all()->each(function($user) {
            echo "   - {$user->name} ({$user->email}) - {$user->role}\n";
        });
    }
    echo "\n";

    // Check Clients
    $clientCount = Client::count();
    echo "🏢 CLIENTS: $clientCount\n";
    if ($clientCount > 0) {
        Client::take(5)->get()->each(function($client) {
            echo "   - {$client->name} ({$client->email})\n";
        });
        if ($clientCount > 5) echo "   ... and " . ($clientCount - 5) . " more\n";
    }
    echo "\n";

    // Check Inspections
    $inspectionCount = Inspection::count();
    echo "🔍 INSPECTIONS: $inspectionCount\n";
    if ($inspectionCount > 0) {
        Inspection::take(5)->get()->each(function($inspection) {
            echo "   - {$inspection->inspection_number} - {$inspection->status}\n";
        });
        if ($inspectionCount > 5) echo "   ... and " . ($inspectionCount - 5) . " more\n";
    }
    echo "\n";

    // Check Service Tables
    $liftingCount = LiftingExamination::count();
    $mpiCount = MpiInspection::count();
    echo "🔧 SERVICE RECORDS:\n";
    echo "   - Lifting Examinations: $liftingCount\n";
    echo "   - MPI Inspections: $mpiCount\n";
    echo "\n";

    echo "✅ Database is ready for testing!\n\n";

    if ($userCount > 0) {
        echo "🔑 LOGIN CREDENTIALS:\n";
        echo "   Super Admin: admin@inspectionservices.com / admin123\n";
        echo "   Admin: admin@company.com / password\n";
        echo "   Inspector: inspector@company.com / password\n";
    }

} catch (Exception $e) {
    echo "❌ Error checking database: " . $e->getMessage() . "\n";
}

echo "\n=== STATUS COMPLETE ===\n";
