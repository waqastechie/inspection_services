<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnel = Personnel::orderBy('first_name')->paginate(20);
        return view('admin.personnel.index', compact('personnel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.personnel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'employee_id' => 'nullable|string|max:100|unique:personnels,employee_id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'supervisor' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'experience_years' => 'nullable|integer|min:0',
            'qualifications' => 'nullable|string',
            'certifications' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Personnel::create($request->all());

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Personnel created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        return view('admin.personnel.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel)
    {
        return view('admin.personnel.edit', compact('personnel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'employee_id' => 'nullable|string|max:100|unique:personnels,employee_id,' . $personnel->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'supervisor' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'experience_years' => 'nullable|integer|min:0',
            'qualifications' => 'nullable|string',
            'certifications' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $personnel->update($request->all());

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Personnel updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel $personnel)
    {
        $personnel->delete();

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Personnel deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Personnel $personnel)
    {
        $personnel->update(['is_active' => !$personnel->is_active]);

        return redirect()->back()
            ->with('success', 'Personnel status updated successfully!');
    }

    /**
     * API endpoint to get personnel for dynamic loading
     */
    public function getPersonnel(Request $request)
    {
        $query = Personnel::active();

        if ($request->has('position')) {
            $query->where('position', $request->position);
        }

        if ($request->has('department')) {
            $query->where('department', $request->department);
        }

        $personnel = $query->get(['id', 'first_name', 'last_name', 'position', 'department', 'email', 'phone']);

        return response()->json([
            'success' => true,
            'personnel' => $personnel
        ]);
    }
}
