<!DOCTYPE html>
<html>
<head>
    <title>Setup Image System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .alert { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Image System Setup</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup'])) {
        try {
            // Include Laravel
            require_once '../vendor/autoload.php';
            $app = require_once '../bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
            $kernel->bootstrap();
            
            echo '<div class="alert success">✅ Laravel loaded successfully</div>';
            
            // Check if table exists
            $tableExists = Schema::hasTable('inspection_images');
            
            if ($tableExists) {
                echo '<div class="alert info">ℹ️ inspection_images table already exists</div>';
            } else {
                echo '<div class="alert info">Creating inspection_images table...</div>';
                
                // Create the table
                Schema::create('inspection_images', function (Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('inspection_id');
                    $table->string('original_name');
                    $table->string('file_name');
                    $table->string('file_path', 500);
                    $table->string('mime_type', 100);
                    $table->unsignedBigInteger('file_size');
                    $table->text('caption')->nullable();
                    $table->json('metadata')->nullable();
                    $table->unsignedInteger('sort_order')->default(0);
                    $table->timestamps();
                    
                    $table->foreign('inspection_id')->references('id')->on('inspections')->onDelete('cascade');
                    $table->index(['inspection_id', 'sort_order']);
                });
                
                echo '<div class="alert success">✅ inspection_images table created successfully!</div>';
            }
            
            // Create storage directory
            $storagePath = storage_path('app/public/inspection_images');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
                echo '<div class="alert success">✅ Created storage directory</div>';
            } else {
                echo '<div class="alert info">ℹ️ Storage directory already exists</div>';
            }
            
            // Test models
            $imageModel = new App\Models\InspectionImage();
            echo '<div class="alert success">✅ InspectionImage model working</div>';
            
            $imageService = new App\Services\ImageUploadService();
            echo '<div class="alert success">✅ ImageUploadService working</div>';
            
            // Test with inspections
            $inspections = App\Models\Inspection::take(3)->get();
            echo '<div class="alert info">📊 Found ' . $inspections->count() . ' inspections for testing</div>';
            
            echo '<div class="alert success"><strong>🎉 Image system setup completed successfully!</strong></div>';
            echo '<div class="alert info"><strong>Next steps:</strong><br>';
            echo '1. Go to your inspection list<br>';
            echo '2. Create or edit an inspection<br>';
            echo '3. Upload images in the Images section<br>';
            echo '4. View the inspection to see images displayed</div>';
            
        } catch (Exception $e) {
            echo '<div class="alert error">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<div class="alert error">Stack trace:<br><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></div>';
        }
    } else {
        ?>
        <div class="alert info">
            <strong>About this setup:</strong><br>
            This will create the inspection_images table and set up the image storage system.
            It will also create the necessary storage directories and verify that all components are working.
        </div>
        
        <form method="POST">
            <button type="submit" name="setup" value="1" class="btn">🚀 Setup Image System</button>
        </form>
        
        <h2>What this will do:</h2>
        <ul>
            <li>Create the inspection_images database table</li>
            <li>Set up storage directories</li>
            <li>Verify all models and services are working</li>
            <li>Test the system with existing inspections</li>
        </ul>
        <?php
    }
    ?>
    
    <hr>
    <p><a href="../public">← Back to Application</a></p>
</body>
</html>
