<?php

use Illuminate\Support\Facades\Route;
use App\Models\Personnel;

Route::get('/test-personnel', function () {
    try {
        $personnel = Personnel::all();
        $activePersonnel = Personnel::where('is_active', true)->get();
        
        return response()->json([
            'total_personnel' => $personnel->count(),
            'active_personnel' => $activePersonnel->count(),
            'personnel_data' => $activePersonnel->take(5),
            'first_record_fields' => $personnel->first() ? array_keys($personnel->first()->toArray()) : []
        ]);
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});