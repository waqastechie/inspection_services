<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class EquipmentController extends Controller
{
    /**
     * Get all equipment items
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Equipment::with('equipmentType')
                ->when($request->category, function ($q) use ($request) {
                    return $q->whereHas('equipmentType', function ($subQuery) use ($request) {
                        $subQuery->where('category', $request->category);
                    });
                })
                ->when($request->search, function ($q) use ($request) {
                    return $q->where(function ($searchQuery) use ($request) {
                        $searchQuery->where('name', 'like', "%{$request->search}%")
                                   ->orWhere('serial_number', 'like', "%{$request->search}%")
                                   ->orWhere('brand', 'like', "%{$request->search}%");
                    });
                });

            // Pagination
            $perPage = min($request->get('per_page', 50), 100); // Max 100 items per page
            $equipment = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $equipment->items(),
                'meta' => [
                    'current_page' => $equipment->currentPage(),
                    'total' => $equipment->total(),
                    'per_page' => $equipment->perPage(),
                    'last_page' => $equipment->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve equipment',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get a specific equipment item
     */
    public function show(Equipment $equipment): JsonResponse
    {
        try {
            $equipment->load('equipmentType');
            
            return response()->json([
                'success' => true,
                'data' => $equipment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Equipment not found',
                'message' => 'The requested equipment could not be found'
            ], 404);
        }
    }

    /**
     * Create new equipment item
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'equipment_type_id' => 'required|exists:equipment_types,id',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'serial_number' => 'required|string|max:255|unique:equipment,serial_number',
                'purchase_date' => 'nullable|date',
                'warranty_expiry' => 'nullable|date',
                'location' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            $equipment = Equipment::create($validated);
            $equipment->load('equipmentType');

            return response()->json([
                'success' => true,
                'data' => $equipment,
                'message' => 'Equipment created successfully'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create equipment',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update equipment item
     */
    public function update(Request $request, Equipment $equipment): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'equipment_type_id' => 'sometimes|required|exists:equipment_types,id',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'serial_number' => 'sometimes|required|string|max:255|unique:equipment,serial_number,' . $equipment->id,
                'purchase_date' => 'nullable|date',
                'warranty_expiry' => 'nullable|date',
                'location' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            $equipment->update($validated);
            $equipment->load('equipmentType');

            return response()->json([
                'success' => true,
                'data' => $equipment,
                'message' => 'Equipment updated successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update equipment',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Delete equipment item
     */
    public function destroy(Equipment $equipment): JsonResponse
    {
        try {
            $equipment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Equipment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete equipment',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get equipment statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_equipment' => Equipment::count(),
                'by_category' => EquipmentType::withCount('equipment')
                    ->get()
                    ->groupBy('category')
                    ->map(function ($types) {
                        return $types->sum('equipment_count');
                    }),
                'calibration_due_soon' => Equipment::whereHas('equipmentType', function ($q) {
                        $q->where('requires_calibration', true);
                    })->whereDate('next_calibration_date', '<=', now()->addDays(30))
                    ->count(),
                'calibration_overdue' => Equipment::whereHas('equipmentType', function ($q) {
                        $q->where('requires_calibration', true);
                    })->whereDate('next_calibration_date', '<', now())
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve statistics',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}