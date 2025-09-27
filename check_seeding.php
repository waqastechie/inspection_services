<?php
require_once 'vendor/autoload.php';

// Load Laravel configuration
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Client;
use App\Models\Personnel;
use App\Models\Equipment;
use App\Models\Consumable;
use App\Models\Inspection;

try {
    echo "Database Seeding Verification:\n";
    echo "=============================\n";
    
    echo "Users: " . User::count() . "\n";
    echo "Clients: " . Client::count() . "\n";
    echo "Personnel: " . Personnel::count() . "\n";
    echo "Equipment: " . Equipment::count() . "\n";
    echo "Consumables: " . Consumable::count() . "\n";
    echo "Inspections: " . Inspection::count() . "\n";
    
    echo "\nUser Details:\n";
    foreach (User::all() as $user) {
        echo "- {$user->name} ({$user->email}) - Role: {$user->role}\n";
    }
    
    echo "\nInspection Status Summary:\n";
    $statuses = Inspection::select('status')->groupBy('status')->get();
    foreach ($statuses as $status) {
        $count = Inspection::where('status', $status->status)->count();
        echo "- {$status->status}: {$count}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
