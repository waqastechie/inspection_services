<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionSafe extends Model
{
    use HasFactory;

    protected $table = 'inspections';
    
    protected $fillable = [
        // Basic Information
        'inspection_number',
        'client_name',
        'project_name',
        'location',
        'area_of_examination',
        'services_performed',
        'contract',
        'work_order',
        'purchase_order',
        'client_job_reference',
        'job_ref',
        'standards',
        'local_procedure_number',
        'drawing_number',
        'test_restrictions',
        'surface_condition',
        'inspection_date',
        'weather_conditions',
        'temperature',
        'humidity',
        
        // Equipment Under Test
        'equipment_type',
        'equipment_description',
        'manufacturer',
        'model',
        'serial_number',
        'asset_tag',
        'manufacture_year',
        'capacity',
        'capacity_unit',
        
        // Certification & Standards
        'applicable_standard',
        'inspection_class',
        'certification_body',
        'previous_certificate_number',
        'last_inspection_date',
        'next_inspection_due',
        'next_inspection_date',
        
        // Test Results
        'overall_result',
        'defects_found',
        'recommendations',
        'limitations',
        
        // Inspector Information
        'lead_inspector_name',
        'lead_inspector_certification',
        'inspector_signature',
        'report_date',
        
        // Service Inspector Assignments
        'lifting_examination_inspector',
        'load_test_inspector',
        'thorough_examination_inspector',
        'mpi_service_inspector',
        'visual_inspector',
        
        // Additional Notes
        'general_notes',
        'inspector_comments',
        'service_notes',
        'attachments',
        'inspection_images',
        'status',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'last_inspection_date' => 'date',
        'next_inspection_due' => 'date',
        'next_inspection_date' => 'date',
        'report_date' => 'date',
        'inspection_images' => 'array',
        'attachments' => 'array',
        'service_notes' => 'array',
        'capacity' => 'decimal:2',
    ];

    /**
     * Ensure inspection_images is always an array
     */
    public function getInspectionImagesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /**
     * Ensure inspection_images is stored as JSON
     */
    public function setInspectionImagesAttribute($value)
    {
        $this->attributes['inspection_images'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Generate unique inspection number
     */
    public static function generateInspectionNumber(): string
    {
        $prefix = 'INS';
        $date = now()->format('Ymd');
        $lastInspection = static::whereDate('created_at', today())
            ->latest('id')
            ->first();
        
        $sequence = $lastInspection ? (int)substr($lastInspection->inspection_number, -3) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get inspection status with color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get overall result with color
     */
    public function getResultColorAttribute(): string
    {
        return match($this->overall_result) {
            'pass' => 'success',
            'fail' => 'danger',
            'conditional_pass' => 'warning',
            default => 'secondary'
        };
    }

    // NO IMAGE RELATIONSHIPS OR METHODS - COMPLETELY SAFE
}
