<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Temporary safe wrapper for Inspection model
 * This prevents any relationship errors until the inspection_images table is created
 */
trait SafeImageHandling
{
    /**
     * Safe images method that never fails
     */
    public function images()
    {
        return collect(); // Always return empty collection for now
    }
    
    /**
     * Safe images_for_edit attribute
     */
    public function getImagesForEditAttribute()
    {
        return collect(); // Always return empty collection for now
    }
    
    /**
     * Check if images table exists
     */
    public function hasImagesTable()
    {
        try {
            return \Schema::hasTable('inspection_images');
        } catch (\Exception $e) {
            return false;
        }
    }
}
