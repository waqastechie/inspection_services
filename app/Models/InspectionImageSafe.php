<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionImageSafe extends Model
{
    use HasFactory;

    protected $table = 'inspection_images';
    
    protected $fillable = [
        'inspection_id',
        'original_name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'caption',
        'metadata',
        'sort_order',
    ];

    protected $casts = [
        'metadata' => 'array',
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the inspection that owns the image
     */
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the full URL to the image
     */
    public function getUrlAttribute(): string
    {
        return asset($this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * Scope to order images by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Check if table exists before any operations
     */
    public static function tableExists()
    {
        try {
            return \Schema::hasTable('inspection_images');
        } catch (\Exception $e) {
            return false;
        }
    }
}
