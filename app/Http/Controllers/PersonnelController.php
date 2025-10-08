<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Http\Controllers\Controller;
use App\Services\PersonnelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonnelController extends Controller
{
    protected $personnelService;
    
    public function __construct(PersonnelService $personnelService)
    {
        $this->personnelService = $personnelService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnel = $this->personnelService->getAllPersonnel(20);
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

        $this->personnelService->createPersonnel($request->all());

        return redirect()->route('personnel.index')
            ->with('success', 'Personnel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $personnel = $this->personnelService->findById($id);
        if (!$personnel) {
            abort(404);
        }
        return view('admin.personnel.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $personnel = $this->personnelService->findById($id);
        if (!$personnel) {
            abort(404);
        }
        return view('admin.personnel.edit', compact('personnel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $personnel = $this->personnelService->findById($id);
        if (!$personnel) {
            abort(404);
        }
        
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

        $this->personnelService->updatePersonnel($id, $request->all());

        return redirect()->route('personnel.index')
            ->with('success', 'Personnel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->personnelService->deletePersonnel($id);

        return redirect()->route('personnel.index')
            ->with('success', 'Personnel deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        $personnel = $this->personnelService->findById($id);
        if (!$personnel) {
            abort(404);
        }
        
        $this->personnelService->updatePersonnel($id, ['is_active' => !$personnel->is_active]);

        return redirect()->back()
            ->with('success', 'Personnel status updated successfully.');
    }

    /**
     * API endpoint to get personnel for dynamic loading
     */
    public function getPersonnel(Request $request)
    {
        $personnel = $this->personnelService->getActivePersonnel();

        return response()->json([
            'success' => true,
            'personnel' => $personnel
        ]);
    }
}
