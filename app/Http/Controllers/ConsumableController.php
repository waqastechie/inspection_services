<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsumableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumables = Consumable::orderBy('name')->paginate(20);
        return view('admin.consumables.index', compact('consumables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.consumables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'brand_manufacturer' => 'nullable|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'batch_lot_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'quantity_available' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_cost' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'condition' => 'required|in:new,good,expired,damaged',
            'storage_requirements' => 'nullable|string',
            'safety_notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Consumable::create($request->all());

        return redirect()->route('admin.consumables.index')
            ->with('success', 'Consumable created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Consumable $consumable)
    {
        return view('admin.consumables.show', compact('consumable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumable $consumable)
    {
        return view('admin.consumables.edit', compact('consumable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consumable $consumable)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'brand_manufacturer' => 'nullable|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'batch_lot_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'quantity_available' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_cost' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'condition' => 'required|in:new,good,expired,damaged',
            'storage_requirements' => 'nullable|string',
            'safety_notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $consumable->update($request->all());

        return redirect()->route('admin.consumables.index')
            ->with('success', 'Consumable updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumable $consumable)
    {
        $consumable->delete();

        return redirect()->route('admin.consumables.index')
            ->with('success', 'Consumable deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Consumable $consumable)
    {
        $consumable->update(['is_active' => !$consumable->is_active]);

        return redirect()->back()
            ->with('success', 'Consumable status updated successfully!');
    }

    /**
     * API endpoint to get consumables for dynamic loading
     */
    public function getConsumables(Request $request)
    {
        $query = Consumable::active();

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('condition')) {
            $query->byCondition($request->condition);
        }

        $consumables = $query->get(['id', 'name', 'type', 'brand_manufacturer', 'quantity_available', 'unit']);

        return response()->json($consumables);
    }
}
