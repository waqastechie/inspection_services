<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EquipmentTypeController extends Controller
{
    /**
     * Get all equipment types
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = EquipmentType::active()
                ->when($request->category, function ($q) use ($request) {
                    return $q->where('category', $request->category);
                })
                ->when($request->requires_calibration !== null, function ($q) use ($request) {
                    return $q->where('requires_calibration', (bool) $request->requires_calibration);
                });

            $equipmentTypes = $query->orderBy('category')
                ->orderBy('name')
                ->get()
                ->map(function ($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'code' => $type->code,
                        'category' => $type->category,
                        'category_display' => ucfirst(str_replace('_', ' ', $type->category)),
                        'description' => $type->description,
                        'default_services' => $type->default_services,
                        'required_fields' => $type->required_fields,
                        'specifications' => $type->specifications,
                        'requires_calibration' => $type->requires_calibration,
                        'calibration_frequency_months' => $type->calibration_frequency_months,
                        'calibration_interval_display' => $type->calibration_interval_display,
                        'is_active' => $type->is_active,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $equipmentTypes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve equipment types',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get a specific equipment type
     */
    public function show(EquipmentType $equipmentType): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $equipmentType->id,
                    'name' => $equipmentType->name,
                    'code' => $equipmentType->code,
                    'category' => $equipmentType->category,
                    'category_display' => ucfirst(str_replace('_', ' ', $equipmentType->category)),
                    'description' => $equipmentType->description,
                    'default_services' => $equipmentType->default_services,
                    'required_fields' => $equipmentType->required_fields,
                    'specifications' => $equipmentType->specifications,
                    'requires_calibration' => $equipmentType->requires_calibration,
                    'calibration_frequency_months' => $equipmentType->calibration_frequency_months,
                    'calibration_interval_display' => $equipmentType->calibration_interval_display,
                    'is_active' => $equipmentType->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Equipment type not found',
                'message' => 'The requested equipment type could not be found'
            ], 404);
        }
    }

    /**
     * Get equipment types grouped by category
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = EquipmentType::active()
                ->get()
                ->groupBy('category')
                ->map(function ($types, $category) {
                    return [
                        'category' => $category,
                        'category_display' => ucfirst(str_replace('_', ' ', $category)),
                        'count' => $types->count(),
                        'types' => $types->map(function ($type) {
                            return [
                                'id' => $type->id,
                                'name' => $type->name,
                                'code' => $type->code,
                                'requires_calibration' => $type->requires_calibration,
                                'default_services' => $type->default_services,
                            ];
                        })->values()
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve equipment categories',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get available services for equipment types
     */
    public function services(): JsonResponse
    {
        try {
            $allServices = EquipmentType::active()
                ->get()
                ->pluck('default_services')
                ->flatten()
                ->unique()
                ->sort()
                ->values();

            $serviceMap = [
                'visual_inspection' => 'Visual Inspection',
                'mpi' => 'MPI (Magnetic Particle Inspection)',
                'load_test' => 'Load Test',
                'calibration' => 'Calibration',
                'pressure_testing' => 'Pressure Testing',
                'ndt_testing' => 'NDT Testing',
                'lifting_examination' => 'Lifting Examination',
                'ultrasonic_testing' => 'Ultrasonic Testing',
                'penetrant_testing' => 'Penetrant Testing',
                'radiographic_testing' => 'Radiographic Testing',
                'hardness_testing' => 'Hardness Testing',
                'thickness_measurement' => 'Thickness Measurement',
                'torque_testing' => 'Torque Testing',
                'measurement' => 'Measurement',
            ];

            $services = $allServices->map(function ($service) use ($serviceMap) {
                return [
                    'value' => $service,
                    'label' => $serviceMap[$service] ?? ucfirst(str_replace('_', ' ', $service)),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve services',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}