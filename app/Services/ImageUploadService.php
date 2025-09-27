<?php

namespace App\Services;

use App\Models\Inspection;
use App\Models\InspectionImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Store images for a new inspection
     */
    public function storeImages(Inspection $inspection, array $imageData): array
    {
        // Check if table exists
        if (!\Schema::hasTable('inspection_images')) {
            Log::warning('inspection_images table does not exist, cannot store images');
            return [];
        }

        Log::info('Storing images for inspection ' . $inspection->id . ', received ' . count($imageData) . ' images');
        $savedImages = [];
        
        foreach ($imageData as $index => $imageInfo) {
            Log::info('Processing image ' . $index . ': ' . json_encode($imageInfo));
            
            // Handle base64 encoded images
            if (is_string($imageInfo)) {
                $decoded = json_decode($imageInfo, true);
                if ($decoded && isset($decoded['dataUrl'])) {
                    $imageInfo = $decoded;
                } else {
                    Log::warning('Invalid image string format at index ' . $index);
                    continue;
                }
            }

            if (!isset($imageInfo['dataUrl'])) {
                Log::warning('No dataUrl found in image data at index ' . $index);
                continue;
            }

            try {
                $savedImage = $this->saveBase64Image(
                    $inspection, 
                    $imageInfo['dataUrl'], 
                    $imageInfo['name'] ?? 'Image ' . ($index + 1),
                    $imageInfo['caption'] ?? '',
                    $index
                );
                
                if ($savedImage) {
                    $savedImages[] = $savedImage;
                    Log::info('Successfully saved image with ID: ' . $savedImage->id);
                }
            } catch (\Exception $e) {
                Log::error('Failed to save image at index ' . $index . ': ' . $e->getMessage());
                continue;
            }
        }
        
        Log::info('Stored ' . count($savedImages) . ' images successfully');
        return $savedImages;
    }

    /**
     * Update images for an existing inspection
     */
    public function updateImages(Inspection $inspection, array $imageData): array
    {
        // Delete existing images first
        $this->deleteInspectionImages($inspection);
        
        // Store new images
        return $this->storeImages($inspection, $imageData);
    }

    /**
     * Save a base64 image to the database and storage
     */
    public function saveBase64Image(Inspection $inspection, string $dataUrl, string $originalName, string $caption = '', int $sortOrder = 0): ?InspectionImage
    {
        try {
            // Extract the base64 data
            if (!preg_match('/^data:image\/([a-zA-Z]+);base64,(.+)$/', $dataUrl, $matches)) {
                return null;
            }

            $imageType = $matches[1];
            $imageData = base64_decode($matches[2]);
            
            if ($imageData === false) {
                return null;
            }

            // Generate unique filename
            $fileName = 'inspection_' . $inspection->id . '_' . time() . '_' . Str::random(8) . '.' . $imageType;
            $filePath = 'inspections/images/' . $fileName;

            // Store the file
            Storage::disk('public')->put($filePath, $imageData);

            // Create database record
            $inspectionImage = InspectionImage::create([
                'inspection_id' => $inspection->id,
                'original_name' => $originalName,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'mime_type' => 'image/' . $imageType,
                'file_size' => strlen($imageData),
                'caption' => $caption,
                'sort_order' => $sortOrder,
                'metadata' => [
                    'uploaded_at' => now()->toISOString(),
                    'original_data_url_length' => strlen($dataUrl)
                ]
            ]);

            return $inspectionImage;

        } catch (\Exception $e) {
            Log::error('Error saving base64 image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get images formatted for edit interface
     */
    public function getImagesForEdit(Inspection $inspection): array
    {
        try {
            if (!\Schema::hasTable('inspection_images')) {
                return [];
            }

            $images = $inspection->images;

            return $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'name' => $image->original_name,
                    'original_name' => $image->original_name,
                    'file_path' => $image->file_path,
                    'caption' => $image->caption,
                    'file_size' => $image->file_size,
                    'formatted_size' => $image->formatted_size,
                    'url' => Storage::disk('public')->url($image->file_path),
                    'dataUrl' => Storage::disk('public')->url($image->file_path)
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Delete all images for an inspection
     */
    public function deleteInspectionImages(Inspection $inspection): void
    {
        try {
            // Check if table exists before attempting operations
            if (!\Schema::hasTable('inspection_images')) {
                return;
            }
            
            // Get existing images
            $images = $inspection->images;
            
            foreach ($images as $image) {
                // Delete file from storage
                if (Storage::disk('public')->exists($image->file_path)) {
                    Storage::disk('public')->delete($image->file_path);
                }
                
                // Delete database record
                $image->delete();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete inspection images: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific image
     */
    public function deleteImage(InspectionImage $image): bool
    {
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($image->file_path)) {
                Storage::disk('public')->delete($image->file_path);
            }

            // Delete database record
            $image->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return false;
        }
    }
}
