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
        $equipment = Equipment::orderBy('name')->paginate(20);
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
            'specifications' => 'nullable|array',
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
            'specifications' => 'nullable|array',
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

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('condition')) {
            $query->byCondition($request->condition);
        }

        $equipment = $query->get(['id', 'name', 'type', 'brand_model', 'serial_number', 'condition']);

        return response()->json($equipment);
    }
}
