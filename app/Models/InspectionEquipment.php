<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionEquipment extends Model
{
    use HasFactory;

    protected $table = 'inspection_equipment';

    protected $fillable = [
        'inspection_id',
        'client_id',
        'equipment_type_id',
        'parent_equipment_id',
        'category',
        'equipment_type',
        'serial_number',
        'description',
        'reason_for_examination',
        'model',
        'swl',
        'test_load_applied',
        'date_of_manufacture',
        'date_of_last_examination',
        'date_of_next_examination',
        'status',
        'remarks',
        'condition',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'swl' => 'decimal:2',
        'test_load_applied' => 'decimal:2',
        'date_of_manufacture' => 'date',
        'date_of_last_examination' => 'date',
        'date_of_next_examination' => 'date',
    ];

    /**
     * Relationship to Inspection
     */
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Relationship to Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relationship to Equipment Type
     */
    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    /**
     * Scope for assets only
     */
    public function scopeAssets($query)
    {
        return $query->where('category', 'asset');
    }

    /**
     * Scope for items only
     */
    public function scopeItems($query)
    {
        return $query->where('category', 'item');
    }

    /**
     * Relationship to parent equipment (for items)
     */
    public function parentEquipment()
    {
        return $this->belongsTo(InspectionEquipment::class, 'parent_equipment_id');
    }

    /**
     * Relationship to child items (for equipment)
     */
    public function childItems()
    {
        return $this->hasMany(InspectionEquipment::class, 'parent_equipment_id')->where('category', 'item');
    }

    /**
     * Scope for equipment with their related items
     */
    public function scopeWithItems($query)
    {
        return $query->with('childItems');
    }
}
