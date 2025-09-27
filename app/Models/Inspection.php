<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        // Basic Information
        'inspection_number',
        'client_id',
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
        'rig',
        'report_number',
        'revision',
        
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
        
        // QA Workflow fields
        'qa_status',
        'qa_reviewer_id',
        'qa_reviewed_at',
        'qa_comments',
        'qa_rejection_reason',
        
        // Completion tracking - temporarily disabled
        // 'created_by',
        // 'completed_at', 
        // 'completed_by',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'last_inspection_date' => 'date',
        'next_inspection_due' => 'date',
        'next_inspection_date' => 'date',
        'report_date' => 'date',
        'completed_at' => 'datetime',
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
     * Ensure attachments is always an array
     */
    public function getAttachmentsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /**
     * Ensure service_notes is always an array
     */
    public function getServiceNotesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /**
     * Get the services for the inspection.
     */
    public function services()
    {
        // Prefer the dedicated 'services' table/model if present, otherwise fallback
        try {
            if (\Schema::hasTable('services')) {
                return $this->hasMany(Service::class, 'inspection_id');
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }
        return $this->hasMany(InspectionService::class, 'inspection_id');
    }

    /**
     * Get the lifting examination data for this inspection.
     */
    public function liftingExamination()
    {
        return $this->hasOne(LiftingExamination::class);
    }

    /**
     * Get the MPI inspection data for this inspection.
     */
    public function mpiInspection()
    {
        return $this->hasOne(MpiInspection::class);
    }

    /**
     * Get the QA reviewer for this inspection.
     */
    public function qaReviewer()
    {
        return $this->belongsTo(User::class, 'qa_reviewer_id');
    }

    /**
     * Get the personnel assignments for the inspection.
     */
    public function personnelAssignments()
    {
        return $this->hasMany(PersonnelAssignment::class);
    }

    /**
     * Get the equipment assignments for the inspection.
     */
    public function equipmentAssignments()
    {
        return $this->hasMany(EquipmentAssignment::class);
    }

    /**
     * Get the inspection equipment for the inspection (Step 4 data).
     */
    public function inspectionEquipment()
    {
        return $this->hasMany(InspectionEquipment::class);
    }

    /**
     * Get the consumable assignments for the inspection.
     */
    public function consumableAssignments()
    {
        return $this->hasMany(ConsumableAssignment::class);
    }

    /**
     * Get the equipment based on equipment_type field (if it's an ID).
     */
    public function equipmentType()
    {
        return $this->belongsTo(Equipment::class, 'equipment_type');
    }

    /**
     * Get the inspection results for the inspection.
     */
    public function inspectionResults()
    {
        return $this->hasMany(InspectionResult::class);
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

    /**
     * Get the client for this inspection
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the lifting examination inspector
     */
    public function liftingExaminationInspector()
    {
        return $this->belongsTo(Personnel::class, 'lifting_examination_inspector');
    }

    /**
     * Get the load test inspector
     */
    public function loadTestInspector()
    {
        return $this->belongsTo(Personnel::class, 'load_test_inspector');
    }

    /**
     * Get the thorough examination inspector
     */
    public function thoroughExaminationInspector()
    {
        return $this->belongsTo(Personnel::class, 'thorough_examination_inspector');
    }

    /**
     * Get the MPI service inspector
     */
    public function mpiServiceInspector()
    {
        return $this->belongsTo(Personnel::class, 'mpi_service_inspector');
    }

    /**
     * Get the visual inspector
     */
    public function visualInspector()
    {
        return $this->belongsTo(Personnel::class, 'visual_inspector');
    }

    /**
     * EMERGENCY FIX: Completely removed images functionality
     * This method is temporarily disabled to prevent relationship errors
     */
    // public function images() - DISABLED

    /**
     * EMERGENCY FIX: Completely removed images_for_edit functionality  
     * This attribute is temporarily disabled to prevent relationship errors
     */
    // public function getImagesForEditAttribute() - DISABLED
    
    /**
     * Get all images for this inspection
     */
    public function images()
    {
        try {
            if (!\Schema::hasTable('inspection_images')) {
                return new \Illuminate\Support\Collection();
            }
            return $this->hasMany(InspectionImage::class)->ordered();
        } catch (\Exception $e) {
            return new \Illuminate\Support\Collection();
        }
    }

    /**
     * Get images formatted for edit interface
     */
    public function getImagesForEditAttribute()
    {
        try {
            if (!\Schema::hasTable('inspection_images')) {
                return new \Illuminate\Support\Collection();
            }
            
            $images = $this->images;
            if ($images instanceof \Illuminate\Database\Eloquent\Collection) {
                return $images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'original_name' => $image->original_name,
                        'file_path' => $image->file_path,
                        'caption' => $image->caption,
                        'file_size' => $image->file_size,
                        'formatted_size' => $image->formatted_size,
                        'url' => $image->url
                    ];
                });
            }
            return new \Illuminate\Support\Collection();
        } catch (\Exception $e) {
            return new \Illuminate\Support\Collection();
        }
    }

    /**
     * Get the user who created this inspection
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who completed this inspection
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // QA Workflow Methods
    
    /**
     * Submit inspection for QA review
     */
    public function submitForQA()
    {
        $this->update([
            'status' => 'submitted_for_qa',
            'qa_status' => 'pending_qa'
        ]);
    }

    /**
     * Assign QA reviewer and start review
     */
    public function startQAReview(User $qaReviewer)
    {
        $this->update([
            'status' => 'under_qa_review',
            'qa_status' => 'under_qa_review',
            'qa_reviewer_id' => $qaReviewer->id
        ]);
    }

    /**
     * Approve inspection after QA review
     */
    public function approveQA(User $qaReviewer, string $comments = null)
    {
        $this->update([
            'status' => 'qa_approved',
            'qa_status' => 'qa_approved',
            'qa_reviewer_id' => $qaReviewer->id,
            'qa_reviewed_at' => now(),
            'qa_comments' => $comments
        ]);
    }

    /**
     * Reject inspection after QA review
     */
    public function rejectQA(User $qaReviewer, string $reason, string $comments = null)
    {
        $this->update([
            'status' => 'qa_rejected',
            'qa_status' => 'qa_rejected',
            'qa_reviewer_id' => $qaReviewer->id,
            'qa_reviewed_at' => now(),
            'qa_rejection_reason' => $reason,
            'qa_comments' => $comments
        ]);
    }

    /**
     * Mark inspection as requiring revision
     */
    public function requireRevision(User $qaReviewer, string $reason, string $comments = null)
    {
        $this->update([
            'status' => 'revision_required',
            'qa_status' => 'revision_required',
            'qa_reviewer_id' => $qaReviewer->id,
            'qa_reviewed_at' => now(),
            'qa_rejection_reason' => $reason,
            'qa_comments' => $comments
        ]);
    }

    /**
     * Complete inspection (after QA approval)
     */
    public function markAsCompleted(User $user = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_by' => $user ? $user->id : auth()->id(),
            'completed_at' => now()
        ]);
    }

    /**
     * Check if inspection is pending QA review
     */
    public function isPendingQA(): bool
    {
        return $this->qa_status === 'pending_qa';
    }

    /**
     * Check if inspection is under QA review
     */
    public function isUnderQAReview(): bool
    {
        return $this->qa_status === 'under_qa_review';
    }

    /**
     * Check if inspection is QA approved
     */
    public function isQAApproved(): bool
    {
        return $this->qa_status === 'qa_approved';
    }

    /**
     * Check if inspection is QA rejected
     */
    public function isQARejeected(): bool
    {
        return $this->qa_status === 'qa_rejected';
    }

    /**
     * Check if inspection requires revision
     */
    public function requiresRevision(): bool
    {
        return $this->qa_status === 'revision_required';
    }

    /**
     * Get QA status badge color
     */
    public function getQAStatusColorAttribute(): string
    {
        return match($this->qa_status) {
            'pending_qa' => 'warning',
            'under_qa_review' => 'info',
            'qa_approved' => 'success',
            'qa_rejected' => 'danger',
            'revision_required' => 'secondary',
            default => 'light'
        };
    }

    /**
     * Get QA status display name
     */
    public function getQAStatusNameAttribute(): string
    {
        return match($this->qa_status) {
            'pending_qa' => 'Pending QA Review',
            'under_qa_review' => 'Under QA Review',
            'qa_approved' => 'QA Approved',
            'qa_rejected' => 'QA Rejected',
            'revision_required' => 'Revision Required',
            default => 'Unknown'
        };
    }

    // NO OTHER IMAGE METHODS - KEEPING IT SIMPLE
}
