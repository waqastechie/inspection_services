<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inspection Report #{{ $inspection->inspection_number }}</title>
    
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.4;
            color: #333;
            font-size: 11px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #007bff;
        }
        
        .header h1 {
            margin: 0;
            color: #007bff;
            font-size: 18px;
        }
        
        .header h2 {
            margin: 5px 0;
            color: #0056b3;
            font-size: 16px;
        }
        
        .section-title {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            margin: 15px 0 10px 0;
            font-weight: bold;
            font-size: 12px;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 4px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 30%;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-draft { background-color: #f8d7da; color: #721c24; }
        .status-qa-approved { background-color: #d4edda; color: #155724; }
        .status-qa-rejected { background-color: #f8d7da; color: #721c24; }
        .status-revision-required { background-color: #fff3cd; color: #856404; }
        
        .inspection-image-container {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 8px;
            background: #fafafa;
            height: auto;
        }
        
        .inspection-image {
            max-width: 100%;
            max-height: 250px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto;
        }
        
        .image-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .image-cell {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 5px;
        }
        
        .image-caption {
            font-style: italic;
            color: #555;
            margin-top: 8px;
            text-align: center;
            background: #f5f5f5;
            padding: 5px;
        }
        
        .image-details {
            font-size: 9px;
            color: #888;
            margin-top: 8px;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        
        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .qa-section {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
            border: 2px solid #27ae60;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .qa-approved { 
            background: linear-gradient(135deg, #d5f4e6 0%, #a9dfbf 100%); 
            border-color: #27ae60; 
        }
        .qa-rejected { 
            background: linear-gradient(135deg, #fadbd8 0%, #f1948a 100%); 
            border-color: #e74c3c; 
        }
        .qa-revision { 
            background: linear-gradient(135deg, #fff3cd 0%, #f7dc6f 100%); 
            border-color: #f39c12; 
        }
        
        .image-section {
            margin: 15px 0;
            background: #ffffff;
            border: 1px solid #e1e8ed;
            border-radius: 6px;
            padding: 15px;
        }
        
        .image-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .image-row {
            display: table-row;
        }
        
        .image-cell {
            display: table-cell;
            width: 50%;
            padding: 8px;
            vertical-align: top;
        }
        
        .inspection-image {
            max-width: 100%;
            max-height: 120px;
            border: 2px solid #e1e8ed;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .image-caption {
            font-size: 8px;
            color: #6c757d;
            text-align: center;
            margin-top: 4px;
            font-style: italic;
        }
        
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
        }
        
        .signature-box {
            border: 2px solid #34495e;
            border-radius: 4px;
            height: 50px;
            margin-top: 8px;
            text-align: center;
            line-height: 50px;
            color: #7f8c8d;
            background: #ffffff;
            font-size: 9px;
        }
        
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
            border-top: 2px solid #34495e;
            padding-top: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 6px;
            padding: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .two-column {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 8px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196f3;
            padding: 12px;
            margin: 10px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .critical-info {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-left: 4px solid #ff9800;
            padding: 12px;
            margin: 10px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .success-info {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border-left: 4px solid #4caf50;
            padding: 12px;
            margin: 10px 0;
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>INSPECTION REPORT</h1>
        <h2>{{ $inspection->inspection_number }}</h2>
        <p style="margin: 5px 0 0 0;">Professional Lifting Equipment Inspection</p>
    </div>

    <!-- Step 1: Client Information & Project Details -->
    <div class="section-title">STEP 1: CLIENT INFORMATION & PROJECT DETAILS</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Inspection Number:</td>
            <td><strong>{{ $inspection->inspection_number ?? 'N/A' }}</strong></td>
            <td class="info-label">Client Name:</td>
            <td>{{ $inspection->client->client_name ?? $inspection->client_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Project Name:</td>
            <td>{{ $inspection->project_name ?? 'N/A' }}</td>
            <td class="info-label">Location:</td>
            <td>{{ $inspection->location ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Area of Examination:</td>
            <td>{{ $inspection->area_of_examination ?? 'N/A' }}</td>
            <td class="info-label">Services Performed:</td>
            <td>{{ $inspection->services_performed ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Contract:</td>
            <td>{{ $inspection->contract ?? 'N/A' }}</td>
            <td class="info-label">Work Order:</td>
            <td>{{ $inspection->work_order ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Purchase Order:</td>
            <td>{{ $inspection->purchase_order ?? 'N/A' }}</td>
            <td class="info-label">Client Job Reference:</td>
            <td>{{ $inspection->client_job_reference ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Job Reference:</td>
            <td>{{ $inspection->job_ref ?? 'N/A' }}</td>
            <td class="info-label">Inspection Status:</td>
            <td>
                <span class="status-badge status-{{ strtolower(str_replace('_', '-', $inspection->status)) }}">
                    {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Step 2: Services & Standards -->
    <div class="section-title">STEP 2: SERVICES & STANDARDS</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Standards Applied:</td>
            <td>{{ $inspection->standards ?? 'N/A' }}</td>
            <td class="info-label">Applicable Standard:</td>
            <td>{{ $inspection->applicable_standard ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Local Procedure Number:</td>
            <td>{{ $inspection->local_procedure_number ?? 'N/A' }}</td>
            <td class="info-label">Drawing Number:</td>
            <td>{{ $inspection->drawing_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Test Restrictions:</td>
            <td>{{ $inspection->test_restrictions ?? 'N/A' }}</td>
            <td class="info-label">Surface Condition:</td>
            <td>{{ $inspection->surface_condition ?? 'N/A' }}</td>
        </tr>
    </table>
    
    @if($inspection->services && $inspection->services->count() > 0)
    <div class="section-title">SERVICES PERFORMED</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%;">Service Name</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 25%;">Inspector</th>
                <th style="width: 20%;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->services as $service)
            <tr>
                <td>{{ $service->service_name ?? 'N/A' }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($service->status ?? 'pending') }}">
                        {{ ucfirst($service->status ?? 'Pending') }}
                    </span>
                </td>
                <td>{{ $service->inspector_name ?? 'N/A' }}</td>
                <td>{{ $service->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Step 3: Personnel Assignments -->
    <div class="section-title">STEP 3: PERSONNEL ASSIGNMENTS</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Lead Inspector Name:</td>
            <td>{{ $inspection->lead_inspector_name ?? 'N/A' }}</td>
            <td class="info-label">Lead Inspector Certification:</td>
            <td>{{ $inspection->lead_inspector_certification ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Lifting Examination Inspector:</td>
            <td>{{ $inspection->liftingExaminationInspector->name ?? $inspection->lifting_examination_inspector ?? 'N/A' }}</td>
            <td class="info-label">Load Test Inspector:</td>
            <td>{{ $inspection->loadTestInspector->name ?? $inspection->load_test_inspector ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Thorough Examination Inspector:</td>
            <td>{{ $inspection->thoroughExaminationInspector->name ?? $inspection->thorough_examination_inspector ?? 'N/A' }}</td>
            <td class="info-label">MPI Service Inspector:</td>
            <td>{{ $inspection->mpiServiceInspector->name ?? $inspection->mpi_service_inspector ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Visual Inspector:</td>
            <td>{{ $inspection->visualInspector->name ?? $inspection->visual_inspector ?? 'N/A' }}</td>
            <td class="info-label">Inspector Signature:</td>
            <td>{{ $inspection->inspector_signature ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Report Date:</td>
            <td>{{ $inspection->report_date ? $inspection->report_date->format('M j, Y') : 'N/A' }}</td>
            <td class="info-label">Inspector Comments:</td>
            <td>{{ $inspection->inspector_comments ?? 'N/A' }}</td>
        </tr>
    </table>
    
    @if($inspection->personnelAssignments && $inspection->personnelAssignments->count() > 0)
    <div class="section-title">ASSIGNED PERSONNEL DETAILS</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30%;">Name</th>
                <th style="width: 25%;">Position</th>
                <th style="width: 25%;">Certification</th>
                <th style="width: 20%;">Certification Expiry</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->personnelAssignments as $assignment)
            <tr>
                <td>{{ $assignment->personnel->name ?? 'N/A' }}</td>
                <td>{{ $assignment->personnel->position ?? 'N/A' }}</td>
                <td>{{ $assignment->personnel->certification ?? 'N/A' }}</td>
                <td>{{ $assignment->personnel->certification_expiry ? $assignment->personnel->certification_expiry->format('M j, Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Step 4: Equipment Under Test -->
    <div class="section-title">STEP 4: EQUIPMENT UNDER TEST</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Equipment Type:</td>
            <td>{{ $inspection->equipment_type ?? 'N/A' }}</td>
            <td class="info-label">Serial Number:</td>
            <td>{{ $inspection->serial_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Equipment Description:</td>
            <td>{{ $inspection->equipment_description ?? 'N/A' }}</td>
            <td class="info-label">Asset Tag:</td>
            <td>{{ $inspection->asset_tag ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Manufacturer:</td>
            <td>{{ $inspection->manufacturer ?? 'N/A' }}</td>
            <td class="info-label">Model:</td>
            <td>{{ $inspection->model ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Manufacture Year:</td>
            <td>{{ $inspection->manufacture_year ?? 'N/A' }}</td>
            <td class="info-label">Capacity:</td>
            <td>{{ $inspection->capacity ? $inspection->capacity . ' ' . ($inspection->capacity_unit ?? '') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Inspection Class:</td>
            <td>{{ $inspection->inspection_class ?? 'N/A' }}</td>
            <td class="info-label">Certification Body:</td>
            <td>{{ $inspection->certification_body ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Previous Certificate Number:</td>
            <td>{{ $inspection->previous_certificate_number ?? 'N/A' }}</td>
            <td class="info-label">Last Inspection Date:</td>
            <td>{{ $inspection->last_inspection_date ? $inspection->last_inspection_date->format('M j, Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Next Inspection Due:</td>
            <td>{{ $inspection->next_inspection_due ? $inspection->next_inspection_due->format('M j, Y') : 'N/A' }}</td>
            <td class="info-label">Next Inspection Date:</td>
            <td>{{ $inspection->next_inspection_date ? $inspection->next_inspection_date->format('M j, Y') : 'N/A' }}</td>
        </tr>
    </table>
    
    @if($inspection->equipmentAssignments && $inspection->equipmentAssignments->count() > 0)
    <div class="section-title">EQUIPMENT ASSIGNMENTS</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30%;">Equipment Name</th>
                <th style="width: 20%;">Type</th>
                <th style="width: 15%;">Quantity</th>
                <th style="width: 35%;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->equipmentAssignments as $assignment)
            <tr>
                <td>{{ $assignment->equipment->name ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->type ?? 'N/A' }}</td>
                <td>{{ $assignment->quantity ?? 'N/A' }}</td>
                <td>{{ $assignment->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if($inspection->inspectionEquipment && $inspection->inspectionEquipment->count() > 0)
    <div class="section-title">INSPECTION EQUIPMENT DETAILS</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Serial Number</th>
                <th style="width: 35%;">Description</th>
                <th style="width: 15%;">SWL</th>
                <th style="width: 25%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->inspectionEquipment as $equipment)
            <tr>
                <td>{{ $equipment->serial_number ?? 'N/A' }}</td>
                <td>{{ $equipment->description ?? 'N/A' }}</td>
                <td>{{ $equipment->swl ?? 'N/A' }}</td>
                <td>{{ $equipment->status ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Step 5: Consumables & Materials -->
    <div class="section-title">STEP 5: CONSUMABLES & MATERIALS</div>
    
    @if($inspection->consumableAssignments && $inspection->consumableAssignments->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30%;">Consumable Name</th>
                <th style="width: 20%;">Type</th>
                <th style="width: 15%;">Quantity</th>
                <th style="width: 35%;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->consumableAssignments as $assignment)
            <tr>
                <td>{{ $assignment->consumable->name ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable->type ?? 'N/A' }}</td>
                <td>{{ $assignment->quantity ?? 'N/A' }}</td>
                <td>{{ $assignment->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="no-data">No consumables assigned for this inspection.</p>
    @endif

    <!-- Lifting Examination Details -->
    @if($inspection->liftingExamination)
    <div class="section-title">LIFTING EXAMINATION - QUESTIONS & ANSWERS</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Inspector:</td>
            <td colspan="3">{{ $inspection->liftingExamination->inspector_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">A. Is this the first thorough examination of lifting equipment after installation or after assembly at a new site or in a new location?</td>
            <td><strong>{{ ucfirst($inspection->liftingExamination->first_examination ?? 'N/A') }}</strong></td>
            <td colspan="2">
                @if($inspection->liftingExamination->first_examination === 'yes' && $inspection->liftingExamination->equipment_installation_details)
                    <strong>Installation Details:</strong> {{ $inspection->liftingExamination->equipment_installation_details }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="info-label">B. Is it the case that the equipment would be safe to operate?</td>
            <td colspan="3"><strong>{{ ucfirst($inspection->liftingExamination->safe_to_operate ?? 'N/A') }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">C. Does any part of the equipment have a defect which is or could become a danger to persons?</td>
            <td><strong>{{ ucfirst($inspection->liftingExamination->equipment_defect ?? 'N/A') }}</strong></td>
            <td class="info-label">Overall Status:</td>
            <td><strong>{{ ucfirst($inspection->liftingExamination->overall_status ?? 'N/A') }}</strong></td>
        </tr>
        @if($inspection->liftingExamination->equipment_defect === 'yes')
        <tr>
            <td class="info-label">Defect Description:</td>
            <td colspan="3">{{ $inspection->liftingExamination->defect_description ?? 'N/A' }}</td>
        </tr>
        @if($inspection->liftingExamination->existing_danger)
        <tr>
            <td class="info-label">Existing Danger:</td>
            <td colspan="3">{{ $inspection->liftingExamination->existing_danger }}</td>
        </tr>
        @endif
        @if($inspection->liftingExamination->potential_danger)
        <tr>
            <td class="info-label">Potential Danger:</td>
            <td>{{ $inspection->liftingExamination->potential_danger }}</td>
            <td class="info-label">Timeline:</td>
            <td>{{ $inspection->liftingExamination->defect_timeline ? $inspection->liftingExamination->defect_timeline->format('M j, Y') : 'N/A' }}</td>
        </tr>
        @endif
        @if($inspection->liftingExamination->repair_details)
        <tr>
            <td class="info-label">Required Repairs:</td>
            <td colspan="3">{{ $inspection->liftingExamination->repair_details }}</td>
        </tr>
        @endif
        @endif
        <tr>
            <td class="info-label">D. Details of any tests carried out as part of the examination:</td>
            <td colspan="3">{{ $inspection->liftingExamination->test_details ?? 'N/A' }}</td>
        </tr>
    </table>
    @endif

    <!-- MPI Service Details -->
    @if($inspection->mpiInspection)
    <div class="section-title">MPI SERVICE - DETAILED PARAMETERS & RESULTS</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Inspector:</td>
            <td colspan="3">{{ $inspection->mpiInspection->inspector_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Contrast Paint Method:</td>
            <td>{{ $inspection->mpiInspection->contrast_paint_method_name ?? 'N/A' }}</td>
            <td class="info-label">Ink/Powder 1 Method:</td>
            <td>{{ $inspection->mpiInspection->ink_powder_1_method_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Magnetic Flow:</td>
            <td>{{ $inspection->mpiInspection->magnetic_flow_name ?? 'N/A' }}</td>
            <td class="info-label">Current Flow:</td>
            <td>{{ $inspection->mpiInspection->current_flow_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Ink/Powder 1 Carrier:</td>
            <td>{{ ucfirst($inspection->mpiInspection->ink_powder_1_carrier ?? 'N/A') }}</td>
            <td class="info-label">Ink/Powder 2 Carrier:</td>
            <td>{{ ucfirst($inspection->mpiInspection->ink_powder_2_carrier ?? 'N/A') }}</td>
        </tr>
        @if($inspection->mpiInspection->magnetic_particle_concentration)
        <tr>
            <td class="info-label">Magnetic Particle Concentration:</td>
            <td>{{ $inspection->mpiInspection->magnetic_particle_concentration }}</td>
            <td class="info-label">Contact Spacing:</td>
            <td>{{ $inspection->mpiInspection->contact_spacing ?? 'N/A' }}</td>
        </tr>
        @endif
        <tr>
            <td class="info-label">Test Temperature:</td>
            <td>{{ ucfirst($inspection->mpiInspection->test_temperature ?? 'N/A') }}</td>
            <td class="info-label">Pull Test:</td>
            <td>{{ ucfirst(str_replace('_', ' ', $inspection->mpiInspection->pull_test ?? 'N/A')) }}</td>
        </tr>
        <tr>
            <td class="info-label">Post Test Cleaning:</td>
            <td>{{ ucfirst(str_replace('_', ' ', $inspection->mpiInspection->post_test_cleaning ?? 'N/A')) }}</td>
            <td class="info-label">Initial Demagnetisation:</td>
            <td>{{ ucfirst($inspection->mpiInspection->initial_demagnetisation ?? 'N/A') }}</td>
        </tr>
        <tr>
            <td class="info-label">Final Demagnetisation:</td>
            <td>{{ ucfirst($inspection->mpiInspection->final_demagnetisation ?? 'N/A') }}</td>
            <td class="info-label">Overall Status:</td>
            <td><strong>{{ ucfirst(str_replace('_', ' ', $inspection->mpiInspection->overall_status ?? 'N/A')) }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">MPI Test Results:</td>
            <td colspan="3">{{ $inspection->mpiInspection->mpi_results ?? 'N/A' }}</td>
        </tr>
    </table>
    @endif

    <!-- Step 6: Test Results & Inspection Details -->
    <div class="section-title">STEP 6: TEST RESULTS & INSPECTION DETAILS</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Inspection Date:</td>
            <td>{{ $inspection->inspection_date ? $inspection->inspection_date->format('M j, Y') : 'N/A' }}</td>
            <td class="info-label">Weather Conditions:</td>
            <td>{{ $inspection->weather_conditions ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Temperature:</td>
            <td>{{ $inspection->temperature ? $inspection->temperature . '°C' : 'N/A' }}</td>
            <td class="info-label">Humidity:</td>
            <td>{{ $inspection->humidity ? $inspection->humidity . '%' : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Rig:</td>
            <td>{{ $inspection->rig ?? 'N/A' }}</td>
            <td class="info-label">Report Number:</td>
            <td>{{ $inspection->report_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Revision:</td>
            <td>{{ $inspection->revision ?? 'N/A' }}</td>
            <td class="info-label">Overall Result:</td>
            <td>
                <span class="status-badge status-{{ strtolower(str_replace('_', '-', $inspection->overall_result ?? 'pending')) }}">
                    {{ ucfirst(str_replace('_', ' ', $inspection->overall_result ?? 'Pending')) }}
                </span>
            </td>
        </tr>
        @if($inspection->defects_found)
        <tr>
            <td class="info-label">Defects Found:</td>
            <td colspan="3">{{ $inspection->defects_found }}</td>
        </tr>
        @endif
        @if($inspection->recommendations)
        <tr>
            <td class="info-label">Recommendations:</td>
            <td colspan="3">{{ $inspection->recommendations }}</td>
        </tr>
        @endif
        @if($inspection->limitations)
        <tr>
            <td class="info-label">Limitations:</td>
            <td colspan="3">{{ $inspection->limitations }}</td>
        </tr>
        @endif
        @if($inspection->general_notes)
        <tr>
            <td class="info-label">General Notes:</td>
            <td colspan="3">{{ $inspection->general_notes }}</td>
        </tr>
        @endif
        @if($inspection->service_notes)
        <tr>
            <td class="info-label">Service Notes:</td>
            <td colspan="3">{{ is_array($inspection->service_notes) ? implode(', ', $inspection->service_notes) : $inspection->service_notes }}</td>
        </tr>
        @endif
        @if($inspection->attachments)
        <tr>
            <td class="info-label">Attachments:</td>
            <td colspan="3">{{ is_array($inspection->attachments) ? implode(', ', $inspection->attachments) : $inspection->attachments }}</td>
        </tr>
        @endif
    </table>

    <!-- Step 7: Images & Documentation -->


    <!-- Step 7: Inspection Images -->
    @if($inspection->images && count($inspection->images) > 0)
    <div class="section-title">STEP 7: INSPECTION IMAGES</div>
    
    @php $imageCount = 0; @endphp
    @foreach($inspection->images as $image)
        @if($imageCount % 2 == 0)
            <div style="display: table; width: 100%; margin-bottom: 20px; page-break-inside: avoid;">
        @endif
        
        <div style="display: table-cell; width: 48%; vertical-align: top; padding: 5px; {{ $imageCount % 2 == 0 ? 'padding-right: 10px;' : 'padding-left: 10px;' }}">
            <div class="inspection-image-container" style="margin-bottom: 5px;">
                <div style="margin-bottom: 8px; font-weight: bold; color: #333; font-size: 11px;">
                    Image {{ $loop->iteration }}: {{ $image->caption ?? $image->original_name }}
                </div>
                
                @php
                    $imagePath = storage_path('app/public/' . $image->file_path);
                    $imageExists = file_exists($imagePath);
                @endphp
                
                @if($imageExists)
                    @php
                        try {
                            $imageData = base64_encode(file_get_contents($imagePath));
                            $imageMimeType = $image->mime_type ?? 'image/jpeg';
                            $base64Image = 'data:' . $imageMimeType . ';base64,' . $imageData;
                        } catch (Exception $e) {
                            $base64Image = null;
                        }
                    @endphp
                    
                    @if($base64Image)
                        <div style="text-align: center; margin: 10px 0;">
                            <img src="{{ $base64Image }}" alt="{{ $image->original_name }}" style="max-width: 100%; max-height: 250px; border: 1px solid #ccc; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        </div>
                    @else
                        <div style="border: 2px dashed #ccc; padding: 15px; text-align: center; color: #666; margin: 5px 0; font-size: 10px;">
                            <strong>Image could not be processed</strong><br>
                            File: {{ $image->original_name }}<br>
                            <small>Path: {{ $image->file_path }}</small>
                        </div>
                    @endif
                @else
                    <div style="border: 2px dashed #f44336; padding: 15px; text-align: center; color: #666; margin: 5px 0; background: #ffebee; font-size: 10px;">
                        <strong>⚠ Image file not found</strong><br>
                        File: {{ $image->original_name }}<br>
                        <small>Expected path: {{ $image->file_path }}</small>
                    </div>
                @endif
                
                @if($image->caption && $image->caption !== $image->original_name)
                    <div style="font-style: italic; color: #555; margin-top: 5px; text-align: center; background: #f5f5f5; padding: 3px; font-size: 9px;">
                        Caption: {{ $image->caption }}
                    </div>
                @endif
                
                <div style="font-size: 8px; color: #888; margin-top: 5px; border-top: 1px solid #eee; padding-top: 3px;">
                    <strong>File:</strong> {{ $image->original_name }} 
                    @if($image->formatted_size)
                        ({{ $image->formatted_size }})
                    @endif
                    @if($image->mime_type)
                        | {{ $image->mime_type }}
                    @endif
                </div>
            </div>
        </div>
        
        @php $imageCount++; @endphp
        @if($imageCount % 2 == 0 || $loop->last)
            </div>
        @endif
    @endforeach
    @endif

    <!-- Inspection Summary -->
    <div class="section-title">INSPECTION SUMMARY</div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Inspection Type:</td>
            <td>{{ $inspection->service_type ?? 'N/A' }}</td>
            <td class="info-label">Status:</td>
            <td>
                <span class="status-badge status-{{ strtolower($inspection->status ?? 'draft') }}">
                    {{ ucfirst($inspection->status ?? 'Draft') }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="info-label">Inspector:</td>
            <td>{{ $inspection->inspector->name ?? 'N/A' }}</td>
            <td class="info-label">Completion Date:</td>
            <td>{{ $inspection->completed_at ? $inspection->completed_at->format('M j, Y H:i') : 'In Progress' }}</td>
        </tr>
        @if($inspection->overall_result)
        <tr>
            <td class="info-label">Final Result:</td>
            <td colspan="3">
                <span class="status-badge status-{{ strtolower($inspection->overall_result) }}">
                    {{ ucfirst(str_replace('_', ' ', $inspection->overall_result)) }}
                </span>
            </td>
        </tr>
        @endif
    </table>

    <!-- Signature & Certification Section -->
    <div class="section-title">INSPECTION CERTIFICATION & QUALITY CONTROL</div>
    
    <!-- Inspector Certification -->
    <div style="margin-bottom: 20px;">
        <div style="background: #f8f9fa; padding: 10px; border-left: 4px solid #007bff; margin-bottom: 10px;">
            <strong style="color: #007bff;">INSPECTOR CERTIFICATION</strong>
        </div>
        <table class="info-table">
            <tr>
                <td class="info-label">Inspector Name:</td>
                <td>{{ $inspection->lead_inspector_name ?? $inspection->inspector->name ?? 'N/A' }}</td>
                <td class="info-label">Inspector Certification:</td>
                <td>{{ $inspection->lead_inspector_certification ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Inspection Date:</td>
                <td>{{ $inspection->inspection_date ? $inspection->inspection_date->format('M j, Y') : 'N/A' }}</td>
                <td class="info-label">Inspector Signature:</td>
                <td>_________________________</td>
            </tr>
        </table>
    </div>

    <!-- Quality Control Section -->
    <div style="margin-bottom: 20px;">
        <div style="background: #f0f8f0; padding: 10px; border-left: 4px solid #28a745; margin-bottom: 10px;">
            <strong style="color: #28a745;">QUALITY CONTROL</strong>
        </div>
        <table class="info-table">
            <tr>
                <td class="info-label">QA Status:</td>
                <td>
                    @if($inspection->qa_status)
                        <span class="status-badge status-{{ str_replace(['_', 'qa_'], ['-', ''], $inspection->qa_status) }}">
                            {{ $inspection->qa_status_name ?? ucfirst(str_replace('_', ' ', $inspection->qa_status)) }}
                        </span>
                    @else
                        <span class="status-badge status-pending">Not Submitted</span>
                    @endif
                </td>
                <td class="info-label">QA Reviewer:</td>
                <td>{{ $inspection->qaReviewer->name ?? 'Not Assigned' }}</td>
            </tr>
            @if($inspection->qa_reviewed_at)
            <tr>
                <td class="info-label">QA Review Date:</td>
                <td>{{ $inspection->qa_reviewed_at->format('M j, Y H:i') }}</td>
                <td class="info-label">QA Signature:</td>
                <td>_________________________</td>
            </tr>
            @else
            <tr>
                <td class="info-label">QA Review Date:</td>
                <td>Pending Review</td>
                <td class="info-label">QA Signature:</td>
                <td>_________________________</td>
            </tr>
            @endif
            @if($inspection->qa_comments)
            <tr>
                <td class="info-label">QA Comments:</td>
                <td colspan="3">{{ $inspection->qa_comments }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Certificate Statement -->
    <div style="background: #fff; border: 2px solid #333; padding: 15px; text-align: center;">
        <strong style="font-size: 12px;">CERTIFICATE STATEMENT</strong><br><br>
        This inspection has been carried out in accordance with the applicable standards and regulations. 
        The equipment described herein has been 
        <strong style="color: {{ $inspection->overall_result === 'pass' ? '#28a745' : ($inspection->overall_result === 'fail' ? '#dc3545' : '#ffc107') }};">
            {{ $inspection->overall_result === 'pass' ? 'APPROVED' : ($inspection->overall_result === 'fail' ? 'REJECTED' : 'CONDITIONALLY APPROVED') }}
        </strong>
        for continued service subject to the conditions and limitations stated above.
        
        @if($inspection->qa_status === 'qa_approved')
            <br><br><span style="color: #28a745; font-weight: bold;">✓ Quality Assurance Approved</span>
        @elseif($inspection->qa_status === 'qa_rejected')
            <br><br><span style="color: #dc3545; font-weight: bold;">✗ Quality Assurance Rejected</span>
        @elseif($inspection->qa_status)
            <br><br><span style="color: #6c757d;">QA Status: {{ $inspection->qa_status_name ?? ucfirst(str_replace('_', ' ', $inspection->qa_status)) }}</span>
        @endif
    </div>

    <!-- Professional Footer -->
    <div class="footer">
        <div style="display: table; width: 100%; margin-bottom: 10px;">
            <div style="display: table-cell; width: 33%; text-align: left;">
                <strong>Generated:</strong><br>
                {{ now()->format('M j, Y g:i A') }}
            </div>
            <div style="display: table-cell; width: 34%; text-align: center;">
                <strong>Report Number:</strong><br>
                {{ $inspection->inspection_number }}
            </div>
            <div style="display: table-cell; width: 33%; text-align: right;">
                <strong>Page:</strong><br>
                1 of 1
            </div>
        </div>
        <hr style="border: 1px solid #34495e; margin: 10px 0;">
        <p style="text-align: center; margin: 0; font-weight: bold; color: #2c3e50;">
            PROFESSIONAL LIFTING EQUIPMENT INSPECTION SERVICES
        </p>
        <p style="text-align: center; margin: 5px 0 0 0; font-size: 8px; color: #7f8c8d;">
            Certified to API, LOLER, BS and International Standards | Oil & Gas Industry Compliance
        </p>
    </div>
</body>
</html>