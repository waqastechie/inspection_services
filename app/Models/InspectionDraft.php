<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InspectionDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'draft_id',
        'form_data',
        'selected_services',
        'personnel_assignments',
        'equipment_assignments',
        'consumable_assignments',
        'uploaded_images',
        'service_sections_data',
        'user_session',
        'ip_address',
        'last_saved_at',
        'is_submitted',
        'version'
    ];

    protected $casts = [
        'selected_services' => 'array',
        'personnel_assignments' => 'array',
        'equipment_assignments' => 'array',
        'consumable_assignments' => 'array',
        'uploaded_images' => 'array',
        'service_sections_data' => 'array',
        'last_saved_at' => 'datetime',
        'is_submitted' => 'boolean'
    ];

    /**
     * Generate a unique draft ID
     */
    public static function generateDraftId(): string
    {
        do {
            $draftId = 'DRAFT_' . date('Ymd') . '_' . Str::random(8);
        } while (static::where('draft_id', $draftId)->exists());
        
        return $draftId;
    }

    /**
     * Find draft by ID and session
     */
    public static function findByDraftId($draftId, $session = null)
    {
        $query = static::where('draft_id', $draftId)
                      ->where('is_submitted', false);
        
        if ($session) {
            $query->where('user_session', $session);
        }
        
        return $query->first();
    }

    /**
     * Clean old drafts (older than 7 days)
     */
    public static function cleanOldDrafts()
    {
        static::where('last_saved_at', '<', now()->subDays(7))->delete();
    }

    /**
     * Mark as submitted
     */
    public function markAsSubmitted()
    {
        $this->update(['is_submitted' => true]);
    }
}
