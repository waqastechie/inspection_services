<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Inspection;
use App\Models\InspectionService;
use App\Models\PersonnelAssignment;
use App\Models\EquipmentAssignment;
use App\Models\ConsumableAssignment;
use App\Models\InspectionResult;
use App\Models\Personnel;
use App\Models\Equipment;
use App\Models\Consumable;
use App\Models\User;

class InspectionController extends Controller
{
    /**
     * Display the inspection form
     */
    public function create()
    {
        $personnel = Personnel::orderBy('first_name')->get();
        return view('inspections.create', compact('personnel'));
    }

    /**
     * Store a newly created inspection report or finalize a draft
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Client Information
            'client_name' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'area_of_examination' => 'nullable|string|max:255',
            'services_performed' => 'nullable|string|max:255',
            'contract' => 'nullable|string|max:255',
            'work_order' => 'nullable|string|max:255',
            'purchase_order' => 'nullable|string|max:255',
            'client_job_reference' => 'nullable|string|max:255',
            'job_ref' => 'nullable|string|max:255',
            'standards' => 'nullable|string|max:255',
            'local_procedure_number' => 'nullable|string|max:255',
            'drawing_number' => 'nullable|string|max:255',
            'test_restrictions' => 'nullable|string',
            'surface_condition' => 'nullable|string',
            
            // Job Details
            'inspection_date' => 'required|date',
            'lead_inspector_name' => 'nullable|string|max:255',
            'lead_inspector_certification' => 'nullable|string|max:255',
            'weather_conditions' => 'nullable|string|max:255',
            'temperature' => 'nullable|numeric',
            'humidity' => 'nullable|numeric|min:0|max:100',
            
            // Equipment Details
            'equipment_type' => 'nullable|string|max:255',
            'equipment_description' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'asset_tag' => 'nullable|string|max:255',
            'manufacture_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit' => 'nullable|string|max:50',
            
            // Certification Details
            'applicable_standard' => 'nullable|string|max:255',
            'inspection_class' => 'nullable|string|max:255',
            'certification_body' => 'nullable|string|max:255',
            'previous_certificate_number' => 'nullable|string|max:255',
            'last_inspection_date' => 'nullable|date',
            'next_inspection_due' => 'nullable|date|after_or_equal:inspection_date',
            
            // Asset Details (arrays for multiple assets)
            'asset_ref' => 'nullable|array',
            'asset_description' => 'nullable|array',
            'asset_location' => 'nullable|array',
            'capacity' => 'nullable|array',
            'last_examined' => 'nullable|array',
            'next_due' => 'nullable|array',
            
            // Items Table (arrays for multiple items)
            'item_ref' => 'nullable|array',
            'item_description' => 'nullable|array',
            'item_quantity' => 'nullable|array',
            'item_condition' => 'nullable|array',
            'item_result' => 'nullable|array',
            'item_remarks' => 'nullable|array',
            
            // Consumables (arrays for multiple consumables)
            'consumable_description' => 'nullable|array',
            'consumable_quantity' => 'nullable|array',
            'consumable_unit' => 'nullable|array',
            'consumable_batch_number' => 'nullable|array',
            
            // Personnel (arrays for multiple personnel)
            'personnel_name' => 'nullable|array',
            'personnel_company' => 'nullable|array',
            'personnel_qualification' => 'nullable|array',
            'personnel_responsibilities' => 'nullable|array',
            
            // Service Inspector Assignments
            'lifting_examination_inspector' => 'nullable|exists:personnels,id',
            'load_test_inspector' => 'nullable|exists:personnels,id',
            'thorough_examination_inspector' => 'nullable|exists:personnels,id',
            'mpi_service_inspector' => 'nullable|exists:personnels,id',
            'visual_inspector' => 'nullable|exists:personnels,id',
            
            // Comments
            'inspector_comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'defects_found' => 'nullable|string',
            'overall_result' => 'nullable|string|max:255',
            'next_inspection_date' => 'nullable|date',
            
            // Additional fields
            'inspection_id' => 'nullable|integer|exists:inspections,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Check if we're finalizing an existing auto-saved inspection
            if ($request->inspection_id) {
                $inspection = Inspection::findOrFail($request->inspection_id);
                
                // Prepare update data with new simplified fields
                $updateData = [
                    'client_name' => $request->client_name,
                    'project_name' => $request->project_name,
                    'location' => $request->location,
                    'inspection_date' => $request->inspection_date,
                    'lead_inspector_name' => $request->filled('lead_inspector_name') ? $request->lead_inspector_name : 'Inspector',
                    'lead_inspector_certification' => $request->filled('lead_inspector_certification') ? $request->lead_inspector_certification : 'Certified Inspector',
                    'equipment_type' => $request->filled('equipment_type') ? $request->equipment_type : 'TBD',
                    'equipment_description' => $request->filled('equipment_description') ? $request->equipment_description : 'General Equipment',
                    'status' => 'completed', // Changed from 'submitted'
                    'report_date' => now(),
                ];
                
                // Add optional fields only if they have values
                $safeOptionalFields = [
                    'area_of_examination', 'services_performed', 'contract', 'work_order', 'purchase_order',
                    'client_job_reference', 'job_ref', 'standards', 'local_procedure_number', 
                    'drawing_number', 'test_restrictions', 'surface_condition',
                    'weather_conditions', 'temperature', 'humidity',
                    'equipment_type', 'equipment_description', 'manufacturer', 'model', 'serial_number',
                    'asset_tag', 'manufacture_year', 'capacity', 'capacity_unit',
                    'applicable_standard', 'inspection_class', 'certification_body', 
                    'previous_certificate_number', 'last_inspection_date', 'next_inspection_due',
                    'inspector_comments', 'recommendations', 'defects_found', 'overall_result', 
                    'next_inspection_date', 'limitations', 'inspector_signature', 'general_notes'
                ];
                
                foreach ($safeOptionalFields as $field) {
                    if ($request->filled($field)) {
                        $updateData[$field] = $request->$field;
                    }
                }
                
                $inspection->update($updateData);
                
                // Clear existing related data
                $inspection->services()->delete();
                $inspection->personnelAssignments()->delete();
                $inspection->equipmentAssignments()->delete();
                $inspection->consumableAssignments()->delete();
            } else {
                // Create a new inspection record with simplified fields
                $createData = [
                    'inspection_number' => Inspection::generateInspectionNumber(),
                    'client_name' => $request->client_name,
                    'project_name' => $request->project_name,
                    'location' => $request->location,
                    'inspection_date' => $request->inspection_date,
                    'lead_inspector_name' => $request->filled('lead_inspector_name') ? $request->lead_inspector_name : 'Inspector',
                    'lead_inspector_certification' => $request->filled('lead_inspector_certification') ? $request->lead_inspector_certification : 'Certified Inspector',
                    'status' => 'completed', // Changed from 'submitted'
                    'report_date' => now(),
                ];
                
                // Add optional fields only if they have values
                $safeOptionalFields = [
                    'area_of_examination', 'services_performed', 'standards', 'contract', 'work_order', 
                    'purchase_order', 'client_job_reference', 'job_ref', 'local_procedure_number', 
                    'drawing_number', 'test_restrictions', 'surface_condition',
                    'weather_conditions', 'temperature', 'humidity',
                    'equipment_type', 'equipment_description', 'manufacturer', 'model', 'serial_number',
                    'asset_tag', 'manufacture_year', 'capacity', 'capacity_unit',
                    'applicable_standard', 'inspection_class', 'certification_body', 
                    'previous_certificate_number', 'last_inspection_date', 'next_inspection_due',
                    'inspector_comments', 'recommendations', 'defects_found', 'overall_result', 
                    'next_inspection_date', 'limitations', 'inspector_signature', 'general_notes',
                    'lifting_examination_inspector', 'load_test_inspector', 'thorough_examination_inspector',
                    'mpi_service_inspector', 'visual_inspector'
                ];
                
                foreach ($safeOptionalFields as $field) {
                    if ($request->filled($field)) {
                        $createData[$field] = $request->$field;
                    }
                }
                
                $inspection = Inspection::create($createData);
            }

            // Store asset details from arrays
            if ($request->has('asset_ref') && is_array($request->asset_ref)) {
                foreach ($request->asset_ref as $index => $ref) {
                    if (!empty($ref)) {
                        EquipmentAssignment::create([
                            'inspection_id' => $inspection->id,
                            'equipment_name' => $ref,
                            'equipment_type' => 'asset',
                            'equipment_description' => $request->asset_description[$index] ?? null,
                            'location' => $request->asset_location[$index] ?? null,
                            'capacity' => $request->capacity[$index] ?? null,
                            'last_inspection_date' => $request->last_examined[$index] ?? null,
                            'next_inspection_due' => $request->next_due[$index] ?? null,
                            'status' => 'assigned',
                        ]);
                    }
                }
            }

            // Store items table data from arrays
            if ($request->has('item_ref') && is_array($request->item_ref)) {
                foreach ($request->item_ref as $index => $ref) {
                    if (!empty($ref)) {
                        InspectionResult::create([
                            'inspection_id' => $inspection->id,
                            'item_reference' => $ref,
                            'item_description' => $request->item_description[$index] ?? null,
                            'quantity' => $request->item_quantity[$index] ?? null,
                            'condition_found' => $request->item_condition[$index] ?? null,
                            'result' => $request->item_result[$index] ?? null,
                            'remarks' => $request->item_remarks[$index] ?? null,
                        ]);
                    }
                }
            }

            // Store consumables from arrays
            if ($request->has('consumable_description') && is_array($request->consumable_description)) {
                foreach ($request->consumable_description as $index => $description) {
                    if (!empty($description)) {
                        ConsumableAssignment::create([
                            'inspection_id' => $inspection->id,
                            'consumable_name' => $description,
                            'consumable_type' => 'standard',
                            'quantity_used' => $request->consumable_quantity[$index] ?? null,
                            'unit' => $request->consumable_unit[$index] ?? null,
                            'batch_lot_number' => $request->consumable_batch_number[$index] ?? null,
                        ]);
                    }
                }
            }

            // Store personnel from arrays
            if ($request->has('personnel_name') && is_array($request->personnel_name)) {
                foreach ($request->personnel_name as $index => $name) {
                    if (!empty($name)) {
                        PersonnelAssignment::create([
                            'inspection_id' => $inspection->id,
                            'personnel_name' => $name,
                            'company' => $request->personnel_company[$index] ?? null,
                            'qualification' => $request->personnel_qualification[$index] ?? null,
                            'role_position' => $request->personnel_responsibilities[$index] ?? null,
                            'status' => 'assigned',
                        ]);
                    }
                }
            }

            DB::commit();

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inspection report has been created successfully!',
                    'inspection_id' => $inspection->id,
                    'inspection_number' => $inspection->inspection_number,
                    'redirect_url' => route('inspections.show', $inspection->id)
                ]);
            }

            return redirect()->route('inspections.show', $inspection->id)
                ->with('success', 'Inspection report has been created successfully! Inspection Number: ' . $inspection->inspection_number);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while saving the inspection report: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'An error occurred while saving the inspection report: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate PDF report
     */
    public function generatePDF($id)
    {
        try {
            $inspection = Inspection::with([
                'services',
                'personnelAssignments.personnel',
                'equipmentAssignments.equipment', 
                'consumableAssignments.consumable',
                'inspectionResults'
            ])->findOrFail($id);

            // Load DomPDF using app helper
            $pdf = app('dompdf.wrapper');
            
            // Load the view with inspection data
            $pdf->loadView('pdf.inspection_report', compact('inspection'));
            
            // Set paper options
            $pdf->setPaper('A4', 'portrait');
            
            // Set options for better rendering
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
            
            // Generate filename
            $filename = 'Inspection_Report_' . $inspection->inspection_number . '_' . date('Y-m-d') . '.pdf';
            
            // Return PDF download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while generating the PDF: ' . $e->getMessage());
        }
    }

