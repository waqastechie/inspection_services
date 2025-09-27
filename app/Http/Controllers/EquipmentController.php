<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipment = Equipment::with(['parentEquipment', 'items'])
            ->orderBy('equipment_category', 'asc') // Assets first, then items
            ->orderBy('name')
            ->paginate(20);
        return view('admin.equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.equipment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'brand_model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'calibration_date' => 'nullable|date',
            'calibration_due' => 'nullable|date|after:calibration_date',
            'calibration_certificate' => 'nullable|string|max:255',
            'condition' => 'required|in:excellent,good,fair,needs_maintenance,out_of_service',
            'usage_hours' => 'nullable|numeric|min:0',
            'maintenance_notes' => 'nullable|string',
            'specifications' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Equipment::create($request->all());

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        return view('admin.equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        return view('admin.equipment.edit', compact('equipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'brand_model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'calibration_date' => 'nullable|date',
            'calibration_due' => 'nullable|date|after:calibration_date',
            'calibration_certificate' => 'nullable|string|max:255',
            'condition' => 'required|in:excellent,good,fair,needs_maintenance,out_of_service',
            'usage_hours' => 'nullable|numeric|min:0',
            'maintenance_notes' => 'nullable|string',
            'specifications' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $equipment->update($request->all());

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Equipment $equipment)
    {
        $equipment->update(['is_active' => !$equipment->is_active]);

        return redirect()->back()
            ->with('success', 'Equipment status updated successfully!');
    }

    /**
     * API endpoint to get equipment for dynamic loading
     */
    public function getEquipment(Request $request)
    {
        $query = Equipment::active();

        // Default to showing assets, but allow showing items if specified
        if ($request->has('category')) {
            if ($request->category === 'assets') {
                $query->assets();
            } elseif ($request->category === 'items') {
                $query->items();
            }
        } else {
            // Default to assets only
            $query->assets();
        }

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('condition')) {
            $query->byCondition($request->condition);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('brand_model', 'LIKE', "%{$search}%")
                  ->orWhere('serial_number', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%");
            });
        }

        $equipment = $query->with('items')->get([
            'id', 
            'name', 
            'type', 
            'brand_model', 
            'serial_number', 
            'condition',
            'calibration_date',
            'calibration_due',
            'calibration_certificate',
            'maintenance_notes',
            'equipment_category',
            'swl',
            'reason_for_examination'
        ]);
        
        // Transform the data to match the expected format
        $transformedEquipment = $equipment->map(function($item) {
            $display_name = $item->name;
            if ($item->equipment_category === 'asset' && $item->items->count() > 0) {
                $display_name .= " ({$item->items->count()} items)";
            }
            
            return [
                'id' => $item->id,
                'name' => $display_name,
                'equipment_name' => $display_name,
                'equipment_type' => $item->type,
                'type' => $item->type,
                'brand_model' => $item->brand_model,
                'serial_number' => $item->serial_number,
                'condition' => $item->condition,
                'calibration_date' => $item->calibration_date ? $item->calibration_date->format('Y-m-d') : null,
                'calibration_due' => $item->calibration_due ? $item->calibration_due->format('Y-m-d') : null,
                'last_calibration' => $item->calibration_date ? $item->calibration_date->format('Y-m-d') : null,
                'calibration_certificate' => $item->calibration_certificate,
                'maintenance_notes' => $item->maintenance_notes,
                'notes' => $item->maintenance_notes,
                'equipment_category' => $item->equipment_category,
                'swl' => $item->swl,
                'reason_for_examination' => $item->reason_for_examination,
                'items_count' => $item->equipment_category === 'asset' ? $item->items->count() : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'equipment' => $transformedEquipment
        ]);
    }

    /**
     * API endpoint to get items for a specific equipment asset
     */
    public function getEquipmentItems($id)
    {
        $asset = Equipment::findOrFail($id);
        
        if ($asset->equipment_category !== 'asset') {
            return response()->json(['error' => 'Equipment must be an asset to have items'], 400);
        }

        $items = $asset->items()->get([
            'id',
            'name',
            'type',
            'serial_number',
            'swl',
            'test_load_applied',
            'examination_status',
            'manufacture_date',
            'next_examination_date',
            'specifications',
            'condition'
        ]);

        $transformedItems = $items->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'serial_number' => $item->serial_number,
                'swl' => $item->swl,
                'test_load_applied' => $item->test_load_applied,
                'examination_status' => $item->examination_status,
                'manufacture_date' => $item->manufacture_date ? $item->manufacture_date->format('Y-m-d') : null,
                'next_examination_date' => $item->next_examination_date ? $item->next_examination_date->format('Y-m-d') : null,
                'specifications' => $item->specifications,
                'condition' => $item->condition,
            ];
        });

        return response()->json([
            'asset' => [
                'id' => $asset->id,
                'name' => $asset->name,
                'serial_number' => $asset->serial_number,
                'reason_for_examination' => $asset->reason_for_examination
            ],
            'items' => $transformedItems
        ]);
    }
}
