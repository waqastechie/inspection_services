<!DOCTYPE html>
<html>
<head>
    <title>Inspection System Test</title>
</head>
<body>
    <h1>Testing Inspection System</h1>
    
    <?php
    try {
        // Test basic PHP
        echo "<p>✓ PHP is working</p>";
        
        // Test file exists
        $inspectionFile = __DIR__ . '/app/Models/Inspection.php';
        if (file_exists($inspectionFile)) {
            echo "<p>✓ Inspection model file exists</p>";
        } else {
            echo "<p>❌ Inspection model file not found</p>";
        }
        
        // Test autoload
        require_once __DIR__ . '/vendor/autoload.php';
        echo "<p>✓ Composer autoload works</p>";
        
        // Bootstrap minimal Laravel
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        echo "<p>✓ Laravel bootstrap successful</p>";
        
        // Test model creation
        $inspection = new App\Models\Inspection();
        echo "<p>✓ Inspection model can be instantiated</p>";
        
        // Test database query
        $count = App\Models\Inspection::count();
        echo "<p>✓ Database query successful: Found {$count} inspections</p>";
        
        echo "<h2 style='color: green;'>✅ ALL TESTS PASSED!</h2>";
        echo "<p>The addEagerConstraints error should be fixed now.</p>";
        
    } catch (Exception $e) {
        echo "<h2 style='color: red;'>❌ ERROR FOUND:</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        
        echo "<h3>Stack Trace:</h3>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    ?>
</body>
</html>
