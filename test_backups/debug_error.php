<?php

echo "Debugging addEagerConstraints Error\n";
echo "=====================================\n\n";

try {
    // Bootstrap Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Laravel bootstrapped\n";
    
    // Test 1: Try to load an inspection
    echo "\nTest 1: Loading inspection...\n";
    $inspection = App\Models\Inspection::first();
    
    if (!$inspection) {
        echo "❌ No inspections found\n";
        exit(1);
    }
    
    echo "✅ Inspection loaded: {$inspection->id}\n";
    
    // Test 2: Check what methods exist on the model
    echo "\nTest 2: Checking available methods...\n";
    $methods = get_class_methods($inspection);
    $imageMethods = array_filter($methods, function($method) {
        return strpos(strtolower($method), 'image') !== false;
    });
    
    echo "Image-related methods: " . implode(', ', $imageMethods) . "\n";
    
    // Test 3: Try to access different properties
    echo "\nTest 3: Testing property access...\n";
    
    try {
        $attr = $inspection->inspection_images;
        echo "✅ inspection_images attribute works\n";
    } catch (Exception $e) {
        echo "❌ inspection_images error: " . $e->getMessage() . "\n";
    }
    
    // Test 4: Check if there are any relationships defined
    echo "\nTest 4: Checking relationships...\n";
    $reflection = new ReflectionClass($inspection);
    $relationMethods = [];
    
    foreach ($reflection->getMethods() as $method) {
        if ($method->class === get_class($inspection) && 
            $method->isPublic() && 
            !$method->isStatic() &&
            strpos($method->name, 'get') !== 0 &&
            strpos($method->name, 'set') !== 0) {
            
            try {
                $result = $method->invoke($inspection);
                if ($result instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $relationMethods[] = $method->name;
                }
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'addEagerConstraints') !== false) {
                    echo "❌ Found problematic relationship: {$method->name}\n";
                    echo "   Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "Relationship methods: " . implode(', ', $relationMethods) . "\n";
    
    echo "\n✅ Debugging completed\n";
    
} catch (Exception $e) {
    echo "❌ Error during debugging: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
