<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoadTest;

class LoadTestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'inspection_id' => 'required|exists:inspections,id',
            'duration_held' => 'required|string',
            'two_points_diagonal' => 'required|string',
            'four_points' => 'required|string',
            'deflection' => 'nullable|string',
            'deformation' => 'nullable|string',
            'distance_from_ground' => 'nullable|string',
            'result' => 'required|string',
        ]);

        LoadTest::create($request->all());

        return back()->with('success', 'Load Test saved!');
    }
}
