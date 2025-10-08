<?php

echo "Creating inspection_images table...\n";

try {
    // Bootstrap Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();

    // Check if table already exists
    if (Schema::hasTable('inspection_images')) {
        echo "✅ inspection_images table already exists\n";
        exit(0);
    }

    echo "Creating inspection_images table manually...\n";

    // Create the table using Schema builder
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

    echo "✅ inspection_images table created successfully!\n";

    // Verify table was created
    if (Schema::hasTable('inspection_images')) {
        echo "✅ Table verification successful\n";
        
        // Show table columns
        $columns = Schema::getColumnListing('inspection_images');
        echo "Table columns: " . implode(', ', $columns) . "\n";
    } else {
        echo "❌ Table verification failed\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