    /**
     * Preview PDF report in browser
     */
    public function previewPDF($id)
    {
        try {
            $inspection = Inspection::with([
                'services',
                'personnelAssignments.personnel',
                'equipmentAssignments.equipment', 
                'consumableAssignments.consumable',
                'inspectionResults'
            ])->findOrFail($id);

            // Load DomPDF using app helper
            $pdf = app('dompdf.wrapper');
            
            // Load the view with inspection data
            $pdf->loadView('pdf.inspection_report', compact('inspection'));
            
            // Set paper options
            $pdf->setPaper('A4', 'portrait');
            
            // Set options for better rendering
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
            
            // Stream PDF (display in browser)
            return $pdf->stream('Inspection_Report_' . $inspection->inspection_number . '.pdf');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while generating the PDF preview: ' . $e->getMessage());
        }
    }

    /**
     * Display all inspection reports
     */
    public function index()
    {
        $inspections = Inspection::with(['services', 'personnelAssignments', 'equipmentAssignments', 'consumableAssignments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('inspections.index', compact('inspections'));
    }

    /**
     * Show a specific inspection report
     */
    public function show($id)
    {
        $inspection = Inspection::with([
            'services.results',
            'personnelAssignments.personnel',
            'equipmentAssignments.equipment', 
            'consumableAssignments.consumable',
            'inspectionResults'
        ])->findOrFail($id);
        
        return view('inspections.show', compact('inspection'));
    }

    /**
     * Show the form for editing the specified inspection (Super Admin only)
     */
    public function edit($id)
    {
        $inspection = Inspection::with([
            'services',
            'personnelAssignments',
            'equipmentAssignments', 
            'consumableAssignments'
        ])->findOrFail($id);
        
        $personnel = Personnel::orderBy('first_name')->get();
        
        return view('inspections.edit', compact('inspection', 'personnel'));
    }

    /**
     * Update the specified inspection (Super Admin only)
     */
    public function update(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            // Basic Information
            'client_name' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'inspection_date' => 'required|date',
            'weather_conditions' => 'nullable|string|max:255',
            'temperature' => 'nullable|numeric',
            'humidity' => 'nullable|numeric',
            
            // Certification & Standards
            'applicable_standard' => 'nullable|string|max:255',
            'inspection_class' => 'nullable|string|max:100',
            'certification_body' => 'nullable|string|max:255',
            'previous_certificate_number' => 'nullable|string|max:255',
            'last_inspection_date' => 'nullable|date',
            'next_inspection_due' => 'nullable|date|after:inspection_date',
            
            // Inspector Information
            'lead_inspector_name' => 'required|string|max:255',
            'lead_inspector_certification' => 'required|string|max:500',
            
            // Service Inspector Assignments
            'lifting_examination_inspector' => 'nullable|exists:personnels,id',
            'load_test_inspector' => 'nullable|exists:personnels,id',
            'thorough_examination_inspector' => 'nullable|exists:personnels,id',
            'mpi_service_inspector' => 'nullable|exists:personnels,id',
            'visual_inspector' => 'nullable|exists:personnels,id',
            
            // Additional Notes
            'general_notes' => 'nullable|string',
            'status' => 'required|in:draft,in_progress,completed,cancelled',
            
            // Image uploads
            'inspection_images' => 'nullable|array',
            'inspection_images.*' => 'nullable|string', // JSON strings from frontend
            
            // Services, Personnel, Equipment, Consumables (JSON)
            'selected_services' => 'nullable|json',
            'personnel_assignments' => 'nullable|json',
            'equipment_assignments' => 'nullable|json',
            'consumable_assignments' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update the main inspection record
            $inspection->update([
                'client_name' => $request->client_name,
                'project_name' => $request->project_name,
                'location' => $request->location,
                'inspection_date' => $request->inspection_date,
                'weather_conditions' => $request->weather_conditions,
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'applicable_standard' => $request->applicable_standard,
                'inspection_class' => $request->inspection_class,
                'certification_body' => $request->certification_body,
                'previous_certificate_number' => $request->previous_certificate_number,
                'last_inspection_date' => $request->last_inspection_date,
                'next_inspection_due' => $request->next_inspection_due,
                'lead_inspector_name' => $request->lead_inspector_name,
                'lead_inspector_certification' => $request->lead_inspector_certification,
                'lifting_examination_inspector' => $request->lifting_examination_inspector,
                'load_test_inspector' => $request->load_test_inspector,
                'thorough_examination_inspector' => $request->thorough_examination_inspector,
                'mpi_service_inspector' => $request->mpi_service_inspector,
                'visual_inspector' => $request->visual_inspector,
                'general_notes' => $request->general_notes,
                'status' => $request->status,
                'inspection_images' => $request->inspection_images ? json_encode($request->inspection_images) : null,
            ]);

            // Delete existing related records and recreate them
            $inspection->services()->delete();
            $inspection->personnelAssignments()->delete();
            $inspection->equipmentAssignments()->delete();
            $inspection->consumableAssignments()->delete();

            // Store updated services
            if ($request->selected_services) {
                $services = json_decode($request->selected_services, true);
                foreach ($services as $service) {
                    InspectionService::create([
                        'inspection_id' => $inspection->id,
                        'service_type' => $service['type'],
                        'service_name' => $service['name'],
                        'service_description' => $service['description'] ?? null,
                        'test_parameters' => $service['parameters'] ?? null,
                        'acceptance_criteria' => $service['criteria'] ?? null,
                        'applicable_codes' => $service['codes'] ?? null,
                        'estimated_duration' => $service['duration'] ?? null,
                        'cost_estimate' => $service['cost'] ?? null,
                        'status' => $service['status'] ?? 'planned',
                    ]);
                }
            }

            // Store updated personnel assignments
            if ($request->personnel_assignments) {
                $personnel = json_decode($request->personnel_assignments, true);
                foreach ($personnel as $person) {
                    PersonnelAssignment::create([
                        'inspection_id' => $inspection->id,
                        'personnel_name' => $person['name'],
                        'role_position' => $person['role'],
                        'certification_level' => $person['certification'] ?? null,
                        'certification_number' => $person['cert_number'] ?? null,
                        'certification_expiry' => $person['cert_expiry'] ?? null,
                        'assigned_services' => $person['services'] ?? [],
                        'contact_information' => $person['contact'] ?? null,
                        'availability_start' => $person['start_time'] ?? null,
                        'availability_end' => $person['end_time'] ?? null,
                        'hourly_rate' => $person['rate'] ?? null,
                        'status' => 'assigned',
                    ]);
                }
            }

            // Store updated equipment assignments
            if ($request->equipment_assignments) {
                $equipment = json_decode($request->equipment_assignments, true);
                foreach ($equipment as $item) {
                    EquipmentAssignment::create([
                        'inspection_id' => $inspection->id,
                        'equipment_name' => $item['name'],
                        'equipment_type' => $item['type'],
                        'brand_model' => $item['brand'] ?? null,
                        'serial_number' => $item['serial'] ?? null,
                        'calibration_date' => $item['cal_date'] ?? null,
                        'calibration_due' => $item['cal_due'] ?? null,
                        'calibration_certificate' => $item['cal_cert'] ?? null,
                        'assigned_services' => $item['services'] ?? [],
                        'condition' => $item['condition'] ?? 'good',
                        'usage_hours' => $item['hours'] ?? null,
                        'maintenance_notes' => $item['notes'] ?? null,
                        'status' => 'assigned',
                    ]);
                }
            }

            // Store updated consumable assignments
            if ($request->consumable_assignments) {
                $consumables = json_decode($request->consumable_assignments, true);
                foreach ($consumables as $item) {
                    ConsumableAssignment::create([
                        'inspection_id' => $inspection->id,
                        'consumable_name' => $item['name'],
                        'consumable_type' => $item['type'],
                        'brand_manufacturer' => $item['brand'] ?? null,
                        'product_code' => $item['code'] ?? null,
                        'batch_lot_number' => $item['batch'] ?? null,
                        'expiry_date' => $item['expiry'] ?? null,
                        'quantity_used' => $item['quantity'] ?? null,
                        'unit' => $item['unit'] ?? null,
                        'unit_cost' => $item['cost'] ?? null,
                        'supplier' => $item['supplier'] ?? null,
                        'assigned_services' => $item['services'] ?? [],
                        'condition' => $item['condition'] ?? 'new',
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('inspections.show', $inspection->id)
                ->with('success', 'Inspection report has been updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while updating the inspection report: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified inspection (Super Admin only)
     */
    public function destroy($id)
    {
        $inspection = Inspection::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Delete all related records first
            $inspection->services()->delete();
            $inspection->personnelAssignments()->delete();
            $inspection->equipmentAssignments()->delete();
            $inspection->consumableAssignments()->delete();
            $inspection->inspectionResults()->delete();
            
            // Delete the inspection itself
            $inspection->delete();
            
            DB::commit();
            
            return redirect()->route('inspections.index')
                ->with('success', 'Inspection report has been deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the inspection report: ' . $e->getMessage());
        }
    }

    /**
     * Save inspection draft to database (enhanced auto-save)
     */
    public function saveDraft(Request $request)
    {
        try {
            $draftId = $request->input('draft_id');
            $session = $request->session()->getId();
            $ipAddress = $request->ip();

            // Find existing draft or create new one
            if ($draftId) {
                $draft = \App\Models\InspectionDraft::findByDraftId($draftId, $session);
            }

            if (!isset($draft) || !$draft) {
                $draft = new \App\Models\InspectionDraft();
                $draft->draft_id = \App\Models\InspectionDraft::generateDraftId();
                $draft->user_session = $session;
                $draft->ip_address = $ipAddress;
            }

            // Update draft data
            $draft->form_data = $request->input('form_data');
            $draft->selected_services = $request->input('selected_services', []);
            $draft->personnel_assignments = $request->input('personnel_assignments', []);
            $draft->equipment_assignments = $request->input('equipment_assignments', []);
            $draft->consumable_assignments = $request->input('consumable_assignments', []);
            $draft->uploaded_images = $request->input('uploaded_images', []);
            $draft->service_sections_data = $request->input('service_sections_data', []);
            $draft->updated_at = now();

            $draft->save();

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully',
                'draft_id' => $draft->draft_id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inspection draft from database
     */
    public function getDraft($draftId, Request $request)
    {
        try {
            $session = $request->session()->getId();
            $draft = \App\Models\InspectionDraft::findByDraftId($draftId, $session);

            if (!$draft) {
                return response()->json([
                    'success' => false,
                    'message' => 'Draft not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'draft' => [
                    'draft_id' => $draft->draft_id,
                    'form_data' => $draft->form_data,
                    'selected_services' => $draft->selected_services ?? [],
                    'personnel_assignments' => $draft->personnel_assignments ?? [],
                    'equipment_assignments' => $draft->equipment_assignments ?? [],
                    'consumable_assignments' => $draft->consumable_assignments ?? [],
                    'uploaded_images' => $draft->uploaded_images ?? [],
                    'service_sections_data' => $draft->service_sections_data ?? [],
                    'last_saved_at' => $draft->updated_at->toISOString(),
                    'version' => 1
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete inspection draft
     */
    public function deleteDraft($draftId, Request $request)
    {
        try {
            $session = $request->session()->getId();
            $draft = \App\Models\InspectionDraft::findByDraftId($draftId, $session);

            if (!$draft) {
                return response()->json([
                    'success' => false,
                    'message' => 'Draft not found'
                ], 404);
            }

            $draft->delete();

            return response()->json([
                'success' => true,
                'message' => 'Draft deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing draft
     */
    public function updateDraft(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $inspection = Inspection::findOrFail($id);
            
            // Update only fields that have values (FIXED - removed problematic fields)
            $updateData = [];
            $allFields = [
                'client_name', 'project_name', 'location', 'inspection_date',
                'weather_conditions', 'temperature', 'humidity', 'equipment_type',
                'equipment_description', 'manufacturer', 'model', 'serial_number',
                'asset_tag', 'manufacture_year', 'capacity', 'capacity_unit',
                'certification_body', 'previous_certificate_number', 'last_inspection_date', 
                'next_inspection_due', 'lead_inspector_name', 'lead_inspector_certification', 'general_notes',
                'lifting_examination_inspector', 'load_test_inspector', 'thorough_examination_inspector',
                'mpi_service_inspector', 'visual_inspector'
            ];

            foreach ($allFields as $field) {
                if ($request->filled($field)) {
                    $updateData[$field] = $request->$field;
                }
            }

            $inspection->update($updateData);

            // Clear and re-store related data
            $inspection->services()->delete();
            $inspection->personnelAssignments()->delete();
            $inspection->equipmentAssignments()->delete();
            $inspection->consumableAssignments()->delete();

            $this->storeRelatedData($inspection, $request);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Auto-save updated successfully',
                'inspection_id' => $inspection->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating auto-save: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to store related data
     */
    private function storeRelatedData($inspection, $request)
    {
        // Store selected services
        if ($request->selected_services) {
            $services = json_decode($request->selected_services, true);
            if (is_array($services)) {
                foreach ($services as $service) {
                    InspectionService::create([
                        'inspection_id' => $inspection->id,
                        'service_type' => $service['type'] ?? '',
                        'service_name' => $service['name'] ?? '',
                        'service_description' => $service['description'] ?? null,
                        'test_parameters' => $service['parameters'] ?? null,
                        'acceptance_criteria' => $service['criteria'] ?? null,
                        'applicable_codes' => $service['codes'] ?? null,
                        'estimated_duration' => $service['duration'] ?? null,
                        'cost_estimate' => $service['cost'] ?? null,
                        'status' => 'planned',
                    ]);
                }
            }
        }

        // Store personnel assignments
        if ($request->personnel_assignments) {
            $personnel = json_decode($request->personnel_assignments, true);
            if (is_array($personnel)) {
                foreach ($personnel as $person) {
                    PersonnelAssignment::create([
                        'inspection_id' => $inspection->id,
                        'personnel_name' => $person['name'] ?? '',
                        'role_position' => $person['role'] ?? '',
                        'certification_level' => $person['certification'] ?? null,
                        'certification_number' => $person['cert_number'] ?? null,
                        'certification_expiry' => $person['cert_expiry'] ?? null,
                        'assigned_services' => $person['services'] ?? [],
                        'contact_information' => $person['contact'] ?? null,
                        'availability_start' => $person['start_time'] ?? null,
                        'availability_end' => $person['end_time'] ?? null,
                        'hourly_rate' => $person['rate'] ?? null,
                    ]);
                }
            }
        }

        // Store equipment assignments
        if ($request->equipment_assignments) {
            $equipment = json_decode($request->equipment_assignments, true);
            if (is_array($equipment)) {
                foreach ($equipment as $item) {
                    EquipmentAssignment::create([
                        'inspection_id' => $inspection->id,
                        'equipment_name' => $item['name'] ?? '',
                        'equipment_type' => $item['type'] ?? '',
                        'brand_model' => $item['brand'] ?? null,
                        'serial_number' => $item['serial'] ?? null,
                        'calibration_date' => $item['cal_date'] ?? null,
                        'calibration_due' => $item['cal_due'] ?? null,
                        'calibration_certificate' => $item['cal_cert'] ?? null,
                        'assigned_services' => $item['services'] ?? [],
                        'condition' => $item['condition'] ?? null,
                        'usage_hours' => $item['hours'] ?? null,
                        'maintenance_notes' => $item['notes'] ?? null,
                    ]);
                }
            }
        }

        // Store consumable assignments
        if ($request->consumable_assignments) {
            $consumables = json_decode($request->consumable_assignments, true);
            if (is_array($consumables)) {
                foreach ($consumables as $item) {
                    ConsumableAssignment::create([
                        'inspection_id' => $inspection->id,
                        'consumable_name' => $item['name'] ?? '',
                        'consumable_type' => $item['type'] ?? '',
                        'brand_manufacturer' => $item['brand'] ?? null,
                        'product_code' => $item['code'] ?? null,
                        'batch_lot_number' => $item['batch'] ?? null,
                        'expiry_date' => $item['expiry'] ?? null,
                        'quantity_used' => $item['quantity'] ?? null,
                        'unit' => $item['unit'] ?? null,
                        'unit_cost' => $item['cost'] ?? null,
                        'supplier' => $item['supplier'] ?? null,
                        'assigned_services' => $item['services'] ?? [],
                        'condition' => $item['condition'] ?? null,
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }
        }
    }

    /**
     * Get personnel data for dropdowns
     */
    public function getPersonnel()
    {
        $personnel = Personnel::where('is_active', true)
            ->select('id', 'first_name', 'last_name', 'position', 'department', 'qualifications', 'certifications', 'phone', 'email')
            ->orderBy('first_name')
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->first_name . ' ' . $person->last_name,
                    'position' => $person->position,
                    'department' => $person->department,
                    'qualifications' => $person->qualifications,
                    'certifications' => $person->certifications,
                    'contact' => $person->phone . ($person->email ? ' / ' . $person->email : ''),
                ];
            });

        return response()->json($personnel);
    }
    
    public function getInspectors()
    {
        try {
            // Get all users with inspector, admin, or super_admin roles
            $inspectors = User::whereIn('role', ['inspector', 'admin', 'super_admin'])
                ->select('id', 'name', 'email', 'certification', 'department', 'role')
                ->orderBy('name')
                ->get()
                ->map(function($inspector) {
                    return [
                        'id' => $inspector->id,
                        'name' => $inspector->name,
                        'email' => $inspector->email,
                        'certification' => $inspector->certification ?? '',
                        'department' => $inspector->department ?? '',
                        'role' => $inspector->role,
                        'display_name' => $inspector->name . ' (' . ucfirst(str_replace('_', ' ', $inspector->role)) . ')',
                    ];
                });

            return response()->json($inspectors);
        } catch (\Exception $e) {
            \Log::error('Error in getInspectors: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load inspectors: ' . $e->getMessage()], 500);
        }
    }
}
