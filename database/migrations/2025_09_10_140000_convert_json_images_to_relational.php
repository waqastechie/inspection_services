<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Inspection;
use App\Models\InspectionImage;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations to convert existing JSON image data to the new format.
     */
    public function up(): void
    {
        // First ensure the inspection_images table exists
        if (!Schema::hasTable('inspection_images')) {
            Schema::create('inspection_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inspection_id')->constrained('inspections')->onDelete('cascade');
                $table->string('original_name');
                $table->string('file_name');
                $table->string('file_path', 500);
                $table->string('mime_type', 100);
                $table->unsignedBigInteger('file_size');
                $table->text('caption')->nullable();
                $table->json('metadata')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
                
                $table->index(['inspection_id', 'sort_order']);
            });
        }
        
        // Convert existing JSON image data
        $inspections = Inspection::whereNotNull('inspection_images')->get();
        
        foreach ($inspections as $inspection) {
            $this->convertInspectionImages($inspection);
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't actually drop the table in down() to prevent data loss
        // Schema::dropIfExists('inspection_images');
    }
    
    /**
     * Convert JSON image data to the new relational format
     */
    private function convertInspectionImages(Inspection $inspection)
    {
        $jsonImages = $inspection->inspection_images;
        
        if (empty($jsonImages)) {
            return;
        }
        
        // Decode JSON if it's a string
        if (is_string($jsonImages)) {
            $jsonImages = json_decode($jsonImages, true);
        }
        
        if (!is_array($jsonImages)) {
            return;
        }
        
        $sortOrder = 0;
        
        foreach ($jsonImages as $imageData) {
            try {
                $this->createImageRecord($inspection, $imageData, $sortOrder++);
            } catch (Exception $e) {
                // Log error but continue with other images
                \Log::warning("Failed to convert image for inspection {$inspection->id}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Create an InspectionImage record from JSON data
     */
    private function createImageRecord(Inspection $inspection, $imageData, int $sortOrder)
    {
        // Handle different formats of image data
        if (is_string($imageData)) {
            // Simple file path
            $this->createFromFilePath($inspection, $imageData, $sortOrder);
        } elseif (is_array($imageData)) {
            // Complex image object
            $this->createFromImageObject($inspection, $imageData, $sortOrder);
        }
    }
    
    /**
     * Create record from simple file path
     */
    private function createFromFilePath(Inspection $inspection, string $filePath, int $sortOrder)
    {
        $fileName = basename($filePath);
        $publicPath = public_path($filePath);
        
        // Estimate file size and type
        $fileSize = file_exists($publicPath) ? filesize($publicPath) : 0;
        $mimeType = $this->guessMimeType($fileName);
        
        InspectionImage::create([
            'inspection_id' => $inspection->id,
            'original_name' => $fileName,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'caption' => null,
            'metadata' => json_encode(['converted_from' => 'simple_path']),
            'sort_order' => $sortOrder,
        ]);
    }
    
    /**
     * Create record from complex image object
     */
    private function createFromImageObject(Inspection $inspection, array $imageData, int $sortOrder)
    {
        // Handle base64 data URL
        if (isset($imageData['dataUrl']) && strpos($imageData['dataUrl'], 'data:') === 0) {
            $this->createFromDataUrl($inspection, $imageData, $sortOrder);
            return;
        }
        
        // Handle regular URL or file path
        $url = $imageData['url'] ?? $imageData['dataUrl'] ?? '';
        $name = $imageData['name'] ?? basename($url);
        $caption = $imageData['caption'] ?? '';
        $size = $this->parseFileSize($imageData['size'] ?? '0');
        
        InspectionImage::create([
            'inspection_id' => $inspection->id,
            'original_name' => $name,
            'file_name' => basename($url),
            'file_path' => $url,
            'mime_type' => $this->guessMimeType($name),
            'file_size' => $size,
            'caption' => $caption ?: null,
            'metadata' => json_encode([
                'converted_from' => 'image_object',
                'original_data' => $imageData
            ]),
            'sort_order' => $sortOrder,
        ]);
    }
    
    /**
     * Create record from base64 data URL
     */
    private function createFromDataUrl(Inspection $inspection, array $imageData, int $sortOrder)
    {
        $dataUrl = $imageData['dataUrl'];
        $name = $imageData['name'] ?? 'image_' . time();
        $caption = $imageData['caption'] ?? '';
        
        // Parse data URL
        if (preg_match('/^data:([^;]+);base64,(.+)$/', $dataUrl, $matches)) {
            $mimeType = $matches[1];
            $base64Data = $matches[2];
            $binaryData = base64_decode($base64Data);
            
            // Generate file name
            $extension = $this->getExtensionFromMimeType($mimeType);
            $fileName = 'converted_' . $inspection->id . '_' . $sortOrder . '.' . $extension;
            
            // Save file to storage
            $filePath = 'inspection_images/' . $fileName;
            Storage::disk('public')->put($filePath, $binaryData);
            
            InspectionImage::create([
                'inspection_id' => $inspection->id,
                'original_name' => $name,
                'file_name' => $fileName,
                'file_path' => 'storage/' . $filePath,
                'mime_type' => $mimeType,
                'file_size' => strlen($binaryData),
                'caption' => $caption ?: null,
                'metadata' => json_encode([
                    'converted_from' => 'data_url',
                    'original_name' => $name
                ]),
                'sort_order' => $sortOrder,
            ]);
        }
    }
    
    /**
     * Guess MIME type from filename
     */
    private function guessMimeType(string $fileName): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
        ];
        
        return $mimeTypes[$extension] ?? 'image/jpeg';
    }
    
    /**
     * Get file extension from MIME type
     */
    private function getExtensionFromMimeType(string $mimeType): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];
        
        return $extensions[$mimeType] ?? 'jpg';
    }
    
    /**
     * Parse file size from various formats
     */
    private function parseFileSize(string $sizeString): int
    {
        if (is_numeric($sizeString)) {
            return (int) $sizeString;
        }
        
        // Handle formatted sizes like "2.5 MB"
        if (preg_match('/^([\d.]+)\s*(B|KB|MB|GB)$/i', trim($sizeString), $matches)) {
            $size = floatval($matches[1]);
            $unit = strtoupper($matches[2]);
            
            switch ($unit) {
                case 'GB': return (int) ($size * 1024 * 1024 * 1024);
                case 'MB': return (int) ($size * 1024 * 1024);
                case 'KB': return (int) ($size * 1024);
                default: return (int) $size;
            }
        }
        
        return 0;
    }
};
