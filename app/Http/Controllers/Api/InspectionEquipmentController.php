<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspectionEquipment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class InspectionEquipmentController extends Controller
{
    /**
     * Display a listing of inspection equipment.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = InspectionEquipment::with(['equipmentType']);
            
            // Filter by inspection_id if provided
            if ($request->has('inspection_id')) {
                $query->where('inspection_id', $request->inspection_id);
            }
            
            // Filter by client_id if provided
            if ($request->has('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            // Filter by category if provided
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            
            $equipment = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $equipment,
                'count' => $equipment->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving inspection equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created inspection equipment.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'inspection_id' => 'required|exists:inspections,id',
                'client_id' => 'nullable|exists:clients,id',
                'equipment_type_id' => 'nullable|exists:equipment_types,id',
                'equipment_type' => 'nullable|string|max:255', // Allow equipment type name
                'serial_number' => 'required|string|max:255',
                'description' => 'required|string',
                'reason_for_examination' => 'required|string|max:255',
                'model' => 'nullable|string|max:255',
                'swl' => 'nullable|string|max:255', // Changed to string to handle "1472 Kg" format
                'test_load_applied' => 'nullable|string|max:255', // Changed to string
                'date_of_manufacture' => 'nullable|date',
                'date_of_last_examination' => 'nullable|date',
                'date_of_next_examination' => 'nullable|date',
                'status' => 'nullable|in:ND,D,NI,R',
                'condition' => 'nullable|in:good,fair,poor,critical',
                'remarks' => 'nullable|string',
                'category' => 'nullable|in:asset,item'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $equipment = InspectionEquipment::create([
                'inspection_id' => $request->inspection_id,
                'client_id' => $request->client_id,
                'equipment_type_id' => $request->equipment_type_id,
                'serial_number' => $request->serial_number,
                'description' => $request->description,
                'reason_for_examination' => $request->reason_for_examination,
                'model' => $request->model,
                'swl' => $request->swl,
                'test_load_applied' => $request->test_load_applied,
                'date_of_manufacture' => $request->date_of_manufacture,
                'date_of_last_examination' => $request->date_of_last_examination,
                'date_of_next_examination' => $request->date_of_next_examination,
                'status' => $request->status ?? 'ND',
                'condition' => $request->condition ?? 'good',
                'remarks' => $request->remarks,
                'category' => $request->category ?? 'asset'
            ]);

            // Load the equipment with its relationships
            $equipment->load(['equipmentType']);

            return response()->json([
                'success' => true,
                'message' => 'Equipment created successfully',
                'data' => $equipment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified inspection equipment.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $equipment = InspectionEquipment::with(['equipmentType'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $equipment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment not found'
            ], 404);
        }
    }

    /**
     * Update the specified inspection equipment.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $equipment = InspectionEquipment::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'equipment_type_id' => 'sometimes|required|exists:equipment_types,id',
                'serial_number' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'reason_for_examination' => 'sometimes|required|string|max:255',
                'model' => 'nullable|string|max:255',
                'swl' => 'nullable|numeric|min:0',
                'test_load_applied' => 'nullable|numeric|min:0',
                'date_of_manufacture' => 'nullable|date',
                'date_of_last_examination' => 'nullable|date',
                'date_of_next_examination' => 'nullable|date',
                'status' => 'nullable|in:ND,D,NI,R',
                'condition' => 'nullable|in:good,fair,poor,critical',
                'remarks' => 'nullable|string',
                'category' => 'nullable|in:asset,item'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $equipment->update($request->only([
                'equipment_type_id', 'serial_number', 'description', 'reason_for_examination',
                'model', 'swl', 'test_load_applied', 'date_of_manufacture',
                'date_of_last_examination', 'date_of_next_examination',
                'status', 'condition', 'remarks', 'category'
            ]));

            $equipment->load(['equipmentType']);

            return response()->json([
                'success' => true,
                'message' => 'Equipment updated successfully',
                'data' => $equipment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified inspection equipment.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $equipment = InspectionEquipment::findOrFail($id);
            $equipment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Equipment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting equipment: ' . $e->getMessage()
            ], 500);
        }
    }
}