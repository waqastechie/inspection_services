<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Inspection;
use App\Models\InspectionService;
use App\Models\Service;
use App\Models\PersonnelAssignment;
use App\Models\EquipmentAssignment;
use App\Models\InspectionEquipment;
use App\Models\EquipmentType;
use App\Models\ConsumableAssignment;
use App\Models\Personnel;
use App\Http\Requests\StoreInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use App\Services\ImageUploadService;
use App\Models\InspectionResult;
use App\Models\Equipment;
use App\Models\Consumable;
use App\Models\User;
use App\Models\LiftingExamination;
use App\Models\MpiInspection;

class InspectionController extends Controller
{
    protected $imageUploadService;

    public function __construct()
    {
        $this->imageUploadService = app(ImageUploadService::class);
    }

    /**
     * Display the inspection form
     */
    public function create()
    {
        // Redirect to wizard instead of showing the old form
        return redirect()->route('inspections.wizard.step', ['step' => 1]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inspections = Inspection::with('client')->latest()->paginate(15);
        return view('inspections.index', compact('inspections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
    {
        $inspection = Inspection::with([
            'client',
            'services',
            'personnelAssignments',
            'equipmentAssignments.equipment',
            'consumableAssignments',
            'inspectionResults',
            'images',
            'liftingExamination',
            'mpiInspection',
            'inspectionEquipment',
            'equipmentType' // Add equipment relationship for equipment_type field
        ])->findOrFail($id);

        return view('inspections.show', compact('inspection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $inspection = Inspection::with([
            'client',
            'services',
            'personnelAssignments',
            'equipmentAssignments',
            'consumableAssignments',
            'inspectionResults',
            'images'
        ])->findOrFail($id);

        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $inspection = Inspection::findOrFail($id);
            
            $inspection->update([
                'client_id' => $request->client_id,
                'project_name' => $request->project_name,
                'location' => $request->location,
                'lead_inspector_name' => $request->lead_inspector_name ?? 'Inspector',
                'lead_inspector_certification' => $request->lead_inspector_certification ?? 'Certified Inspector',
                'status' => $request->status ?? $inspection->status,
            ]);

            DB::commit();

            return redirect()->route('inspections.show', $inspection->id)
                ->with('success', 'Inspection updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while updating the inspection: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $inspection = Inspection::findOrFail($id);
            $inspection->delete();

            return redirect()->route('inspections.index')
                ->with('success', 'Inspection deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the inspection: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created inspection report or finalize a draft
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $inspection = Inspection::create([
                'inspection_number' => Inspection::generateInspectionNumber(),
                'client_id' => $request->client_id,
                'project_name' => $request->project_name,
                'location' => $request->location,
                'inspection_date' => now()->format('Y-m-d'),
                'lead_inspector_name' => $request->lead_inspector_name ?? 'Inspector',
                'lead_inspector_certification' => $request->lead_inspector_certification ?? 'Certified Inspector',
                'status' => 'submitted_for_qa',
                'report_date' => now(),
            ]);

            // Handle service-specific data (MPI and Lifting Examination)
            $this->saveServiceSpecificData($inspection, $request);

            DB::commit();

            return redirect()->route('inspections.show', $inspection->id)
                ->with('success', 'Inspection submitted for QA successfully! Report Number: ' . $inspection->inspection_number);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while saving the inspection report: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Client portal methods
     */
    public function clientIndex()
    {
        if (!auth()->user()->isClient()) {
            abort(403, 'Access denied');
        }

        $inspections = Inspection::where('client_id', auth()->user()->client_id)
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('client.inspections.index', compact('inspections'));
    }

    public function clientShow($id)
    {
        if (!auth()->user()->isClient()) {
            abort(403, 'Access denied');
        }

        $inspection = Inspection::with([
            'client',
            'services',
            'personnelAssignments',
            'equipmentAssignments',
            'consumableAssignments',
            'inspectionResults',
            'images'
        ])->where('client_id', auth()->user()->client_id)
          ->where('status', 'approved')
          ->findOrFail($id);

        return view('client.inspections.show', compact('inspection'));
    }

    /**
     * QA Methods
     */
    public function submitForQA($id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->update(['status' => 'submitted_for_qa']);
        
        return redirect()->back()->with('success', 'Inspection submitted for QA review');
    }

    /**
     * Wizard Methods
     */
    public function createWizard($step = 1, $inspection = null)
    {
        $currentStep = (int) $step;
        if ($currentStep < 1 || $currentStep > 7) {
            $currentStep = 1;
        }

        $totalSteps = 7; // Total number of wizard steps
        
        // Define wizard steps (0-based indexing to match view expectations)
        $steps = [
            0 => [
                'title' => 'Client Information',
                'description' => 'Enter basic client and project details',
                'view' => 'inspections.wizard.step1'
            ],
            1 => [
                'title' => 'Equipment Details',
                'description' => 'Specify equipment information',
                'view' => 'inspections.wizard.step2'
            ],
            2 => [
                'title' => 'Services',
                'description' => 'Select and configure inspection services',
                'view' => 'inspections.wizard.step3'
            ],
            3 => [
                'title' => 'Equipment Assignments',
                'description' => 'Assign equipment for inspection',
                'view' => 'inspections.wizard.step4'
            ],
            4 => [
                'title' => 'Consumables',
                'description' => 'Assign consumables and materials',
                'view' => 'inspections.wizard.step5'
            ],
            5 => [
                'title' => 'Images & Documentation',
                'description' => 'Upload images and documentation',
                'view' => 'inspections.wizard.step6'
            ],
            6 => [
                'title' => 'Review & Submit',
                'description' => 'Review all information and submit for QA',
                'view' => 'inspections.wizard.step7'
            ]
        ];
        
        $inspectionRecord = null;
        if ($inspection) {
            $inspectionRecord = Inspection::find($inspection);
        }

        // Load personnel data for service inspector dropdowns
        $personnel = Personnel::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        // Load clients for step 1
        $clients = \App\Models\Client::where('is_active', true)
            ->orderBy('client_name')
            ->get();

        // Load consumables for step 5
        $consumables = \App\Models\Consumable::active()
            ->orderBy('type')
            ->get();

        // Load users for inspector dropdown
        $users = \App\Models\User::where(function($query) {
                $query->where('role', 'inspector')
                      ->orWhere('role', 'admin');
            })
            ->orderBy('name')
            ->get();

        // Load equipment for step 2
        $equipment = \App\Models\Equipment::active()
            ->orderBy('name')
            ->get();

        return view('inspections.create-wizard', compact('currentStep', 'totalSteps', 'steps', 'personnel', 'clients', 'consumables', 'users', 'equipment') + ['inspection' => $inspectionRecord]);
    }

    public function saveWizardStep(Request $request)
    {
        $step = $request->input('step', 1);
        $inspectionId = $request->input('inspection_id');

        // DEBUG: Log all incoming request data
        \Log::info('=== WIZARD STEP SAVE REQUEST ===', [
            'step' => $step,
            'inspection_id' => $inspectionId,
            'has_equipment_data' => $request->has('equipment_data'),
            'equipment_data_preview' => substr($request->input('equipment_data', ''), 0, 200),
            'all_request_keys' => array_keys($request->all()),
            'request_method' => $request->method(),
            'url' => $request->url()
        ]);

        try {
            DB::beginTransaction();

            if ($inspectionId) {
                $inspection = Inspection::findOrFail($inspectionId);
                $inspection->update($request->except(['step', '_token', 'equipment_data']));
            } else {
                $inspection = Inspection::create(array_merge($request->except(['step', '_token', 'equipment_data']), [
                    'inspection_number' => Inspection::generateInspectionNumber(),
                    'status' => 'draft',
                    'inspection_date' => now()->format('Y-m-d'),
                ]));
            }

            // Handle equipment data for step 2 (Equipment Details)
            if ($step == 2) {
                \Log::info('Step 2 processing - Equipment Details', [
                    'step' => $step,
                    'inspection_id' => $inspection->id,
                    'equipment_type' => $request->input('equipment_type'),
                    'equipment_serial' => $request->input('equipment_serial'),
                    'request_data' => $request->except(['_token', 'step'])
                ]);
                
                $this->saveEquipmentDetails($inspection, $request);
                \Log::info('saveEquipmentDetails completed for inspection ' . $inspection->id);
            }

            // Handle equipment assignments for step 4 (Equipment Assignments)
            \Log::info('Step 4 processing', [
                'step' => $step,
                'has_equipment_data' => $request->has('equipment_data'),
                'equipment_data_raw' => $request->input('equipment_data'),
                'inspection_id' => $inspection->id
            ]);
            
            if ($step == 4 && $request->has('equipment_data')) {
                \Log::info('Calling saveEquipmentData for inspection ' . $inspection->id);
                $this->saveEquipmentData($inspection, $request->input('equipment_data'));
                \Log::info('saveEquipmentData completed for inspection ' . $inspection->id);
            } elseif ($step == 4) {
                \Log::warning('Step 4 but no equipment_data found in request', [
                    'all_request_data' => $request->all()
                ]);
            }

            // Handle consumables data for step 5
            if ($step == 5 && $request->has('consumables')) {
                \Log::info('Calling saveConsumablesData for inspection ' . $inspection->id);
                $this->saveConsumablesData($inspection, $request->input('consumables'));
                \Log::info('saveConsumablesData completed for inspection ' . $inspection->id);
            } elseif ($step == 5) {
                \Log::info('Step 5 but no consumables found in request', [
                    'all_request_data' => $request->all()
                ]);
            }

            // Handle images data for step 6
            if ($step == 6) {
                \Log::info('Processing Step 6 - Images & Documentation for inspection ' . $inspection->id);
                $this->saveInspectionImages($inspection, $request);
                \Log::info('saveInspectionImages completed for inspection ' . $inspection->id);
            }

            // Handle service-specific data (MPI and Lifting Examination)
            $this->saveServiceSpecificData($inspection, $request);

            DB::commit();

            if ($step >= 7) {
                // Final step - submit for QA
                $inspection->update(['status' => 'submitted_for_qa']);
                return redirect()->route('inspections.show', $inspection->id)
                    ->with('success', 'Inspection submitted for QA successfully!');
            } else {
                // Go to next step
                return redirect()->route('inspections.wizard.step', ['step' => $step + 1, 'inspection' => $inspection->id]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error saving wizard step: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function linkItemsToEquipment(Request $request)
    {
        // Handle linking items to equipment
        return response()->json(['success' => true]);
    }

    public function saveDraft(Request $request)
    {
        try {
            DB::beginTransaction();

            $inspection = Inspection::create(array_merge($request->all(), [
                'inspection_number' => Inspection::generateInspectionNumber(),
                'status' => 'draft',
                'inspection_date' => now()->format('Y-m-d'),
            ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'inspection_id' => $inspection->id,
                'message' => 'Draft saved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving draft: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDraft(Request $request, $id)
    {
        try {
            $inspection = Inspection::findOrFail($id);
            $inspection->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Draft updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating draft: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePDF($id)
    {
        $inspection = Inspection::with([
            'client',
            'services',
            'personnelAssignments',
            'equipmentAssignments',
            'consumableAssignments',
            'inspectionResults',
            'images'
        ])->findOrFail($id);

        // For now, return a simple response
        return response()->json([
            'message' => 'PDF generation not yet implemented',
            'inspection_id' => $id
        ]);
    }

    public function previewPDF($id)
    {
        $inspection = Inspection::with([
            'client',
            'services',
            'personnelAssignments',
            'equipmentAssignments',
            'consumableAssignments',
            'inspectionResults',
            'images'
        ])->findOrFail($id);

        return view('inspections.pdf-preview', compact('inspection'));
    }

    public function markAsCompleted($id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->update(['status' => 'completed']);
        
        return redirect()->back()->with('success', 'Inspection marked as completed');
    }

    public function deleteImage($imageId)
    {
        try {
            // Handle image deletion
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateImageCaption(Request $request, $imageId)
    {
        try {
            // Handle image caption update
            return response()->json([
                'success' => true,
                'message' => 'Caption updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating caption: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save equipment data to inspection_equipment table
     */
    private function saveEquipmentData($inspection, $equipmentDataJson)
    {
        \Log::info('saveEquipmentData called', [
            'inspection_id' => $inspection->id,
            'equipmentDataJson' => $equipmentDataJson,
            'is_empty' => empty($equipmentDataJson)
        ]);
        
        if (empty($equipmentDataJson)) {
            \Log::warning('Equipment data is empty, skipping save');
            return;
        }

        $equipmentData = json_decode($equipmentDataJson, true);
        \Log::info('Decoded equipment data', [
            'is_array' => is_array($equipmentData),
            'count' => is_array($equipmentData) ? count($equipmentData) : 0,
            'data' => $equipmentData
        ]);
        
        if (!is_array($equipmentData)) {
            \Log::error('Equipment data is not an array after JSON decode');
            return;
        }

        // Clear existing equipment for this inspection
        $deletedCount = \App\Models\InspectionEquipment::where('inspection_id', $inspection->id)->count();
        \App\Models\InspectionEquipment::where('inspection_id', $inspection->id)->delete();
        \Log::info('Cleared existing equipment records', ['deleted_count' => $deletedCount]);

        // Save new equipment data
        $createdCount = 0;
        foreach ($equipmentData as $index => $item) {
            \Log::info('Creating equipment record', ['index' => $index, 'item' => $item]);
            
            // Validate and clean numeric fields
            $swl = null;
            if (!empty($item['swl'])) {
                $swlValue = is_numeric($item['swl']) ? (float) $item['swl'] : null;
                if ($swlValue !== null && $swlValue >= 0) {
                    $swl = $swlValue;
                } else {
                    \Log::warning('Invalid SWL value ignored', ['value' => $item['swl'], 'index' => $index]);
                }
            }
            
            $testLoad = null;
            if (!empty($item['test_load_applied'])) {
                $testLoadValue = is_numeric($item['test_load_applied']) ? (float) $item['test_load_applied'] : null;
                if ($testLoadValue !== null && $testLoadValue >= 0) {
                    $testLoad = $testLoadValue;
                } else {
                    \Log::warning('Invalid test load value ignored', ['value' => $item['test_load_applied'], 'index' => $index]);
                }
            }
            
            $record = \App\Models\InspectionEquipment::create([
                'inspection_id' => $inspection->id,
                'client_id' => $inspection->client_id,
                'equipment_type_id' => $item['equipment_type_id'] ?? null,
                'category' => $item['category'] ?? 'item',
                'equipment_type' => $item['equipment_type'] ?? '',
                'serial_number' => $item['serial_number'] ?? '',
                'description' => $item['description'] ?? '',
                'reason_for_examination' => $item['reason_for_examination'] ?? '',
                'model' => $item['model'] ?? '',
                'swl' => $swl,
                'test_load_applied' => $testLoad,
                'date_of_manufacture' => $item['date_of_manufacture'] ?: null,
                'date_of_last_examination' => $item['date_of_last_examination'] ?: null,
                'date_of_next_examination' => $item['date_of_next_examination'] ?: null,
                'status' => $item['status'] ?? '',
                'remarks' => $item['remarks'] ?? '',
                'condition' => 'good',
                'metadata' => json_encode($item)
            ]);
            
            $createdCount++;
            \Log::info('Created equipment record', ['id' => $record->id, 'equipment_type' => $record->equipment_type]);
        }
        
        \Log::info('Equipment data save completed', ['created_count' => $createdCount]);
    }

    /**
     * Save equipment details from step 2 of the wizard
     */
    private function saveEquipmentDetails($inspection, $request)
    {
        \Log::info('saveEquipmentDetails called', [
            'inspection_id' => $inspection->id,
            'equipment_type' => $request->input('equipment_type'),
            'equipment_serial' => $request->input('equipment_serial')
        ]);

        try {
            // Delete existing equipment assignments for this inspection
            \App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->delete();

            // Get equipment details if equipment_type is numeric (equipment ID)
            $equipmentName = '';
            $equipmentType = '';
            if (is_numeric($request->input('equipment_type'))) {
                $equipment = \App\Models\Equipment::find($request->input('equipment_type'));
                if ($equipment) {
                    $equipmentName = $equipment->name;
                    $equipmentType = $equipment->type;
                }
            }

            // Determine assigned services based on equipment name/type
            $assignedServices = $this->getServicesForEquipment($equipmentName ?: $request->input('equipment_type'));

            // Determine calibration status based on dates
            $calibrationStatus = 'current';
            if ($request->input('equipment_next_due')) {
                $nextDue = \Carbon\Carbon::parse($request->input('equipment_next_due'));
                if ($nextDue->isPast()) {
                    $calibrationStatus = 'overdue';
                } elseif ($nextDue->diffInDays(now()) <= 30) {
                    $calibrationStatus = 'due_soon';
                }
            } else {
                $calibrationStatus = 'not_required';
            }

            // Create new equipment assignment based on step 2 form data
            $equipmentAssignment = \App\Models\EquipmentAssignment::create([
                'inspection_id' => $inspection->id,
                'equipment_id' => is_numeric($request->input('equipment_type')) ? $request->input('equipment_type') : null,
                'equipment_name' => $equipmentName ?: $request->input('equipment_type'),
                'equipment_type' => $equipmentType ?: $request->input('equipment_type'),
                'make_model' => trim(($request->input('equipment_manufacturer') ?: '') . ' ' . ($request->input('equipment_model') ?: '')),
                'serial_number' => $request->input('equipment_serial'),
                'last_calibration_date' => $request->input('equipment_cert_date') ?: null,
                'next_calibration_date' => $request->input('equipment_next_due') ?: null,
                'calibration_status' => $calibrationStatus,
                'condition' => strtolower($request->input('equipment_status')) ?: 'good',
                'assigned_services' => $assignedServices,
                'notes' => $request->input('equipment_comments')
            ]);

            \Log::info('Equipment assignment created', ['id' => $equipmentAssignment->id]);

        } catch (\Exception $e) {
            \Log::error('Error saving equipment details', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get assigned services for equipment based on equipment name/type
     */
    private function getServicesForEquipment($equipmentName)
    {
        $equipmentName = strtolower($equipmentName);
        
        // MPI (Magnetic Particle Inspection) Equipment
        $mpiEquipment = [
            'ac yoke', 'dc yoke', 'permanent magnet', 'test blocks',
            'magnetic particle tester', 'yoke', 'magnet'
        ];
        
        // DPI (Dye Penetrant Inspection) Equipment  
        $dpiEquipment = [
            'comparator block', 'penetrant testing kit', 'dye penetrant'
        ];
        
        // Load Testing Equipment
        $loadTestingEquipment = [
            'load cell', 'water bags', 'dead weight blocks', 'crane scale',
            'load testing', 'weight', 'lifting'
        ];
        
        // NDT Equipment (General)
        $ndtEquipment = [
            'ultrasonic testing device', 'ultrasonic', 'ut', 'flaw detector'
        ];
        
        // Check equipment name against service categories
        foreach ($mpiEquipment as $equipment) {
            if (strpos($equipmentName, $equipment) !== false) {
                return ['MPI'];
            }
        }
        
        foreach ($dpiEquipment as $equipment) {
            if (strpos($equipmentName, $equipment) !== false) {
                return ['DPI'];
            }
        }
        
        foreach ($loadTestingEquipment as $equipment) {
            if (strpos($equipmentName, $equipment) !== false) {
                return ['Load Testing'];
            }
        }
        
        foreach ($ndtEquipment as $equipment) {
            if (strpos($equipmentName, $equipment) !== false) {
                return ['NDT', 'UT'];
            }
        }
        
        // Default: return empty array if no specific service match
        return [];
    }

    /**
     * Save consumables assignment data for inspection
     */
    private function saveConsumablesData($inspection, $consumablesData)
    {
        \Log::info('saveConsumablesData called', [
            'inspection_id' => $inspection->id,
            'consumables_data' => $consumablesData,
            'is_empty' => empty($consumablesData)
        ]);
        
        if (empty($consumablesData)) {
            \Log::warning('Consumables data is empty, skipping save');
            return;
        }

        if (!is_array($consumablesData)) {
            \Log::error('Consumables data is not an array');
            return;
        }

        // Clear existing consumable assignments for this inspection
        $deletedCount = ConsumableAssignment::where('inspection_id', $inspection->id)->count();
        ConsumableAssignment::where('inspection_id', $inspection->id)->delete();
        \Log::info('Cleared existing consumable assignments', ['deleted_count' => $deletedCount]);

        // Save new consumable assignments
        $createdCount = 0;
        foreach ($consumablesData as $index => $item) {
            \Log::info('Creating consumable assignment record', ['index' => $index, 'item' => $item]);
            
            // Validate quantity
            $quantity = 1; // default
            if (!empty($item['quantity'])) {
                $quantityValue = is_numeric($item['quantity']) ? (float) $item['quantity'] : null;
                if ($quantityValue !== null && $quantityValue > 0) {
                    $quantity = $quantityValue;
                } else {
                    \Log::warning('Invalid quantity value, using default 1', ['value' => $item['quantity'], 'index' => $index]);
                }
            }

            // Validate cost
            $cost = null;
            if (!empty($item['cost'])) {
                $costValue = is_numeric($item['cost']) ? (float) $item['cost'] : null;
                if ($costValue !== null && $costValue >= 0) {
                    $cost = $costValue;
                } else {
                    \Log::warning('Invalid cost value ignored', ['value' => $item['cost'], 'index' => $index]);
                }
            }
            
            // Get consumable type from database if consumable_id is provided
            $consumableType = '';
            if (!empty($item['consumable_id'])) {
                $consumable = \App\Models\Consumable::find($item['consumable_id']);
                if ($consumable) {
                    $consumableType = $consumable->type;
                }
            }
            
            // Get assigned services - default to common services for the consumable type
            $assignedServices = [];
            if (!empty($item['assigned_services'])) {
                $assignedServices = is_array($item['assigned_services']) ? $item['assigned_services'] : [$item['assigned_services']];
            } else {
                // Auto-assign services based on consumable type
                $assignedServices = $this->getServicesForConsumableType($consumableType ?: ($item['type'] ?? 'General'));
            }
            
            $record = ConsumableAssignment::create([
                'inspection_id' => $inspection->id,
                'consumable_id' => $item['consumable_id'] ?? null,
                'consumable_name' => $item['description'] ?? '',
                'consumable_type' => $consumableType ?: ($item['type'] ?? 'General'),
                'batch_lot_number' => $item['batch_number'] ?? '',
                'quantity_used' => $quantity,
                'unit' => $item['unit'] ?? '',
                'condition' => $item['condition'] ?? 'new',
                'assigned_services' => json_encode($assignedServices),
                'notes' => $item['notes'] ?? ''
            ]);
            
            $createdCount++;
            \Log::info('Created consumable assignment record', ['id' => $record->id, 'consumable_name' => $record->consumable_name]);
        }
        
        \Log::info('Consumables data save completed', ['created_count' => $createdCount]);
    }

    /**
     * Get assigned services for consumable based on consumable type
     */
    private function getServicesForConsumableType($consumableType)
    {
        $consumableType = strtolower($consumableType);
        
        // Map consumable types to their common services
        $serviceMapping = [
            'coupling agent' => ['UT', 'NDT'],
            'test medium' => ['MPI', 'NDT'],
            'test chemical' => ['DPI', 'PT'],
            'imaging medium' => ['RT', 'NDT'],
            'processing chemical' => ['RT', 'DPI'],
            'cleaner' => ['MPI', 'DPI', 'NDT'],
            'developer' => ['DPI', 'PT'],
            'penetrant' => ['DPI', 'PT'],
            'magnetic particles' => ['MPI'],
            'contrast paint' => ['DPI', 'PT'],
            'general' => ['General Inspection']
        ];
        
        // Check for matching service mapping
        foreach ($serviceMapping as $type => $services) {
            if (strpos($consumableType, $type) !== false) {
                return $services;
            }
        }
        
        // Default services if no specific match found
        return ['General Inspection'];
    }

    /**
     * Save inspection images and documents
     */
    private function saveInspectionImages($inspection, $request)
    {
        \Log::info('saveInspectionImages called', [
            'inspection_id' => $inspection->id,
            'has_files' => $request->hasFile('inspection_images'),
            'files_count' => $request->hasFile('inspection_images') ? count($request->file('inspection_images')) : 0
        ]);

        // Handle file uploads
        if ($request->hasFile('inspection_images')) {
            $uploadedFiles = $request->file('inspection_images');
            $savedCount = 0;

            foreach ($uploadedFiles as $index => $file) {
                try {
                    // Validate file
                    if (!$file->isValid()) {
                        \Log::warning('Invalid file skipped', ['index' => $index, 'error' => $file->getErrorMessage()]);
                        continue;
                    }

                    // Generate unique filename
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileName = time() . '_' . $index . '_' . \Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;

                    // Store file in storage/app/public/inspection_images
                    $filePath = $file->storeAs('inspection_images', $fileName, 'public');

                    // Get description from request (if provided)
                    $description = $request->input("image_descriptions.{$index}", '');

                    // Save to database
                    $imageRecord = \App\Models\InspectionImage::create([
                        'inspection_id' => $inspection->id,
                        'original_name' => $originalName,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'caption' => $description,
                        'sort_order' => $index + 1
                    ]);

                    $savedCount++;
                    \Log::info('Image saved successfully', [
                        'id' => $imageRecord->id,
                        'file_name' => $originalName,
                        'file_path' => $filePath
                    ]);

                } catch (\Exception $e) {
                    \Log::error('Failed to save image', [
                        'index' => $index,
                        'error' => $e->getMessage(),
                        'file_name' => $file->getClientOriginalName()
                    ]);
                }
            }

            \Log::info('Image upload completed', ['saved_count' => $savedCount]);
        }

        // Handle deletion of existing images (if any deletion requests)
        if ($request->has('delete_images')) {
            $deleteIds = $request->input('delete_images', []);
            if (!empty($deleteIds)) {
                $deletedCount = 0;
                foreach ($deleteIds as $imageId) {
                    try {
                        $image = \App\Models\InspectionImage::where('inspection_id', $inspection->id)
                                                            ->where('id', $imageId)
                                                            ->first();
                        if ($image) {
                            // Delete file from storage
                            \Storage::disk('public')->delete($image->file_path);
                            // Delete database record
                            $image->delete();
                            $deletedCount++;
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to delete image', ['id' => $imageId, 'error' => $e->getMessage()]);
                    }
                }
                \Log::info('Images deleted', ['deleted_count' => $deletedCount]);
            }
        }
    }

    /**
     * Save service-specific data (MPI and Lifting Examination)
     */
    private function saveServiceSpecificData($inspection, $request)
    {
        // Save Lifting Examination data if present
        if ($request->has('lifting_examination_inspector') || $request->has('first_examination')) {
            $liftingData = [
                'inspection_id' => $inspection->id,
                'inspector_id' => $request->input('lifting_examination_inspector'),
                'first_examination' => $request->input('first_examination'),
                'equipment_installation_details' => $request->input('equipment_installation_details'),
                'safe_to_operate' => $request->input('equipment_safe_to_operate') ? 'yes' : null,
                'equipment_defect' => $request->input('equipment_defect'),
                'defect_description' => $request->input('defect_description'),
                'existing_danger' => $request->input('existing_danger'),
                'potential_danger' => $request->input('potential_danger'),
                'defect_timeline' => $request->input('defect_timeline'),
                'repair_details' => $request->input('repair_details'),
                'test_details' => $request->input('test_details'),
            ];

            // Remove null values
            $liftingData = array_filter($liftingData, function($value) {
                return $value !== null && $value !== '';
            });

            if (!empty($liftingData) && isset($liftingData['inspection_id'])) {
                \App\Models\LiftingExamination::updateOrCreate(
                    ['inspection_id' => $inspection->id],
                    $liftingData
                );
            }
        }

        // Save MPI data if present
        if ($request->has('mpi_service_inspector') || $request->has('contrast_paint_method')) {
            $mpiData = [
                'inspection_id' => $inspection->id,
                'inspector_id' => $request->input('mpi_service_inspector'),
                'contrast_paint_method' => $request->input('contrast_paint_method'),
                'ink_powder_1_method' => $request->input('ink_powder_1_method'),
                'magnetic_particle_concentration' => $request->input('magnetic_particle_concentration'),
                'current_flow' => $request->input('current_flow'),
                'ink_powder_1_carrier' => $request->input('ink_powder_1_carrier'),
                'contact_spacing' => $request->input('contact_spacing'),
                'magnetic_flow' => $request->input('magnetic_flow'),
                'ink_powder_2_method' => $request->input('ink_powder_2_method'),
                'field_application_time' => $request->input('field_application_time'),
                'ink_powder_2_carrier' => $request->input('ink_powder_2_carrier'),
                'black_light_intensity_begin' => $request->input('black_light_intensity_begin'),
                'black_light_intensity_end' => $request->input('black_light_intensity_end'),
                'test_temperature' => $request->input('test_temperature'),
                'pull_test' => $request->input('pull_test'),
                'post_test_cleaning' => $request->input('post_test_cleaning'),
                'initial_demagnetisation' => $request->input('initial_demagnetisation'),
                'final_demagnetisation' => $request->input('final_demagnetisation'),
                'mpi_results' => $request->input('mpi_results'),
            ];

            // Remove null values
            $mpiData = array_filter($mpiData, function($value) {
                return $value !== null && $value !== '';
            });

            if (!empty($mpiData) && isset($mpiData['inspection_id'])) {
                \App\Models\MpiInspection::updateOrCreate(
                    ['inspection_id' => $inspection->id],
                    $mpiData
                );
            }
        }
    }

    /**
     * API endpoint to get equipment types
     */
    public function getEquipmentTypes()
    {
        try {
            $equipmentTypes = \DB::table('equipment_types')
                ->select('id', 'name', 'code', 'description', 'category')
                ->where('is_active', 1)
                ->orderBy('name')
                ->get();
            
            return response()->json($equipmentTypes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load equipment types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save equipment data for an inspection
     */
    public function saveEquipmentOnly(Request $request)
    {
        try {
            $inspectionId = $request->input('inspection_id');
            $equipmentData = $request->input('equipment_data');
            
            if (!$inspectionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inspection ID is required'
                ], 400);
            }
            
            // Find the inspection
            $inspection = Inspection::find($inspectionId);
            if (!$inspection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inspection not found'
                ], 404);
            }
            
            // Clear existing equipment for this inspection
            \DB::table('inspection_equipment')->where('inspection_id', $inspectionId)->delete();
            
            // Save new equipment data
            if (!empty($equipmentData)) {
                foreach ($equipmentData as $item) {
                    \DB::table('inspection_equipment')->insert([
                        'inspection_id' => $inspectionId,
                        'client_id' => $inspection->client_id,
                        'equipment_type_id' => $item['equipment_type_id'] ?: null,
                        'category' => $item['category'] ?? '',
                        'equipment_type' => $item['equipment_type'] ?? '',
                        'serial_number' => $item['serial_number'] ?? '',
                        'description' => $item['description'] ?? '',
                        'reason_for_examination' => $item['reason_for_examination'] ?? '',
                        'model' => $item['model'] ?? '',
                        'swl' => $item['swl'] ?? null,
                        'test_load_applied' => $item['test_load_applied'] ?? null,
                        'date_of_manufacture' => $item['date_of_manufacture'] ?: null,
                        'date_of_last_examination' => $item['date_of_last_examination'] ?: null,
                        'date_of_next_examination' => $item['date_of_next_examination'] ?: null,
                        'status' => $item['status'] ?? '',
                        'remarks' => $item['remarks'] ?? '',
                        'condition' => $item['condition'] ?? 'Good',
                        'metadata' => json_encode($item),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Equipment data saved successfully',
                'equipment_count' => count($equipmentData ?: [])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving equipment data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save equipment data: ' . $e->getMessage()
            ], 500);
        }
    }

}