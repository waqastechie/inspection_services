<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\EquipmentTypeController;
use App\Http\Controllers\Api\InspectionEquipmentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Equipment Types API Routes
Route::prefix('equipment-types')->group(function () {
    Route::get('/', [EquipmentTypeController::class, 'index']);
    Route::get('/categories', [EquipmentTypeController::class, 'categories']);
    Route::get('/services', [EquipmentTypeController::class, 'services']);
    Route::get('/{equipmentType}', [EquipmentTypeController::class, 'show']);
});

// Inspection Equipment API Routes
Route::prefix('inspection-equipment')->group(function () {
    Route::get('/', [InspectionEquipmentController::class, 'index']);
    Route::get('/{id}', [InspectionEquipmentController::class, 'show']);
    Route::post('/', [InspectionEquipmentController::class, 'store']);
    Route::put('/{id}', [InspectionEquipmentController::class, 'update']);
    Route::delete('/{id}', [InspectionEquipmentController::class, 'destroy']);
});

// Equipment Items API Routes
Route::prefix('equipment')->group(function () {
    Route::get('/', [EquipmentController::class, 'index']);
    Route::get('/statistics', [EquipmentController::class, 'statistics']);
    Route::get('/{equipment}', [EquipmentController::class, 'show']);
    
    // Authenticated routes for CUD operations
    Route::middleware('auth')->group(function () {
        Route::post('/', [EquipmentController::class, 'store']);
        Route::put('/{equipment}', [EquipmentController::class, 'update']);
        Route::delete('/{equipment}', [EquipmentController::class, 'destroy']);
    });
});

// Legacy compatibility routes (for backward compatibility)
Route::get('/equipment-types', [EquipmentTypeController::class, 'index']);
Route::get('/equipment', [EquipmentController::class, 'index']);