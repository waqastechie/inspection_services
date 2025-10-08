<?php

require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Database connection
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== SERVICE TABLES STRUCTURE CHECK ===\n\n";

    // Define service tables to check
    $serviceTables = [
        'load_tests' => [
            'duration_held', 'two_points_diagonal', 'four_points', 'deflection', 
            'deformation', 'distance_from_ground', 'result', 'test_load', 
            'safe_working_load', 'test_method', 'test_equipment', 'test_conditions'
        ],
        'mpi_inspections' => [
            'mpi_service_inspector', 'visual_comments', 'visual_inspector',
            'visual_method', 'visual_lighting', 'visual_equipment', 'visual_conditions',
            'visual_results', 'mpi_results'
        ],
        'lifting_examinations' => [
            'lifting_examination_inspector', 'thorough_examination_comments',
            'thorough_examination_inspector', 'thorough_method', 'thorough_equipment',
            'thorough_conditions', 'thorough_results', 'first_examination', 'equipment_defect'
        ],
        'other_tests' => [
            'other_test_inspector', 'other_test_method', 'other_test_equipment',
            'other_test_conditions', 'other_test_results', 'other_test_comments'
        ]
    ];

    foreach ($serviceTables as $tableName => $expectedColumns) {
        echo "=== CHECKING TABLE: $tableName ===\n";
        
        // Check if table exists
        $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
        if ($stmt->rowCount() == 0) {
            echo "❌ Table $tableName does not exist!\n\n";
            continue;
        }

        echo "✅ Table $tableName exists\n";

        // Get current columns
        $stmt = $pdo->query("DESCRIBE $tableName");
        $currentColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $currentColumnNames = array_column($currentColumns, 'Field');
        
        echo "Current columns (" . count($currentColumns) . "):\n";
        foreach ($currentColumns as $column) {
            echo "  - {$column['Field']} ({$column['Type']})\n";
        }
        
        echo "\nChecking expected columns:\n";
        $missingColumns = [];
        foreach ($expectedColumns as $expectedColumn) {
            if (in_array($expectedColumn, $currentColumnNames)) {
                echo "  ✅ $expectedColumn - EXISTS\n";
            } else {
                echo "  ❌ $expectedColumn - MISSING\n";
                $missingColumns[] = $expectedColumn;
            }
        }
        
        if (!empty($missingColumns)) {
            echo "\n🔴 MISSING COLUMNS IN $tableName: " . implode(', ', $missingColumns) . "\n";
        } else {
            echo "\n🟢 All expected columns present in $tableName\n";
        }
        
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }

    // Check migration files for each service
    echo "=== CHECKING MIGRATION FILES ===\n";
    $migrationDir = 'database/migrations';
    
    foreach (array_keys($serviceTables) as $tableName) {
        $migrationFiles = glob($migrationDir . '/*' . $tableName . '*');
        echo "$tableName migrations:\n";
        if (empty($migrationFiles)) {
            echo "  ❌ No migration files found\n";
        } else {
            foreach ($migrationFiles as $file) {
                echo "  - " . basename($file) . "\n";
            }
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "=== CHECK COMPLETE ===\n";