
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
     * Store a newly created inspection report or finalize a draft
     */
    public function store(StoreInspectionRequest $request)
    {
        \Log::info('=== STORE METHOD CALLED ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request URL: ' . $request->url());
        
        $validator = Validator::make($request->all(), [
            // Client Information
            'client_id' => 'required|exists:clients,id',
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
            'serial_number' => 'nullable|array',
            'capacity' => 'nullable|array',
            'last_examined' => 'nullable|array',
            'next_due' => 'nullable|array',
            
            // Asset Details (JSON array format)
            'assets' => 'nullable|array',
            
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
            
            // New dynamic data fields
            'selected_services' => 'nullable|string',
            'service_sections_data' => 'nullable|string',
            'personnel_assignments' => 'nullable|string',
            'equipment_assignments' => 'nullable|string',
            'consumable_assignments' => 'nullable|string',
            
            // Lifting Examination specific fields
            'first_examination' => 'nullable|string|in:yes,no',
            'equipment_installation_details' => 'nullable|string',
            'safe_to_operate' => 'nullable|string|in:yes,no',
            'equipment_defect' => 'nullable|string|in:yes,no',
            'defect_description' => 'nullable|string',
            'existing_danger' => 'nullable|string',
            'potential_danger' => 'nullable|string',
            'defect_timeline' => 'nullable|date',
            'repair_details' => 'nullable|string',
            'test_details' => 'nullable|string',
            
            // MPI Service specific fields
            'mpi_method' => 'nullable|string',
            'mpi_concentration' => 'nullable|string',
            'mpi_equipment' => 'nullable|string',
            'mpi_conditions' => 'nullable|string',
            'mpi_results' => 'nullable|string',
            
            // Visual Examination specific fields
            'visual_method' => 'nullable|string',
            'visual_lighting' => 'nullable|string',
            'visual_equipment' => 'nullable|string',
            'visual_conditions' => 'nullable|string',
            'visual_results' => 'nullable|string',
            
            // Thorough Examination specific fields
            'thorough_method' => 'nullable|string',
            'thorough_equipment' => 'nullable|string',
            'thorough_conditions' => 'nullable|string',
            'thorough_results' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::error('=== VALIDATION FAILED ===');
            \Log::error('Validation errors: ' . json_encode($validator->errors()->all()));
            \Log::error('Failed fields: ' . json_encode($validator->errors()->keys()));
            \Log::error('Request data keys: ' . json_encode(array_keys($request->all())));
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 🔍 DEBUG: Log all incoming request data
        \Log::info('=== INSPECTION STORE DEBUG START ===');
        \Log::info('Request Method: ' . $request->method());
        \Log::info('Request URL: ' . $request->fullUrl());
        \Log::info('User: ' . (auth()->user() ? auth()->user()->email : 'Not logged in'));
        
        // Log the complex data we're interested in
        \Log::info('Selected Services Raw: ' . $request->input('selected_services', 'NOT_SET'));
        \Log::info('Service Sections Data Raw: ' . $request->input('service_sections_data', 'NOT_SET'));
        \Log::info('Personnel Assignments Raw: ' . $request->input('personnel_assignments', 'NOT_SET'));
        \Log::info('Equipment Assignments Raw: ' . $request->input('equipment_assignments', 'NOT_SET'));
        \Log::info('Consumable Assignments Raw: ' . $request->input('consumable_assignments', 'NOT_SET'));
        
        // Try to decode JSON data
        if ($request->has('selected_services')) {
            $services = is_string($request->selected_services) ? json_decode($request->selected_services, true) : $request->selected_services;
            \Log::info('Selected Services Decoded: ' . json_encode($services));
        }
        
        if ($request->has('service_sections_data')) {
            $sectionsData = is_string($request->service_sections_data) ? json_decode($request->service_sections_data, true) : $request->service_sections_data;
            \Log::info('Service Sections Data Decoded: ' . json_encode($sectionsData));
        }
        
        \Log::info('=== INSPECTION STORE DEBUG END ===');

        // 🔍 DEBUG: Check what happens after validation
        \Log::info('=== VALIDATION PASSED - PROCEEDING TO STORE ===');
        \Log::info('Request ID present: ' . ($request->has('inspection_id') ? 'YES' : 'NO'));
        if ($request->has('inspection_id')) {
            \Log::info('Inspection ID: ' . $request->inspection_id);
        }

        try {
            DB::beginTransaction();
            \Log::info('=== DATABASE TRANSACTION STARTED ===');

            // Check if we're finalizing an existing auto-saved inspection
            if ($request->inspection_id) {
                \Log::info('=== UPDATING EXISTING INSPECTION ===');
                \Log::info('Inspection ID: ' . $request->inspection_id);
                $inspection = Inspection::findOrFail($request->inspection_id);
                
                // Prepare update data with new simplified fields
                $updateData = [
                    'client_id' => $request->client_id,
                    'project_name' => $request->project_name,
                    'location' => $request->location,
                    'inspection_date' => now()->format('Y-m-d'), // Always use current date
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
                    'next_inspection_date', 'limitations', 'inspector_signature', 'general_notes',
                    'lifting_examination_inspector', 'load_test_inspector', 'thorough_examination_inspector',
                    'mpi_service_inspector', 'visual_inspector'
                ];
                
                foreach ($safeOptionalFields as $field) {
                    if ($request->has($field) && $request->input($field) !== null) {
                        $updateData[$field] = $request->$field;
                    }
                }
                
                $inspection->update($updateData);
                
                // Handle uploaded images using the new service
                if ($request->has('uploaded_images') && is_array($request->uploaded_images)) {
                    $this->imageUploadService->updateImages($inspection, $request->uploaded_images);
                } elseif ($request->has('inspection_images') && is_array($request->inspection_images)) {
                    $this->imageUploadService->updateImages($inspection, $request->inspection_images);
                }
                
                // Clear existing related data
                $inspection->services()->delete();
                $inspection->personnelAssignments()->delete();
                $inspection->equipmentAssignments()->delete();
                $inspection->consumableAssignments()->delete();
            } else {
                \Log::info('=== CREATING NEW INSPECTION ===');
                // Create a new inspection record with simplified fields
                $createData = [
                    'inspection_number' => Inspection::generateInspectionNumber(),
                    'client_id' => $request->client_id,
                    'project_name' => $request->project_name,
                    'location' => $request->location,
                    'inspection_date' => now()->format('Y-m-d'), // Always use current date
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
                    if ($request->has($field) && $request->input($field) !== null) {
                        $createData[$field] = $request->$field;
                    }
                }
                
                \Log::info('=== INSPECTION CREATE DATA ===');
                \Log::info('Create data: ' . json_encode($createData));
                
                $inspection = Inspection::create($createData);
                \Log::info('=== INSPECTION CREATED ===');
                \Log::info('New inspection ID: ' . $inspection->id);
                
                // Handle uploaded images using the new service
                if ($request->has('uploaded_images') && is_array($request->uploaded_images)) {
                    $this->imageUploadService->storeImages($inspection, $request->uploaded_images);
                } elseif ($request->has('inspection_images') && is_array($request->inspection_images)) {
                    $this->imageUploadService->storeImages($inspection, $request->inspection_images);
                }
            }

            // Store asset details from arrays (OLD FORMAT - for backward compatibility)
            if ($request->has('asset_ref') && is_array($request->asset_ref)) {
                foreach ($request->asset_ref as $index => $ref) {
                    if (!empty($ref)) {
                        EquipmentAssignment::create([
                            'inspection_id' => $inspection->id,
                            'equipment_id' => 'asset_' . $index,
                            'equipment_name' => $ref,
                            'equipment_type' => 'asset',
                            'make_model' => $request->asset_description[$index] ?? null,
                            'serial_number' => $request->serial_number[$index] ?? null,
                            'condition' => 'good',
                            'calibration_status' => 'not_required',
                            'last_calibration_date' => $request->last_examined[$index] ?? null,
                            'next_calibration_date' => $request->next_due[$index] ?? null,
                            'assigned_services' => ['visual_inspection'],
                            'notes' => null,
                        ]);
                    }
                }
            }

            // Store asset details from JSON array (NEW FORMAT) - Equipment Types
            if ($request->has('assets')) {
                $assets = $request->input('assets');
                \Log::info('Processing equipment types (assets) data', ['assets' => $assets]);
                foreach ($assets as $index => $assetJson) {
                    if (is_string($assetJson)) {
                        $asset = json_decode($assetJson, true);
                        if ($asset && !empty($asset['asset_ref'])) {
                            $assetData = [
                                'inspection_id' => $inspection->id,
                                'equipment_id' => 'equipment_type_' . ($asset['id'] ?? $index),
                                'equipment_name' => $asset['asset_ref'], // Gas Rack, Wire Rope, etc.
                                'equipment_type' => 'equipment_type', // Mark as equipment type category
                                'make_model' => $asset['asset_description'] ?? null,
                                'serial_number' => $asset['serial_number'] ?? null,
                                'condition' => 'good',
                                'calibration_status' => 'not_required',
                                'last_calibration_date' => $asset['last_examined'] ?? null,
                                'next_calibration_date' => $asset['next_due'] ?? null,
                                'assigned_services' => ['visual_inspection'],
                                'notes' => $asset['capacity'] ? 'Capacity: ' . $asset['capacity'] : null,
                            ];
                            \Log::info('Creating equipment type assignment', ['data' => $assetData]);
                            EquipmentAssignment::create($assetData);
                        }
                    }
                }
            }

            // Store selected services (NEW FORMAT)
            if ($request->has('selected_services')) {
                \Log::info('=== PROCESSING SELECTED SERVICES ===');
                \Log::info('Raw selected_services: ' . $request->selected_services);
                
                $services = $request->selected_services;
                
                // Ensure services is an array
                if (is_string($services)) {
                    $services = json_decode($services, true);
                    \Log::info('Decoded services from string: ' . json_encode($services));
                }
                
                $serviceSectionsData = null;
                
                // Get service sections data if available
                if ($request->has('service_sections_data')) {
                    $serviceSectionsData = is_string($request->service_sections_data) 
                        ? json_decode($request->service_sections_data, true) 
                        : $request->service_sections_data;
                    \Log::info('Service sections data: ' . json_encode($serviceSectionsData));
                }
                
                if (is_array($services)) {
                    \Log::info('Processing ' . count($services) . ' services');
                    foreach ($services as $serviceData) {
                        $service = is_string($serviceData) ? json_decode($serviceData, true) : $serviceData;
                        if ($service && is_array($service)) {
                            try {
                                \Log::info('Creating service: ' . json_encode($service));
                                
                                // Get detailed service data for this service type
                                $detailedServiceData = [
                                    'name' => $service['name'] ?? '',
                                    'description' => $service['description'] ?? null,
                                    'parameters' => $service['parameters'] ?? null,
                                    'criteria' => $service['criteria'] ?? null,
                                    'codes' => $service['codes'] ?? null,
                                    'duration' => $service['duration'] ?? null,
                                    'cost' => $service['cost'] ?? null,
                                ];
                                
                                // Add service-specific detailed data from section_data
                                $serviceType = $service['type'] ?? 'general';
                                if ($serviceSectionsData && isset($serviceSectionsData['mpi']) && $serviceType === 'mpi-service') {
                                    $detailedServiceData['section_data'] = $serviceSectionsData['mpi'];
                                } elseif ($serviceSectionsData && isset($serviceSectionsData[$serviceType])) {
                                    $detailedServiceData['section_data'] = $serviceSectionsData[$serviceType];
                                }
                                
                                // Also capture individual form fields for this service type
                                if ($serviceType === 'lifting-examination') {
                                    $detailedServiceData['form_fields'] = [
                                        'lifting_examination_inspector' => $request->input('lifting_examination_inspector'),
                                        'first_examination' => $request->input('first_examination'),
                                        'equipment_installation_details' => $request->input('equipment_installation_details'),
                                        'safe_to_operate' => $request->input('safe_to_operate'),
                                        'equipment_defect' => $request->input('equipment_defect'),
                                        'defect_description' => $request->input('defect_description'),
                                        'existing_danger' => $request->input('existing_danger'),
                                        'potential_danger' => $request->input('potential_danger'),
                                        'defect_timeline' => $request->input('defect_timeline'),
                                        'repair_details' => $request->input('repair_details'),
                                        'test_details' => $request->input('test_details'),
                                    ];
                                } elseif ($serviceType === 'mpi-service') {
                                    $detailedServiceData['form_fields'] = [
                                        'mpi_service_inspector' => $request->input('mpi_service_inspector'),
                                        'mpi_method' => $request->input('mpi_method'),
                                        'mpi_concentration' => $request->input('mpi_concentration'),
                                        'mpi_equipment' => $request->input('mpi_equipment'),
                                        'mpi_conditions' => $request->input('mpi_conditions'),
                                        'mpi_results' => $request->input('mpi_results'),
                                    ];
                                } elseif ($serviceType === 'visual-examination') {
                                    $detailedServiceData['form_fields'] = [
                                        'visual_inspector' => $request->input('visual_inspector'),
                                        'visual_method' => $request->input('visual_method'),
                                        'visual_lighting' => $request->input('visual_lighting'),
                                        'visual_equipment' => $request->input('visual_equipment'),
                                        'visual_conditions' => $request->input('visual_conditions'),
                                        'visual_results' => $request->input('visual_results'),
                                    ];
                                } elseif ($serviceType === 'thorough-examination') {
                                    $detailedServiceData['form_fields'] = [
                                        'thorough_examination_inspector' => $request->input('thorough_examination_inspector'),
                                        'thorough_method' => $request->input('thorough_method'),
                                        'thorough_equipment' => $request->input('thorough_equipment'),
                                        'thorough_conditions' => $request->input('thorough_conditions'),
                                        'thorough_results' => $request->input('thorough_results'),
                                    ];
                                }
                                
                                // Prefer 'services' table if exists
                                $payload = [
                                    'inspection_id' => $inspection->id,
                                    'service_type' => $serviceType,
                                    'service_data' => $detailedServiceData,
                                    'notes' => $service['notes'] ?? null,
                                ];
                                try {
                                    if (\Schema::hasTable('services')) {
                                        Service::create($payload);
                                    } else {
                                        // Keep backward compatibility with inspection_services
                                        InspectionService::create([
                                            'inspection_id' => $inspection->id,
                                            'service_type' => $serviceType,
                                            'service_data' => json_encode($detailedServiceData),
                                            'notes' => $service['notes'] ?? null,
                                        ]);
                                    }
                                } catch (\Throwable $ex) {
                                    // As last resort, try legacy table
                                    InspectionService::create([
                                        'inspection_id' => $inspection->id,
                                        'service_type' => $serviceType,
                                        'service_data' => json_encode($detailedServiceData),
                                        'notes' => $service['notes'] ?? null,
                                    ]);
                                }
                                
                                \Log::info('✅ Service created successfully', [
                                    'inspection_id' => $inspection->id,
                                    'service_type' => $serviceType,
                                    'has_section_data' => isset($serviceSectionsData[$serviceType]),
                                ]);
                                
                            } catch (\Exception $e) {
                                \Log::error('Failed to create service', [
                                    'error' => $e->getMessage(),
                                    'service_data' => $service
                                ]);
                            }
                        }
                    }
                }
            }

            // Store equipment assignments (NEW FORMAT)
            if ($request->has('equipment_assignments')) {
                $equipmentAssignments = $request->equipment_assignments;
                if (is_array($equipmentAssignments)) {
                    foreach ($equipmentAssignments as $equipment) {
                        if (is_array($equipment) && !empty($equipment['name'])) {
                            EquipmentAssignment::create([
                                'inspection_id' => $inspection->id,
                                'equipment_id' => $equipment['equipment_id'] ?? $equipment['id'] ?? uniqid('EQ_'),
                                'equipment_name' => $equipment['equipment_name'] ?? $equipment['name'] ?? 'Unknown Equipment',
                                'equipment_type' => $equipment['equipment_type'] ?? $equipment['type'] ?? 'general',
                                'make_model' => $equipment['make_model'] ?? $equipment['brand'] ?? null,
                                'serial_number' => $equipment['serial_number'] ?? $equipment['serial'] ?? null,
                                'condition' => $equipment['condition'] ?? 'good',
                                'calibration_status' => $equipment['calibration_status'] ?? $equipment['cal_status'] ?? 'current',
                                'last_calibration_date' => $equipment['last_calibration_date'] ?? $equipment['cal_date'] ?? null,
                                'next_calibration_date' => $equipment['next_calibration_date'] ?? $equipment['cal_due'] ?? null,
                                'assigned_services' => $equipment['assigned_services'] ?? $equipment['services'] ?? ['visual_inspection'],
                                'notes' => $equipment['notes'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Store personnel assignments (NEW FORMAT)
            if ($request->has('personnel_assignments')) {
                $personnelAssignments = $request->personnel_assignments;
                if (is_array($personnelAssignments)) {
                    foreach ($personnelAssignments as $person) {
                        if (is_array($person) && !empty($person['name'])) {
                            PersonnelAssignment::create([
                                'inspection_id' => $inspection->id,
                                'personnel_id' => $person['id'] ?? uniqid('PER_'),
                                'personnel_name' => $person['name'],
                                'role' => $person['role'] ?? '',
                                'certification_level' => $person['certification'] ?? null,
                                'certification_number' => $person['cert_number'] ?? null,
                                'certification_expiry' => $person['cert_expiry'] ?? null,
                                'assigned_services' => isset($person['services']) ? json_encode($person['services']) : '[]',
                                'notes' => $person['notes'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Store consumable assignments (NEW FORMAT)
            if ($request->has('consumable_assignments')) {
                $consumableAssignments = $request->consumable_assignments;
                if (is_array($consumableAssignments)) {
                    foreach ($consumableAssignments as $consumable) {
                        if (is_array($consumable) && !empty($consumable['name'])) {
                            ConsumableAssignment::create([
                                'inspection_id' => $inspection->id,
                                'consumable_id' => $consumable['id'] ?? uniqid('CON_'),
                                'consumable_name' => $consumable['name'],
                                'consumable_type' => $consumable['type'] ?? 'general',
                                'brand_manufacturer' => $consumable['brand'] ?? null,
                                'product_code' => $consumable['code'] ?? null,
                                'batch_lot_number' => $consumable['batch'] ?? null,
                                'expiry_date' => $consumable['expiry'] ?? null,
                                'quantity_used' => $consumable['quantity'] ?? 0,
                                'unit' => $consumable['unit'] ?? 'each',
                                'unit_cost' => $consumable['cost'] ?? null,
                                'total_cost' => isset($consumable['quantity']) && isset($consumable['cost']) 
                                    ? $consumable['quantity'] * $consumable['cost'] : null,
                                'supplier' => $consumable['supplier'] ?? null,
                                'condition' => $consumable['condition'] ?? 'new',
                                'assigned_services' => isset($consumable['services']) ? json_encode($consumable['services']) : '[]',
                                'notes' => $consumable['notes'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Store items table data from arrays (OLD FORMAT - for backward compatibility)
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

            // Store old format consumables for backward compatibility
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

            // Store old format personnel for backward compatibility
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

            \Log::info('=== BEFORE COMMIT ===');
            \Log::info('Inspection created with ID: ' . $inspection->id);
            \Log::info('Inspection number: ' . $inspection->inspection_number);
            \Log::info('=== COMMITTING TRANSACTION ===');

            DB::commit();

            \Log::info('=== TRANSACTION COMMITTED SUCCESSFULLY ===');

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
                ->with('success', 'Inspection report has been created successfully! Report Number: ' . $inspection->inspection_number);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // 🔍 LOG THE ACTUAL ERROR
            \Log::error('=== INSPECTION STORE EXCEPTION ===');
            \Log::error('Exception Message: ' . $e->getMessage());
            \Log::error('Exception File: ' . $e->getFile());
            \Log::error('Exception Line: ' . $e->getLine());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            \Log::error('Request Data: ' . json_encode($request->all()));
            \Log::error('=== INSPECTION STORE EXCEPTION END ===');
            
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
            ->get(); // Get all records for DataTables to handle
            
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
            'consumableAssignments',
            'images'
        ])->findOrFail($id);
        
        $personnel = Personnel::orderBy('first_name')->get();
        
        // Get images using the service
        $inspection->images_for_edit = $this->imageUploadService->getImagesForEdit($inspection);
        
        return view('inspections.edit', compact('inspection', 'personnel'));
    }

    /**
     * Update the specified inspection (Super Admin only)
     */
    public function update(UpdateInspectionRequest $request, $id)
    {
        $inspection = Inspection::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            // Basic Information
            'client_id' => 'required|exists:clients,id',
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
            
            // Comments & Recommendations
            'inspector_comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'defects_found' => 'nullable|string',
            'overall_result' => 'nullable|in:pass,fail,conditional_pass',
            'next_inspection_date' => 'nullable|date',
            
            'status' => 'required|in:draft,in_progress,completed,cancelled',
            
            // Image uploads
            'inspection_images' => 'nullable|array',
            'inspection_images.*' => 'nullable|string', // JSON strings from frontend
            
            // Services, Personnel, Equipment, Consumables (JSON)
            'selected_services' => 'nullable|json',
            'service_sections_data' => 'nullable|json',
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
                'client_id' => $request->client_id,
                'project_name' => $request->project_name,
                'location' => $request->location,
                'inspection_date' => now()->format('Y-m-d'), // Always use current date
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
                
                // Comments & Recommendations
                'inspector_comments' => $request->inspector_comments,
                'recommendations' => $request->recommendations,
                'defects_found' => $request->defects_found,
                'overall_result' => $request->overall_result,
                'next_inspection_date' => $request->next_inspection_date,
                
                'status' => $request->status,
            ]);

            // Handle images using the new service
            if ($request->has('inspection_images') && is_array($request->inspection_images)) {
                $this->imageUploadService->updateImages($inspection, $request->inspection_images);
            }

            // Delete existing related records and recreate them
            $inspection->services()->delete();
            $inspection->personnelAssignments()->delete();
            $inspection->equipmentAssignments()->delete();
            $inspection->consumableAssignments()->delete();

            // Store updated services
            if ($request->selected_services) {
                $services = json_decode($request->selected_services, true);
                foreach ($services as $service) {
                    $payload = [
                        'inspection_id' => $inspection->id,
                        'service_type' => $service['type'],
                        'service_data' => [
                            'name' => $service['name'],
                            'description' => $service['description'] ?? null,
                            'parameters' => $service['parameters'] ?? null,
                            'criteria' => $service['criteria'] ?? null,
                            'codes' => $service['codes'] ?? null,
                            'duration' => $service['duration'] ?? null,
                            'cost' => $service['cost'] ?? null,
                        ],
                    ];
                    try {
                        if (\Schema::hasTable('services')) {
                            Service::create($payload);
                        } else {
                            InspectionService::create([
                                'inspection_id' => $inspection->id,
                                'service_type' => $payload['service_type'],
                                'service_data' => json_encode($payload['service_data']),
                            ]);
                        }
                    } catch (\Throwable $ex) {
                        InspectionService::create([
                            'inspection_id' => $inspection->id,
                            'service_type' => $payload['service_type'],
                            'service_data' => json_encode($payload['service_data']),
                        ]);
                    }
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
                        'equipment_id' => $item['equipment_id'] ?? $item['id'] ?? 'manual_' . time(),
                        'equipment_name' => $item['equipment_name'] ?? $item['name'] ?? 'Unknown Equipment',
                        'equipment_type' => $item['equipment_type'] ?? $item['type'] ?? 'general',
                        'make_model' => $item['make_model'] ?? $item['brand'] ?? null,
                        'serial_number' => $item['serial_number'] ?? $item['serial'] ?? null,
                        'condition' => $item['condition'] ?? 'good',
                        'calibration_status' => $item['calibration_status'] ?? 'current',
                        'last_calibration_date' => $item['last_calibration_date'] ?? $item['cal_date'] ?? null,
                        'next_calibration_date' => $item['next_calibration_date'] ?? $item['cal_due'] ?? null,
                        'assigned_services' => $item['assigned_services'] ?? $item['services'] ?? ['visual_inspection'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Store updated consumable assignments
            if ($request->consumable_assignments) {
                $consumables = json_decode($request->consumable_assignments, true);
                foreach ($consumables as $item) {
                    ConsumableAssignment::create([
                        'inspection_id' => $inspection->id,
                        'consumable_id' => $item['consumable_id'],
                        'consumable_name' => $item['consumable_name'],
                        'quantity_used' => $item['quantity_used'] ?? $item['quantity'] ?? null,
                        'unit' => $item['unit'] ?? null,
                        'batch_lot_number' => $item['batch_lot_number'] ?? $item['batch'] ?? null,
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

            // Update draft data - handle form_data properly
            $formData = $request->input('form_data', []);
            $draft->form_data = is_array($formData) ? json_encode($formData) : $formData;
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
                    'form_data' => is_string($draft->form_data) ? json_decode($draft->form_data, true) : $draft->form_data,
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
    public function updateDraft(UpdateInspectionRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $inspection = Inspection::findOrFail($id);
            
            // Update only fields that have values (FIXED - removed problematic fields)
            $updateData = [];
            $allFields = [
                'client_id', 'project_name', 'location', 'inspection_date',
                'weather_conditions', 'temperature', 'humidity', 'equipment_type',
                'equipment_description', 'manufacturer', 'model', 'serial_number',
                'asset_tag', 'manufacture_year', 'capacity', 'capacity_unit',
                'certification_body', 'previous_certificate_number', 'last_inspection_date', 
                'next_inspection_due', 'lead_inspector_name', 'lead_inspector_certification', 'general_notes',
                'lifting_examination_inspector', 'load_test_inspector', 'thorough_examination_inspector',
                'mpi_service_inspector', 'visual_inspector'
            ];

            foreach ($allFields as $field) {
                if ($request->has($field) && $request->input($field) !== null) {
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
                    $payload = [
                        'inspection_id' => $inspection->id,
                        'service_type' => $service['type'] ?? '',
                        'service_data' => [
                            'name' => $service['name'] ?? '',
                            'description' => $service['description'] ?? null,
                            'parameters' => $service['parameters'] ?? null,
                            'criteria' => $service['criteria'] ?? null,
                            'codes' => $service['codes'] ?? null,
                            'duration' => $service['duration'] ?? null,
                            'cost' => $service['cost'] ?? null,
                        ],
                    ];
                    try {
                        if (\Schema::hasTable('services')) {
                            Service::create($payload);
                        } else {
                            InspectionService::create([
                                'inspection_id' => $inspection->id,
                                'service_type' => $payload['service_type'],
                                'service_data' => json_encode($payload['service_data']),
                            ]);
                        }
                    } catch (\Throwable $ex) {
                        InspectionService::create([
                            'inspection_id' => $inspection->id,
                            'service_type' => $payload['service_type'],
                            'service_data' => json_encode($payload['service_data']),
                        ]);
                    }
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
                        'equipment_id' => $item['equipment_id'] ?? $item['id'] ?? 'manual_' . time(),
                        'equipment_name' => $item['equipment_name'] ?? $item['name'] ?? 'Unknown Equipment',
                        'equipment_type' => $item['equipment_type'] ?? $item['type'] ?? 'general',
                        'make_model' => $item['make_model'] ?? $item['brand'] ?? null,
                        'serial_number' => $item['serial_number'] ?? $item['serial'] ?? null,
                        'condition' => $item['condition'] ?? 'good',
                        'calibration_status' => $item['calibration_status'] ?? 'current',
                        'last_calibration_date' => $item['last_calibration_date'] ?? $item['cal_date'] ?? null,
                        'next_calibration_date' => $item['next_calibration_date'] ?? $item['cal_due'] ?? null,
                        'assigned_services' => $item['assigned_services'] ?? $item['services'] ?? ['visual_inspection'],
                        'notes' => $item['notes'] ?? null,
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
                        'consumable_id' => $item['consumable_id'],
                        'consumable_name' => $item['consumable_name'] ?? $item['name'] ?? '',
                        'quantity_used' => $item['quantity_used'] ?? $item['quantity'] ?? null,
                        'unit' => $item['unit'] ?? null,
                        'batch_lot_number' => $item['batch_lot_number'] ?? $item['batch'] ?? null,
                        'condition' => $item['condition'] ?? 'new',
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

        return response()->json([
            'success' => true,
            'personnel' => $personnel
        ]);
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

            return response()->json([
                'success' => true,
                'inspectors' => $inspectors
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getInspectors: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load inspectors: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the wizard creation form
     */
    public function createWizard($step = 1, $inspectionId = null)
    {
        $steps = $this->getWizardSteps();
        $totalSteps = count($steps);
        
        // Validate step number
        if ($step < 1 || $step > $totalSteps) {
            return redirect()->route('inspections.wizard.step', ['step' => 1]);
        }
        
        $inspection = null;
        if ($inspectionId) {
            $inspection = Inspection::with(['inspectionEquipment'])->find($inspectionId);
            if (!$inspection) {
                return redirect()->route('inspections.wizard.step', ['step' => 1])
                    ->with('error', 'Inspection not found.');
            }
        }
        
        // Load active personnel for inspector dropdowns in service sections
        $personnel = Personnel::active()->orderBy('first_name')->get();

        // Load equipment types for step 4
        $equipmentTypes = EquipmentType::orderBy('name')->get();

        // Load consumables for step 5 (consumables section)
        $consumables = \App\Models\Consumable::active()->orderBy('type')->orderBy('manufacturer')->get();

        // Try to preselect the logged-in user if they exist in personnel (by email)
        $defaultInspectorId = null;
        try {
            if (auth()->check() && !empty(auth()->user()->email)) {
                $match = Personnel::active()->where('email', auth()->user()->email)->value('id');
                if ($match) {
                    $defaultInspectorId = $match;
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Default inspector detection failed: '.$e->getMessage());
        }

        return view('inspections.create-wizard', [
            'currentStep' => $step,
            'totalSteps' => $totalSteps,
            'steps' => $steps,
            'inspection' => $inspection,
            'personnel' => $personnel,
            'equipmentTypes' => $equipmentTypes,
            'consumables' => $consumables,
            'defaultInspectorId' => $defaultInspectorId,
        ]);
    }

    /**
     * Save wizard step data
     */
    public function saveWizardStep(Request $request)
    {
        // Add debugging to see all request data
        \Log::info('=== WIZARD STEP SAVE - ALL REQUEST DATA ===');
        \Log::info('All request data: ' . json_encode($request->all(), JSON_PRETTY_PRINT));
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request URL: ' . $request->url());
        
        $step = $request->input('step');
        $inspectionId = $request->input('inspection_id');
        $steps = $this->getWizardSteps();
        
        if ($step < 1 || $step > count($steps)) {
            return redirect()->route('inspections.wizard.step', ['step' => 1])
                ->with('error', 'Invalid step.');
        }
        
        DB::beginTransaction();
        try {
            $inspection = null;
            
            if ($inspectionId) {
                $inspection = Inspection::find($inspectionId);
            }
            
            // If this is step 1 and no inspection exists, create a new one
            if ($step == 1 && !$inspection) {
                $inspection = new Inspection();
                $inspection->status = 'draft';
                $inspection->inspection_number = Inspection::generateInspectionNumber();
                // TODO: Re-enable after database columns are added
                // $inspection->created_by = auth()->id();
                $inspection->save();
            }
            
            if (!$inspection) {
                throw new \Exception('Inspection not found or could not be created.');
            }
            
            // Get validation rules for this step
            $rules = $this->getValidationRulesForStep($step);
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->route('inspections.wizard.step', [
                    'step' => $step, 
                    'inspection' => $inspection->id
                ])->withErrors($validator)->withInput();
            }
            
            // Update inspection with step data
            $this->updateInspectionWithStepData($inspection, $request, $step);

            // If this is Services & Examination step, persist selected services now
            if ((int)$step === 2) {
                \Log::info('=== WIZARD STEP 2 - SERVICES SAVE ===');
                \Log::info('Step: ' . $step);
                \Log::info('Inspection ID: ' . $inspection->id);
                
                // Clear existing service rows (direct tables only since we're not using inspection_services)
                try { \App\Models\LiftingExamination::where('inspection_id', $inspection->id)->delete(); } catch (\Throwable $e) { /* ignore */ }
                try { \App\Models\MpiInspection::where('inspection_id', $inspection->id)->delete(); } catch (\Throwable $e) { /* ignore */ }

                // Handle individual service form submissions (e.g., lifting examination, MPI)
                // This will save directly to the dedicated service tables
                $this->handleIndividualServiceFormData($inspection, $request);
                
                \Log::info('=== END WIZARD STEP 2 - SERVICES SAVE ===');
            }
            
            // If this is the last step, change status to QA
            if ($step == count($steps)) {
                $inspection->status = 'qa';
                $inspection->save();
            }
            
            DB::commit();
            
            $nextStep = $step + 1;
            if ($nextStep > count($steps)) {
                return redirect()->route('inspections.show', $inspection->id)
                    ->with('success', 'Inspection completed and submitted for QA review!');
            }
            
            return redirect()->route('inspections.wizard.step', [
                'step' => $nextStep, 
                'inspection' => $inspection->id
            ])->with('success', 'Step completed successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving wizard step: ' . $e->getMessage());
            
            return redirect()->route('inspections.wizard.step', [
                'step' => $step, 
                'inspection' => $inspectionId
            ])->with('error', 'Error saving step: ' . $e->getMessage());
        }
    }

    /**
     * Get wizard steps configuration
     */
    private function getWizardSteps()
    {
        return [
            [
                'title' => 'Client Information',
                'description' => 'Enter client details, project information, and basic inspection data',
                'view' => 'inspections.sections.client-information'
            ],
            [
                'title' => 'Services & Examination',
                'description' => 'Select services to be performed and examination details',
                'view' => 'inspections.sections.add-service'
            ],
            [
                'title' => 'Equipment Details',
                'description' => 'Add equipment information and specifications',
                'view' => 'inspections.sections.equipment'
            ],
            [
                'title' => 'Equipment & Items',
                'description' => 'Manage equipment details and add inspection items',
                'view' => 'inspections.sections.personnel-assets-combined'
            ],
            [
                'title' => 'Consumables',
                'description' => 'Add and manage consumables used in the inspection',
                'view' => 'inspections.sections.consumables'
            ],
            [
                'title' => 'Comments & Recommendations',
                'description' => 'Add comments, recommendations and upload images',
                'view' => 'inspections.sections.comments-recommendations'
            ]
        ];
    }

    /**
     * Get validation rules for specific step
     */
    private function getValidationRulesForStep($step)
    {
        $baseRules = [
            1 => [
                'client_id' => 'required|exists:clients,id',
                'project_name' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'lead_inspector_name' => 'nullable|string|max:255',
            ],
            2 => [
                'services_performed' => 'nullable|array',
            ],
            3 => [
                // Require at least one equipment assignment rather than legacy fields
                'equipment_assignments' => 'required',
            ],
            4 => [
                'personnel_assignments' => 'nullable|array',
            ],
            5 => [
                'consumables' => 'nullable|array',
                'consumables.*' => 'nullable|string', // Each consumable is JSON string
            ],
            6 => [
                'general_notes' => 'nullable|string',
                'inspector_comments' => 'nullable|string',
            ],
        ];
        return $baseRules[$step] ?? [];
    }

    /**
     * Update inspection with step-specific data
     */
    private function updateInspectionWithStepData($inspection, $request, $step)
    {
        switch ($step) {
            case 1:
                $inspection->fill($request->only([
                    'client_id', 'client_code', 'client_contact_person', 'client_contact_email',
                    'client_phone', 'project_name', 'location', 'area_of_examination',
                    'contract', 'work_order', 'purchase_order', 'client_job_reference',
                    'job_ref', 'standards', 'local_procedure_number', 'drawing_number',
                    'weather_conditions', 'temperature', 'humidity',
                    'lead_inspector_name', 'lead_inspector_certification'
                ]));
                // Always set inspection_date to current date
                $inspection->inspection_date = now()->format('Y-m-d');
                break;
                
            case 2:
                $inspection->services_performed = $request->input('services_performed', []);
                break;
                
            case 3:
                // Save equipment assignments to equipment_assignments table
                // Clear previous entries for this inspection
                \App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->delete();

                $assignments = $request->input('equipment_assignments');
                if (is_string($assignments)) {
                    $decoded = json_decode($assignments, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $assignments = $decoded;
                    }
                }

                if (is_array($assignments)) {
                    \Log::info('Processing equipment assignments array', ['count' => count($assignments), 'assignments' => $assignments]);
                    foreach ($assignments as $eq) {
                        if (!is_array($eq)) continue;
                        if (empty($eq['id']) && empty($eq['name'])) continue;
                        
                        $createData = [
                            'inspection_id' => $inspection->id,
                            'equipment_id' => $eq['equipment_id'] ?? $eq['id'] ?? 'manual_' . time(),
                            'equipment_name' => $eq['equipment_name'] ?? $eq['name'] ?? 'Unknown Equipment',
                            'equipment_type' => $eq['equipment_type'] ?? $eq['type'] ?? 'equipment_item', // Mark as equipment item
                            'make_model' => $eq['make_model'] ?? $eq['brand'] ?? null,
                            'serial_number' => $eq['serial_number'] ?? $eq['serial'] ?? null,
                            'condition' => $eq['condition'] ?? 'good',
                            'calibration_status' => $eq['calibration_status'] ?? $eq['cal_status'] ?? 'current',
                            'last_calibration_date' => $eq['last_calibration_date'] ?? $eq['cal_date'] ?? null,
                            'next_calibration_date' => $eq['next_calibration_date'] ?? $eq['cal_due'] ?? null,
                            'assigned_services' => $eq['assigned_services'] ?? ['visual_inspection'],
                            'notes' => $eq['notes'] ?? null,
                        ];
                        
                        \Log::info('Creating equipment item assignment', ['data' => $createData]);
                        \App\Models\EquipmentAssignment::create($createData);
                    }
                }
                break;
                
            case 4:
                // Handle Equipment Types (Assets) and Items data - save to inspection_equipment table
                \Log::info('=== WIZARD STEP 4 - EQUIPMENT & ITEMS SAVE ===');
                \Log::info('Request data for step 4:', $request->all());
                
                // Clear previous equipment records for this inspection in step 4 table
                InspectionEquipment::where('inspection_id', $inspection->id)->delete();
                
                $equipmentIdMap = []; // To track created equipment for linking items
                
                // Handle Equipment Types (Assets)
                if ($request->has('assets')) {
                    $assets = $request->input('assets');
                    \Log::info('Processing assets:', ['count' => count($assets), 'assets' => $assets]);
                    
                    foreach ($assets as $index => $assetJson) {
                        $asset = json_decode($assetJson, true);
                        if ($asset && is_array($asset)) {
                            $equipmentData = [
                                'inspection_id' => $inspection->id,
                                'client_id' => $inspection->client_id,
                                'equipment_type_id' => $asset['equipment_type_id'] ?? null,
                                'category' => 'asset',
                                'equipment_type' => $asset['equipment_type'] ?? null,
                                'serial_number' => $asset['serial_number'] ?? '',
                                'description' => $asset['description'] ?? '',
                                'reason_for_examination' => $asset['reason_for_examination'] ?? '',
                                'model' => $asset['model'] ?? null,
                                'condition' => 'good', // Default condition
                            ];
                            
                            \Log::info('Creating equipment/asset record in inspection_equipment:', $equipmentData);
                            $createdEquipment = InspectionEquipment::create($equipmentData);
                            
                            // Map serial number to created equipment ID for item linking
                            if ($asset['serial_number']) {
                                $equipmentIdMap[$asset['serial_number']] = $createdEquipment->id;
                            }
                        }
                    }
                }
                
                // Handle Items (Inspection Items)
                if ($request->has('items')) {
                    $items = $request->input('items');
                    \Log::info('Processing items:', ['count' => count($items), 'items' => $items]);
                    
                    foreach ($items as $index => $itemJson) {
                        $item = json_decode($itemJson, true);
                        if ($item && is_array($item)) {
                            // Try to find parent equipment based on description matching
                            $parentEquipmentId = null;
                            if (isset($item['description']) && !empty($equipmentIdMap)) {
                                foreach ($equipmentIdMap as $serialNumber => $equipmentId) {
                                    // Simple matching logic - can be enhanced
                                    if (stripos($item['description'], 'gas rack') !== false && stripos($serialNumber, 'gas') !== false) {
                                        $parentEquipmentId = $equipmentId;
                                        break;
                                    }
                                    // Add more matching logic as needed
                                }
                            }
                            
                            $itemData = [
                                'inspection_id' => $inspection->id,
                                'client_id' => $inspection->client_id,
                                'equipment_type_id' => null, // Items don't need equipment_type_id
                                'parent_equipment_id' => $parentEquipmentId, // Link to parent equipment
                                'category' => 'item',
                                'equipment_type' => 'inspection_item',
                                'serial_number' => $item['serial_number'] ?? '',
                                'description' => $item['description'] ?? '',
                                'reason_for_examination' => $item['reason_of_examination'] ?? '',
                                'model' => $item['model'] ?? null,
                                'swl' => $item['swl'] ?? null,
                                'test_load_applied' => $item['test_load_applied'] ?? null,
                                'date_of_manufacture' => $item['date_of_manufacture'] ?? null,
                                'date_of_last_examination' => $item['date_of_last_examination'] ?? null,
                                'date_of_next_examination' => $item['date_of_next_examination'] ?? null,
                                'status' => $item['status'] ?? null,
                                'remarks' => $item['remarks'] ?? null,
                                'condition' => 'good', // Default condition
                            ];
                            
                            \Log::info('Creating inspection item record in inspection_equipment:', $itemData);
                            InspectionEquipment::create($itemData);
                        }
                    }
                }
                
                \Log::info('=== END WIZARD STEP 4 - EQUIPMENT & ITEMS SAVE ===');
                break;
                
            case 5:
                // Handle consumables
                if ($request->has('consumables')) {
                    \App\Models\ConsumableAssignment::where('inspection_id', $inspection->id)->delete();
                    $consumables = $request->input('consumables');
                    foreach ($consumables as $index => $consumableJson) {
                        $consumable = json_decode($consumableJson, true);
                        if ($consumable && is_array($consumable)) {
                            \App\Models\ConsumableAssignment::create([
                                'inspection_id' => $inspection->id,
                                'consumable_id' => $consumable['consumable_id'] ?? null,
                                'consumable_name' => $consumable['description'] ?? null,
                                'batch_lot_number' => $consumable['batch_lot_number'] ?? null,
                                'quantity_used' => $consumable['quantity'] ?? 0,
                                'unit' => $consumable['unit'] ?? null,
                                'condition' => $consumable['condition'] ?? null,
                                'notes' => $consumable['notes'] ?? null,
                            ]);
                        }
                    }
                }
                break;
                
            case 6:
                $inspection->fill($request->only([
                    'general_notes', 'inspector_comments', 'service_notes',
                    'defects_found', 'recommendations', 'limitations'
                ]));
                
                // Handle image uploads
                $this->handleImageUploads($inspection, $request);
                break;
                
            case 7:
                $inspection->fill($request->only([
                    'overall_result', 'report_date'
                ]));
                break;
        }
        
        $inspection->save();
    }

    /**
     * Link items to equipment (AJAX endpoint)
     */
    public function linkItemsToEquipment(Request $request)
    {
        try {
            $inspectionId = $request->input('inspection_id');
            $equipmentSerial = $request->input('equipment_serial');
            $itemSerials = $request->input('item_serials', []);
            
            // Find the equipment
            $equipment = InspectionEquipment::where('inspection_id', $inspectionId)
                ->where('category', 'asset')
                ->where('serial_number', $equipmentSerial)
                ->first();
            
            if (!$equipment) {
                return response()->json(['error' => 'Equipment not found'], 404);
            }
            
            // Update items to link them to this equipment
            $updatedCount = 0;
            foreach ($itemSerials as $itemSerial) {
                $updated = InspectionEquipment::where('inspection_id', $inspectionId)
                    ->where('category', 'item')
                    ->where('serial_number', $itemSerial)
                    ->update(['parent_equipment_id' => $equipment->id]);
                
                if ($updated) {
                    $updatedCount++;
                }
            }
            
            return response()->json([
                'success' => true, 
                'message' => "Successfully linked {$updatedCount} items to equipment {$equipmentSerial}",
                'linked_count' => $updatedCount
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error linking items to equipment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to link items'], 500);
        }
    }

    /**
     * Mark inspection as completed (for QA use)
     */
    public function markAsCompleted($id)
    {
        $inspection = Inspection::findOrFail($id);
        
        // Only QA approved inspections can be marked as completed
        if ($inspection->qa_status !== 'qa_approved') {
            return redirect()->back()->with('error', 'Only QA approved inspections can be marked as completed.');
        }
        
        $inspection->markAsCompleted(auth()->user());
        
        return redirect()->back()->with('success', 'Inspection marked as completed successfully!');
    }

    /**
     * Submit inspection for QA review
     */
    public function submitForQA($id)
    {
        $inspection = Inspection::findOrFail($id);
        
        // Check if inspection is in a state that can be submitted for QA
        if (!in_array($inspection->status, ['draft', 'revision_required'])) {
            return redirect()->back()->with('error', 'Only draft or revision-required inspections can be submitted for QA.');
        }

        $inspection->submitForQA();

        // Log the submission activity
        activity()
            ->performedOn($inspection)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'submitted_for_qa'])
            ->log('Inspection submitted for QA review');

        return redirect()
            ->route('inspections.index')
            ->with('success', "Inspection {$inspection->inspection_number} has been submitted for QA review.");
    }

    /**
     * Create specific service type record based on service type
     */
    private function createSpecificServiceRecord(\App\Models\InspectionService $inspectionService, array $serviceData): void
    {
        \Log::info("Creating specific service record for type: " . $inspectionService->service_type);
        
        switch ($inspectionService->service_type) {
            case 'lifting_examination':
            case 'lifting-examination':
                $this->createLiftingExaminationRecord($inspectionService, $serviceData);
                break;
                
            case 'mpi':
            case 'mpi_service':
                $this->createMpiInspectionRecord($inspectionService, $serviceData);
                break;
                
            default:
                \Log::info("No specific table for service type: " . $inspectionService->service_type);
                break;
        }
    }

    /**
     * Create lifting examination record
     */
    private function createLiftingExaminationRecord(\App\Models\InspectionService $inspectionService, array $serviceData): void
    {
        try {
            \App\Models\LiftingExamination::create([
                'inspection_id' => $inspectionService->inspection_id,
                'inspection_service_id' => $inspectionService->id,
                'inspector_id' => $serviceData['inspector_id'] ?? null,
                'first_examination' => $serviceData['first_examination'] ?? null,
                'equipment_installation_details' => $serviceData['equipment_installation_details'] ?? null,
                'safe_to_operate' => $serviceData['safe_to_operate'] ?? null,
                'equipment_defect' => $serviceData['equipment_defect'] ?? null,
                'defect_description' => $serviceData['defect_description'] ?? null,
                'existing_danger' => $serviceData['existing_danger'] ?? null,
                'potential_danger' => $serviceData['potential_danger'] ?? null,
                'defect_timeline' => $serviceData['defect_timeline'] ?? null,
                'repair_details' => $serviceData['repair_details'] ?? null,
                'test_details' => $serviceData['test_details'] ?? null,
            ]);
            \Log::info("LiftingExamination record created successfully");
        } catch (\Throwable $ex) {
            \Log::error("Failed to create LiftingExamination record: " . $ex->getMessage());
        }
    }

    /**
     * Create MPI inspection record
     */
    private function createMpiInspectionRecord(\App\Models\InspectionService $inspectionService, array $serviceData): void
    {
        try {
            \App\Models\MpiInspection::create([
                'inspection_id' => $inspectionService->inspection_id,
                'inspection_service_id' => $inspectionService->id,
                'inspector_id' => $serviceData['inspector_id'] ?? null,
                'contrast_paint_method' => $serviceData['contrast_paint_method'] ?? null,
                'ink_powder_1_method' => $serviceData['ink_powder_1_method'] ?? null,
                'magnetic_particle_concentration' => $serviceData['magnetic_particle_concentration'] ?? null,
                'current_flow' => $serviceData['current_flow'] ?? null,
                'ink_powder_1_carrier' => $serviceData['ink_powder_1_carrier'] ?? null,
                'contact_spacing' => $serviceData['contact_spacing'] ?? null,
                'magnetic_flow' => $serviceData['magnetic_flow'] ?? null,
                'ink_powder_2_method' => $serviceData['ink_powder_2_method'] ?? null,
                'field_application_time' => $serviceData['field_application_time'] ?? null,
                'ink_powder_2_carrier' => $serviceData['ink_powder_2_carrier'] ?? null,
                'black_light_intensity_begin' => $serviceData['black_light_intensity_begin'] ?? null,
                'black_light_intensity_end' => $serviceData['black_light_intensity_end'] ?? null,
                'test_temperature' => $serviceData['test_temperature'] ?? null,
                'pull_test' => $serviceData['pull_test'] ?? null,
                'post_test_cleaning' => $serviceData['post_test_cleaning'] ?? null,
                'initial_demagnetisation' => $serviceData['initial_demagnetisation'] ?? null,
                'final_demagnetisation' => $serviceData['final_demagnetisation'] ?? null,
                'mpi_results' => $serviceData['mpi_results'] ?? null,
            ]);
            \Log::info("MpiInspection record created successfully");
        } catch (\Throwable $ex) {
            \Log::error("Failed to create MpiInspection record: " . $ex->getMessage());
        }
    }

    /**
     * Handle individual service form data (when forms are filled directly instead of via service selection)
     */
    private function handleIndividualServiceFormData(\App\Models\Inspection $inspection, Request $request): void
    {
        \Log::info('=== HANDLING INDIVIDUAL SERVICE FORM DATA ===');
        
        // Check for lifting examination data
        if ($request->has('lifting_examination_inspector') || 
            $request->has('first_examination') || 
            $request->has('safe_to_operate') || 
            $request->has('equipment_defect')) {
            
            \Log::info('Lifting examination data detected');
            $this->handleLiftingExaminationForm($inspection, $request);
        }
        
        // Check for MPI service data
        if ($request->has('mpi_service_inspector') || 
            $request->has('contrast_paint_method') || 
            $request->has('current_flow')) {
            
            \Log::info('MPI service data detected');
            $this->handleMpiServiceForm($inspection, $request);
        }
        
        \Log::info('=== END INDIVIDUAL SERVICE FORM DATA HANDLING ===');
    }

    /**
     * Handle lifting examination form submission
     */
    private function handleLiftingExaminationForm(\App\Models\Inspection $inspection, Request $request): void
    {
        try {
            // Delete existing lifting examination record if exists
            \App\Models\LiftingExamination::where('inspection_id', $inspection->id)->delete();
            
            // Create new lifting examination record with form data (direct to table)
            $liftingExamination = \App\Models\LiftingExamination::create([
                'inspection_id' => $inspection->id,
                'inspector_id' => $request->input('lifting_examination_inspector'),
                'first_examination' => $request->input('first_examination'),
                'equipment_installation_details' => $request->input('equipment_installation_details'),
                'safe_to_operate' => $request->input('safe_to_operate'),
                'equipment_defect' => $request->input('equipment_defect'),
                'defect_description' => $request->input('defect_description'),
                'existing_danger' => $request->input('existing_danger'),
                'potential_danger' => $request->input('potential_danger'),
                'defect_timeline' => $request->input('defect_timeline'),
                'repair_details' => $request->input('repair_details'),
                'test_details' => $request->input('test_details'),
            ]);
            
            \Log::info('Created LiftingExamination record directly: ' . $liftingExamination->id);
            
        } catch (\Throwable $ex) {
            \Log::error('Failed to create lifting examination record: ' . $ex->getMessage());
        }
    }

    /**
     * Handle MPI service form submission
     */
    private function handleMpiServiceForm(\App\Models\Inspection $inspection, Request $request): void
    {
        try {
            // Delete existing MPI record if exists
            \App\Models\MpiInspection::where('inspection_id', $inspection->id)->delete();
            
            // Create new MPI record with form data (direct to table)
            $mpiInspection = \App\Models\MpiInspection::create([
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
            ]);
            
            \Log::info('Created MpiInspection record directly: ' . $mpiInspection->id);
            
        } catch (\Throwable $ex) {
            \Log::error('Failed to create MPI inspection record: ' . $ex->getMessage());
        }
    }

    /**
     * Delete inspection image
     */
    public function deleteImage($imageId)
    {
        try {
            $image = \App\Models\InspectionImage::findOrFail($imageId);
            
            // Delete file from storage
            if (\Storage::disk('public')->exists($image->file_path)) {
                \Storage::disk('public')->delete($image->file_path);
            }
            
            // Delete record from database
            $image->delete();
            
            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting image'], 500);
        }
    }

    /**
     * Update image caption
     */
    public function updateImageCaption($imageId, Request $request)
    {
        try {
            $image = \App\Models\InspectionImage::findOrFail($imageId);
            $image->caption = $request->input('caption', '');
            $image->save();
            
            return response()->json(['success' => true, 'message' => 'Caption updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Error updating image caption: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating caption'], 500);
        }
    }

    /**
     * Handle image uploads for inspection
     */
    private function handleImageUploads($inspection, Request $request)
    {
        if (!$request->hasFile('inspection_images')) {
            return;
        }

        $uploadedFiles = $request->file('inspection_images');
        if (!is_array($uploadedFiles)) {
            $uploadedFiles = [$uploadedFiles];
        }

        foreach ($uploadedFiles as $index => $file) {
            try {
                // Validate file
                if (!$file->isValid()) {
                    \Log::warning("Invalid file at index {$index}");
                    continue;
                }

                // Generate unique filename
                $extension = $file->getClientOriginalExtension();
                $filename = 'inspection_' . $inspection->id . '_' . uniqid() . '.' . $extension;
                
                // Store file in storage/app/public/inspection-images/
                $path = $file->storeAs('inspection-images', $filename, 'public');

                // Create database record
                \App\Models\InspectionImage::create([
                    'inspection_id' => $inspection->id,
                    'original_name' => $file->getClientOriginalName(),
                    'file_name' => $filename,
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'caption' => $request->input("image_captions.{$index}", ''),
                    'sort_order' => $index,
                ]);

                \Log::info("Successfully uploaded image: {$filename} for inspection {$inspection->id}");
            } catch (\Exception $e) {
                \Log::error("Error uploading image at index {$index}: " . $e->getMessage());
            }
        }
    }
}

