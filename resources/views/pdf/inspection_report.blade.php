<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inspection Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .company-info {
            float: left;
            width: 40%;
        }
        
        .certified-by {
            float: right;
            width: 55%;
            text-align: right;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 5px;
        }
        
        .report-title {
            text-align: center;
            background-color: #e6e6e6;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .section-header {
            background-color: #f0f0f0;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ccc;
            margin-top: 15px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .details-table td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: top;
        }
        
        .details-table .label {
            background-color: #f9f9f9;
            font-weight: bold;
            width: 25%;
        }
        
        .equipment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .equipment-table th {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        
        .equipment-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .mpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .mpi-table th, .mpi-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }
        
        .mpi-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .signatures {
            margin-top: 30px;
        }
        
        .signature-section {
            margin-bottom: 25px;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 300px;
            margin-left: 10px;
        }
        
        .image-container {
            page-break-inside: avoid;
            margin-bottom: 30px;
        }
        
        .image-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .image-caption {
            font-style: italic;
            color: #666;
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .image-meta {
            font-size: 10px;
            color: #999;
            text-align: center;
            margin-top: 5px;
        }
        
        .inspection-image {
            max-width: 100%;
            max-height: 400px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto;
        }
        
        @media print {
            .image-container {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header clearfix">
        <div class="company-info">
            <div class="company-name">GLOBAL ENERGY</div>
            <div class="company-name">VENTURES</div>
        </div>
        <div class="certified-by">
            <strong>Certified By:</strong><br>
            Global Energy Ventures SARL<br>
            Zone Industrielle de Vridi<br>
            Rue du Canal<br>
            Base Bollore Oil & Gas<br>
            Abidjan, Cote d'Ivoire
        </div>
    </div>
    
    <!-- Report Title -->
    <div class="report-title">Inspection Report</div>
    
    <!-- Client and Report Details -->
    <table class="details-table">
        <tr>
            <td class="label">Client Details</td>
            <td class="label">Report Details</td>
        </tr>
        <tr>
            <td>
                <strong>Client:</strong> {{ $inspection->client?->client_name ?? 'N/A' }}<br>
                <strong>Project:</strong> {{ $inspection->project_name ?? 'N/A' }}
            </td>
            <td>
                <strong>Report Number:</strong> {{ $inspection->inspection_number ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Serial Number:</strong> {{ $inspection->location ?? 'N/A' }}
            </td>
            <td>
                <strong>Date of Examination:</strong> {{ $inspection->inspection_date ? $inspection->inspection_date->format('d-M-Y') : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Weather:</strong> {{ $inspection->weather_conditions ?? 'N/A' }}
            </td>
            <td>
                <strong>Report Date:</strong> {{ $inspection->report_date ? $inspection->report_date->format('d-M-Y') : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Temperature:</strong> {{ $inspection->temperature ? $inspection->temperature . '°C' : 'N/A' }}
            </td>
            <td>
                <strong>Humidity:</strong> {{ $inspection->humidity ? $inspection->humidity . '%' : 'N/A' }}
            </td>
        </tr>
        @if($inspection->area_of_examination || $inspection->services_performed)
        <tr>
            @if($inspection->area_of_examination)
            <td>
                <strong>Area of Examination:</strong> {{ $inspection->area_of_examination }}
            </td>
            @else
            <td></td>
            @endif
            @if($inspection->services_performed)
            <td>
                <strong>Services Performed:</strong> {{ $inspection->services_performed }}
            </td>
            @else
            <td></td>
            @endif
        </tr>
        @endif
        @if($inspection->contract || $inspection->work_order)
        <tr>
            @if($inspection->contract)
            <td>
                <strong>Contract:</strong> {{ $inspection->contract }}
            </td>
            @else
            <td></td>
            @endif
            @if($inspection->work_order)
            <td>
                <strong>Work Order:</strong> {{ $inspection->work_order }}
            </td>
            @else
            <td></td>
            @endif
        </tr>
        @endif
        @if($inspection->purchase_order || $inspection->standards)
        <tr>
            @if($inspection->purchase_order)
            <td>
                <strong>Purchase Order:</strong> {{ $inspection->purchase_order }}
            </td>
            @else
            <td></td>
            @endif
            @if($inspection->standards)
            <td>
                <strong>Standards:</strong> {{ $inspection->standards }}
            </td>
            @else
            <td></td>
            @endif
        </tr>
        @endif
        @if($inspection->local_procedure_number || $inspection->drawing_number)
        <tr>
            @if($inspection->local_procedure_number)
            <td>
                <strong>Local Procedure:</strong> {{ $inspection->local_procedure_number }}
            </td>
            @else
            <td></td>
            @endif
            @if($inspection->drawing_number)
            <td>
                <strong>Drawing Number:</strong> {{ $inspection->drawing_number }}
            </td>
            @else
            <td></td>
            @endif
        </tr>
        @endif
        @if($inspection->test_restrictions)
        <tr>
            <td colspan="2">
                <strong>Test Restrictions:</strong> {{ $inspection->test_restrictions }}
            </td>
        </tr>
        @endif
    </table>
    
    <!-- Job Details -->
    <div class="section-header">Job Details</div>
    <table class="details-table">
        <tr>
            <td class="label">Inspector:</td>
            <td>{{ $inspection->lead_inspector_name ?? 'N/A' }}</td>
            <td class="label">Standards:</td>
            <td>{{ $inspection->applicable_standard ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Certification:</td>
            <td>{{ $inspection->lead_inspector_certification ?? 'N/A' }}</td>
            <td class="label">Inspection Class:</td>
            <td>{{ $inspection->inspection_class ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Last Inspection:</td>
            <td>{{ $inspection->last_inspection_date ? $inspection->last_inspection_date->format('d-M-Y') : 'N/A' }}</td>
            <td class="label">Next Due:</td>
            <td>{{ $inspection->next_inspection_due ? $inspection->next_inspection_due->format('d-M-Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Asset Tag:</td>
            <td>{{ $inspection->asset_tag ?? 'N/A' }}</td>
            <td class="label">Certification Body:</td>
            <td>{{ $inspection->certification_body ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Previous Certificate:</td>
            <td>{{ $inspection->previous_certificate_number ?? 'N/A' }}</td>
            <td class="label">Overall Result:</td>
            <td>{{ $inspection->overall_result ?? 'Pending' }}</td>
        </tr>
    </table>
    
    <!-- Service Inspector Assignments -->
    @if($inspection->lifting_examination_inspector || $inspection->load_test_inspector || $inspection->thorough_examination_inspector || $inspection->mpi_service_inspector || $inspection->visual_inspector)
    <div class="section-header">Service Inspector Assignments</div>
    <table class="details-table">
        @if($inspection->lifting_examination_inspector)
            @php $liftingInspector = \App\Models\Personnel::find($inspection->lifting_examination_inspector); @endphp
            <tr>
                <td class="label">Lifting Examination Inspector:</td>
                <td>
                    @if($liftingInspector)
                        {{ $liftingInspector->first_name }} {{ $liftingInspector->last_name }}
                        @if($liftingInspector->position)
                            <br><small>{{ $liftingInspector->position }}</small>
                        @endif
                    @else
                        Inspector ID: {{ $inspection->lifting_examination_inspector }}
                    @endif
                </td>
            </tr>
        @endif
        
        @if($inspection->load_test_inspector)
            @php $loadTestInspector = \App\Models\Personnel::find($inspection->load_test_inspector); @endphp
            <tr>
                <td class="label">Load Test Inspector:</td>
                <td>
                    @if($loadTestInspector)
                        {{ $loadTestInspector->first_name }} {{ $loadTestInspector->last_name }}
                        @if($loadTestInspector->position)
                            <br><small>{{ $loadTestInspector->position }}</small>
                        @endif
                    @else
                        Inspector ID: {{ $inspection->load_test_inspector }}
                    @endif
                </td>
            </tr>
        @endif
        
        @if($inspection->thorough_examination_inspector)
            @php $thoroughInspector = \App\Models\Personnel::find($inspection->thorough_examination_inspector); @endphp
            <tr>
                <td class="label">Thorough Examination Inspector:</td>
                <td>
                    @if($thoroughInspector)
                        {{ $thoroughInspector->first_name }} {{ $thoroughInspector->last_name }}
                        @if($thoroughInspector->position)
                            <br><small>{{ $thoroughInspector->position }}</small>
                        @endif
                    @else
                        Inspector ID: {{ $inspection->thorough_examination_inspector }}
                    @endif
                </td>
            </tr>
        @endif
        
        @if($inspection->mpi_service_inspector)
            @php $mpiInspector = \App\Models\Personnel::find($inspection->mpi_service_inspector); @endphp
            <tr>
                <td class="label">MPI Service Inspector:</td>
                <td>
                    @if($mpiInspector)
                        {{ $mpiInspector->first_name }} {{ $mpiInspector->last_name }}
                        @if($mpiInspector->position)
                            <br><small>{{ $mpiInspector->position }}</small>
                        @endif
                    @else
                        Inspector ID: {{ $inspection->mpi_service_inspector }}
                    @endif
                </td>
            </tr>
        @endif
        
        @if($inspection->visual_inspector)
            @php $visualInspector = \App\Models\Personnel::find($inspection->visual_inspector); @endphp
            <tr>
                <td class="label">Visual Inspector:</td>
                <td>
                    @if($visualInspector)
                        {{ $visualInspector->first_name }} {{ $visualInspector->last_name }}
                        @if($visualInspector->position)
                            <br><small>{{ $visualInspector->position }}</small>
                        @endif
                    @else
                        Inspector ID: {{ $inspection->visual_inspector }}
                    @endif
                </td>
            </tr>
        @endif
    </table>
    @endif
    
    <!-- Equipment Details -->
    <div class="section-header">Equipment Under Test</div>
    <table class="details-table">
        <tr>
            <td class="label">Type:</td>
            <td>{{ $inspection->equipment_type ?? 'N/A' }}</td>
            <td class="label">Serial Number:</td>
            <td>{{ $inspection->serial_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Description:</td>
            <td>{{ $inspection->equipment_description ?? 'N/A' }}</td>
            <td class="label">Model:</td>
            <td>{{ $inspection->model ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Manufacturer:</td>
            <td>{{ $inspection->manufacturer ?? 'N/A' }}</td>
            <td class="label">Capacity:</td>
            <td>{{ $inspection->capacity ? $inspection->capacity . ' ' . $inspection->capacity_unit : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Year:</td>
            <td>{{ $inspection->manufacture_year ?? 'N/A' }}</td>
            <td class="label">Asset Tag:</td>
            <td>{{ $inspection->asset_tag ?? 'N/A' }}</td>
        </tr>
    </table>
    
    <!-- Equipment Table -->
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Serial Number</th>
                <th>Model</th>
                <th>Description</th>
                <th>Capacity</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $inspection->equipment_type ?? 'N/A' }}</td>
                <td>{{ $inspection->serial_number ?? 'N/A' }}</td>
                <td>{{ $inspection->model ?? 'N/A' }}</td>
                <td>{{ $inspection->equipment_description ?? 'N/A' }}</td>
                <td>{{ $inspection->capacity ? $inspection->capacity . ' ' . $inspection->capacity_unit : 'N/A' }}</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Equipment Details Table (from equipment assignments) -->
    @if($inspection->equipmentAssignments && $inspection->equipmentAssignments->count() > 0)
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Description and Identification of Equipment</th>
                <th>SWL</th>
                <th>Test Load Applied</th>
                <th>Reason of Examination</th>
                <th>Date of Manufacture</th>
                <th>Date of Last Examination</th>
                <th>Date of Next Examination</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->equipmentAssignments as $assignment)
            <tr>
                <td>{{ $assignment->equipment->serial_number ?? $assignment->equipment_name ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->description ?? $assignment->equipment_description ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->swl ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->test_load ?? 'N/A' }}</td>
                <td>{{ $assignment->examination_reason ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment && $assignment->equipment->manufacture_date ? date('d-M-Y', strtotime($assignment->equipment->manufacture_date)) : 'N/A' }}</td>
                <td>{{ $assignment->equipment && $assignment->equipment->last_examination_date ? date('d-M-Y', strtotime($assignment->equipment->last_examination_date)) : '-' }}</td>
                <td>{{ $assignment->equipment && $assignment->equipment->next_examination_date ? date('d-M-Y', strtotime($assignment->equipment->next_examination_date)) : 'N/A' }}</td>
                <td>{{ $assignment->status ?? 'ND' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Status Legend -->
    <div style="margin: 15px 0;">
        <strong>Status</strong><br>
        <div style="font-size: 10px;">
            ND = No Defect; SDR = See Defect Report; NF = Not Found; OBS = Observation (see Defect Report)
        </div>
    </div>
    
    <!-- Lifting Questions - Simplified -->
    <div class="section-header">Inspection Status</div>
    <table class="details-table">
        <tr>
            <td class="label" style="width: 70%;">Overall Result</td>
            <td>{{ $inspection->overall_result ?? 'Pending' }}</td>
        </tr>
        @if($inspection->defects_found)
        <tr>
            <td>Defects Found</td>
            <td>{{ $inspection->defects_found }}</td>
        </tr>
        @endif
        @if($inspection->recommendations)
        <tr>
            <td>Recommendations</td>
            <td>{{ $inspection->recommendations }}</td>
        </tr>
        @endif
        @if($inspection->limitations)
        <tr>
            <td>Limitations</td>
            <td>{{ $inspection->limitations }}</td>
        </tr>
        @endif
    </table>
    
    <!-- Services and Results -->
    @if($inspection->services && $inspection->services->count() > 0)
    <div class="section-header">SERVICES PERFORMED</div>
    
    @foreach($inspection->services as $service)
    @php
        // Safely read optional service_data property (may not exist on stdClass)
        $serviceData = property_exists($service, 'service_data') ? $service->service_data : [];
        
        // Handle JSON decoding - could be string or already array
        if (is_string($serviceData)) {
            $decoded = json_decode($serviceData, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $serviceData = $decoded;
            } else {
                // Try double-decode for double-encoded JSON
                $doubleDecoded = json_decode(json_decode($serviceData, true), true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($doubleDecoded)) {
                    $serviceData = $doubleDecoded;
                } else {
                    $serviceData = [];
                }
            }
        } elseif (!is_array($serviceData)) {
            $serviceData = [];
        }
    @endphp
    
    <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px;">
        @php
            // Map service_type to human-readable labels for PDF
            $serviceTypeLabels = [
                'load-test' => 'Load Test',
                'mpi-service' => 'MPI Inspection',
                'other-services' => 'Other Services',
                'lifting-examination' => 'Lifting Examination',
                'visual' => 'Visual Inspection',
                'thorough-examination' => 'Thorough Examination',
            ];
            $serviceLabel = $serviceTypeLabels[$service->service_type ?? ''] ?? ucwords(str_replace(['-', '_'], ' ', $service->service_type ?? 'Service'));
        @endphp
        <div style="background-color: #f0f0f0; padding: 5px; font-weight: bold; margin-bottom: 10px;">
            {{ $serviceLabel }}
        </div>
        
        <table class="details-table">
            @php
                $serviceName = $serviceData['service_name'] ?? $serviceData['name'] ?? null;
                $serviceDescription = $serviceData['service_description'] ?? $serviceData['description'] ?? null;
                $testParameters = $serviceData['test_parameters'] ?? $serviceData['parameters'] ?? null;
                $acceptanceCriteria = $serviceData['acceptance_criteria'] ?? $serviceData['criteria'] ?? null;
                $applicableCodes = $serviceData['applicable_codes'] ?? $serviceData['codes'] ?? null;
                $duration = $serviceData['estimated_duration'] ?? $serviceData['duration'] ?? null;
                $cost = $serviceData['cost_estimate'] ?? $serviceData['cost'] ?? null;
            @endphp
            
            @if($serviceName)
            <tr>
                <td class="label">Service Name:</td>
                <td>{{ $serviceName }}</td>
            </tr>
            @endif
            
            @if($serviceDescription)
            <tr>
                <td class="label">Description:</td>
                <td>{{ $serviceDescription }}</td>
            </tr>
            @endif
            
            @if($testParameters)
            <tr>
                <td class="label">Test Parameters:</td>
                <td>{{ $testParameters }}</td>
            </tr>
            @endif
            
            @if($acceptanceCriteria)
            <tr>
                <td class="label">Acceptance Criteria:</td>
                <td>{{ $acceptanceCriteria }}</td>
            </tr>
            @endif
            
            @if($applicableCodes)
            <tr>
                <td class="label">Applicable Codes:</td>
                <td>{{ $applicableCodes }}</td>
            </tr>
            @endif
            
            @if($duration || $cost)
            <tr>
                <td class="label">Duration & Cost:</td>
                <td>
                    @if($duration)
                        Duration: {{ $duration }}
                    @endif
                    @if($cost)
                        @if($duration) | @endif
                        Cost: ${{ is_numeric($cost) ? number_format($cost) : $cost }}
                    @endif
                </td>
            </tr>
            @endif
            
            @if($service->notes)
            <tr>
                <td class="label">Service Notes:</td>
                <td>{{ $service->notes }}</td>
            </tr>
            @endif
            
            {{-- Show detailed examination questions and answers --}}
            @php
                $displayedFields = ['service_name', 'name', 'service_description', 'description', 
                                  'test_parameters', 'parameters', 'acceptance_criteria', 'criteria',
                                  'applicable_codes', 'codes', 'estimated_duration', 'duration',
                                  'cost_estimate', 'cost'];
                $remainingData = array_diff_key($serviceData, array_flip($displayedFields));
            @endphp
            @if(!empty($remainingData))
                @foreach($remainingData as $key => $value)
                    @if($key === 'section_data' && is_array($value))
                        {{-- Handle section_data with proper question formatting --}}
                        @if(!empty($value))
                        
                        {{-- Service-specific detailed questions --}}
                        @if($service->service_type === 'lifting-examination')
                            {{-- Lifting Examination Questions --}}
                            <tr><td colspan="2" style="font-weight: bold; background-color: #f0f0f0; padding: 8px; text-align: center;">LIFTING EQUIPMENT EXAMINATION</td></tr>
                            
                            {{-- Inspector Assignment --}}
                            @if(isset($value['lifting_examination_inspector']))
                            <tr>
                                <td class="label">Assigned Inspector:</td>
                                <td>
                                    @php $inspector = \App\Models\Personnel::find($value['lifting_examination_inspector']); @endphp
                                    @if($inspector)
                                        {{ $inspector->first_name }} {{ $inspector->last_name }} - {{ $inspector->job_title ?? 'Inspector' }}
                                    @else
                                        Inspector ID: {{ $value['lifting_examination_inspector'] }}
                                    @endif
                                </td>
                            </tr>
                            @endif
                            
                            {{-- Question A --}}
                            <tr>
                                <td class="label" style="vertical-align: top; font-weight: bold;">A. Is this the first thorough examination of lifting equipment after installation or after assembly at a new site or in a new location?</td>
                                <td>
                                    @if(isset($value['first_examination']))
                                        <strong>{{ strtoupper($value['first_examination']) }}</strong>
                                        @if($value['first_examination'] === 'yes' && isset($value['equipment_installation_details']))
                                            <div style="margin-top: 5px; padding: 5px; background-color: #f9f9f9; border-left: 3px solid #007bff;">
                                                <strong>Equipment Installation Details:</strong><br>
                                                {{ $value['equipment_installation_details'] }}
                                            </div>
                                        @endif
                                    @else
                                        Not answered
                                    @endif
                                </td>
                            </tr>
                            
                            {{-- Question B --}}
                            <tr>
                                <td class="label" style="vertical-align: top; font-weight: bold;">B. Is it the case that the equipment would be safe to operate?</td>
                                <td>
                                    @if(isset($value['safe_to_operate']))
                                        <strong style="color: {{ $value['safe_to_operate'] === 'yes' ? '#28a745' : '#dc3545' }};">
                                            {{ strtoupper($value['safe_to_operate']) }}
                                        </strong>
                                    @else
                                        Not answered
                                    @endif
                                </td>
                            </tr>
                            
                            {{-- Question C with sub-questions --}}
                            <tr>
                                <td class="label" style="vertical-align: top; font-weight: bold;">C. Does any part of the equipment have a defect which is or could become a danger to persons?</td>
                                <td>
                                    @if(isset($value['equipment_defect']))
                                        <strong style="color: {{ $value['equipment_defect'] === 'no' ? '#28a745' : '#dc3545' }};">
                                            {{ strtoupper($value['equipment_defect']) }}
                                        </strong>
                                        
                                        {{-- Sub-questions for defects --}}
                                        @if($value['equipment_defect'] === 'yes')
                                            <div style="margin-top: 10px; padding: 10px; background-color: #fff3cd; border: 1px solid #ffeaa7;">
                                                
                                                {{-- Defect Description --}}
                                                @if(isset($value['defect_description']) && !empty($value['defect_description']))
                                                <div style="margin-bottom: 8px;">
                                                    <strong>Identification and description of defect:</strong><br>
                                                    <div style="padding: 5px; background-color: white; border: 1px solid #ddd; margin-top: 3px;">
                                                        {{ $value['defect_description'] }}
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                {{-- Existing Danger --}}
                                                @if(isset($value['existing_danger']) && !empty($value['existing_danger']))
                                                <div style="margin-bottom: 8px;">
                                                    <strong>Is this an existing or imminent danger to persons?</strong><br>
                                                    <div style="padding: 5px; background-color: white; border: 1px solid #ddd; margin-top: 3px;">
                                                        {{ $value['existing_danger'] }}
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                {{-- Potential Danger --}}
                                                @if(isset($value['potential_danger']) && !empty($value['potential_danger']))
                                                <div style="margin-bottom: 8px;">
                                                    <strong>Is this a defect which is not yet, but could become a danger to persons?</strong><br>
                                                    <div style="padding: 5px; background-color: white; border: 1px solid #ddd; margin-top: 3px;">
                                                        {{ $value['potential_danger'] }}
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                {{-- Defect Timeline --}}
                                                @if(isset($value['defect_timeline']) && !empty($value['defect_timeline']))
                                                <div style="margin-bottom: 8px;">
                                                    <strong>If yes, state when:</strong><br>
                                                    <div style="padding: 5px; background-color: #ffe6e6; border: 1px solid #ffcccc; margin-top: 3px; color: #d32f2f;">
                                                        {{ date('d M Y', strtotime($value['defect_timeline'])) }}
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                {{-- Repair Details --}}
                                                @if(isset($value['repair_details']) && !empty($value['repair_details']))
                                                <div style="margin-bottom: 8px;">
                                                    <strong>Particulars of any repair, renewal or alteration required:</strong><br>
                                                    <div style="padding: 5px; background-color: white; border: 1px solid #ddd; margin-top: 3px;">
                                                        {{ $value['repair_details'] }}
                                                    </div>
                                                </div>
                                                @endif
                                                
                                            </div>
                                        @endif
                                    @else
                                        Not answered
                                    @endif
                                </td>
                            </tr>
                            
                            {{-- Question D --}}
                            @if(isset($value['test_details']) && !empty($value['test_details']))
                            <tr>
                                <td class="label" style="vertical-align: top; font-weight: bold;">D. Details of any tests carried out as part of the examination:</td>
                                <td>
                                    <div style="padding: 5px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                        {{ $value['test_details'] }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                            
                        @elseif($service->service_type === 'mpi-service')
                            {{-- MPI Service Questions --}}
                            <tr><td colspan="2" style="font-weight: bold; background-color: #f0f0f0; padding: 8px; text-align: center;">MAGNETIC PARTICLE INSPECTION (MPI)</td></tr>
                            
                            @foreach($value as $sectionKey => $sectionValue)
                                @if(!empty($sectionValue) && $sectionValue !== '' && $sectionValue !== null)
                                    <tr>
                                        <td class="label">{{ ucwords(str_replace('_', ' ', $sectionKey)) }}:</td>
                                        <td>
                                            @if($sectionKey === 'mpi_service_inspector' && is_numeric($sectionValue))
                                                @php $inspector = \App\Models\Personnel::find($sectionValue); @endphp
                                                @if($inspector)
                                                    {{ $inspector->first_name }} {{ $inspector->last_name }}
                                                @else
                                                    Inspector ID: {{ $sectionValue }}
                                                @endif
                                            @else
                                                {{ ucfirst($sectionValue) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            
                        @elseif($service->service_type === 'visual-examination')
                            {{-- Visual Examination Questions --}}
                            <tr><td colspan="2" style="font-weight: bold; background-color: #f0f0f0; padding: 8px; text-align: center;">VISUAL EXAMINATION</td></tr>
                            
                            @foreach($value as $sectionKey => $sectionValue)
                                @if(!empty($sectionValue) && $sectionValue !== '' && $sectionValue !== null)
                                    <tr>
                                        <td class="label">{{ ucwords(str_replace('_', ' ', $sectionKey)) }}:</td>
                                        <td>
                                            @if($sectionKey === 'visual_inspector' && is_numeric($sectionValue))
                                                @php $inspector = \App\Models\Personnel::find($sectionValue); @endphp
                                                @if($inspector)
                                                    {{ $inspector->first_name }} {{ $inspector->last_name }}
                                                @else
                                                    Inspector ID: {{ $sectionValue }}
                                                @endif
                                            @else
                                                {{ ucfirst($sectionValue) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            
                        @elseif($service->service_type === 'thorough-examination')
                            {{-- Thorough Examination Questions --}}
                            <tr><td colspan="2" style="font-weight: bold; background-color: #f0f0f0; padding: 8px; text-align: center;">THOROUGH EXAMINATION</td></tr>
                            
                            @foreach($value as $sectionKey => $sectionValue)
                                @if(!empty($sectionValue) && $sectionValue !== '' && $sectionValue !== null)
                                    <tr>
                                        <td class="label">{{ ucwords(str_replace('_', ' ', $sectionKey)) }}:</td>
                                        <td>
                                            @if($sectionKey === 'thorough_examination_inspector' && is_numeric($sectionValue))
                                                @php $inspector = \App\Models\Personnel::find($sectionValue); @endphp
                                                @if($inspector)
                                                    {{ $inspector->first_name }} {{ $inspector->last_name }}
                                                @else
                                                    Inspector ID: {{ $sectionValue }}
                                                @endif
                                            @else
                                                {{ ucfirst($sectionValue) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            
                        @else
                            {{-- Generic service data display --}}
                            @foreach($value as $sectionKey => $sectionValue)
                                @if(!empty($sectionValue) && $sectionValue !== '' && $sectionValue !== null)
                                    <tr>
                                        <td class="label">{{ ucwords(str_replace('_', ' ', $sectionKey)) }}:</td>
                                        <td>{{ ucfirst($sectionValue) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        
                        @endif
                    @elseif($key === 'form_fields' && is_array($value))
                        {{-- Show form_fields if section_data is not available --}}
                        @if(!isset($remainingData['section_data']) && !empty($value))
                        <tr>
                            <td class="label">Form Data:</td>
                            <td>
                                @foreach($value as $fieldKey => $fieldValue)
                                    @if(!empty($fieldValue) && $fieldValue !== '' && $fieldValue !== null)
                                        <div style="margin-bottom: 3px;">
                                            <strong>{{ ucwords(str_replace('_', ' ', $fieldKey)) }}:</strong>
                                            @if(str_ends_with($fieldKey, '_inspector') && is_numeric($fieldValue))
                                                @php $inspector = \App\Models\Personnel::find($fieldValue); @endphp
                                                @if($inspector)
                                                    {{ $inspector->first_name }} {{ $inspector->last_name }}
                                                @else
                                                    Inspector ID: {{ $fieldValue }}
                                                @endif
                                            @else
                                                {{ ucfirst($fieldValue) }}
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                        @endif
                    @elseif(!is_array($value) && !empty($value))
                        <tr>
                            <td class="label">{{ ucwords(str_replace('_', ' ', $key)) }}:</td>
                            <td>{{ $value }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
            
            {{-- Show results if any --}}
            @if($service->results && $service->results->count() > 0)
            <tr>
                <td class="label">Results:</td>
                <td>
                    @foreach($service->results as $result)
                        <div style="margin-bottom: 5px;">
                            <strong>{{ $result->result_type }}:</strong> 
                            @if($result->result_value)
                                {{ $result->result_value }} {{ $result->unit }}
                            @endif
                            @if($result->notes)
                                - {{ $result->notes }}
                            @endif
                        </div>
                    @endforeach
                </td>
            </tr>
            @endif
        </table>
    </div>
    @endforeach
    @endif
    
    <div class="page-break"></div>
    
    <!-- Equipment Section -->
    @if($inspection->equipmentAssignments && $inspection->equipmentAssignments->count() > 0)
    <div class="section-header">EQUIPMENT ASSIGNMENTS</div>
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Equipment Name</th>
                <th>Type</th>
                <th>Brand/Model</th>
                <th>Serial Number</th>
                <th>Calibration Due</th>
                <th>Condition</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->equipmentAssignments as $assignment)
            <tr>
                <td>{{ $assignment->equipment->description ?? $assignment->equipment_name ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->type ?? $assignment->equipment_type ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->brand ?? $assignment->brand_model ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->serial_number ?? $assignment->serial_number ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment && $assignment->equipment->next_certification_date ? date('d-M-Y', strtotime($assignment->equipment->next_certification_date)) : ($assignment->calibration_due ? $assignment->calibration_due->format('d-M-Y') : 'N/A') }}</td>
                <td>{{ $assignment->condition ?? 'Good' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Consumables Section -->
    @if($inspection->consumableAssignments && $inspection->consumableAssignments->count() > 0)
    <div class="section-header">CONSUMABLES</div>
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Consumable</th>
                <th>Type</th>
                <th>Manufacturer</th>
                <th>Batch Number</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Expiry Date</th>
                <th>Condition</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->consumableAssignments as $assignment)
            <tr>
                <td>{{ $assignment->consumable->description ?? $assignment->consumable_name ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable->type ?? $assignment->consumable_type ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable->manufacturer ?? $assignment->brand_manufacturer ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable->batch_number ?? $assignment->batch_lot_number ?? 'N/A' }}</td>
                <td>{{ $assignment->quantity_used ? $assignment->quantity_used . ' ' . $assignment->unit : 'N/A' }}</td>
                <td>{{ $assignment->total_cost ? '$' . number_format($assignment->total_cost, 2) : 'N/A' }}</td>
                <td>{{ $assignment->consumable && $assignment->consumable->expiry_date ? date('d-M-Y', strtotime($assignment->consumable->expiry_date)) : ($assignment->expiry_date ? date('d-M-Y', strtotime($assignment->expiry_date)) : 'N/A') }}</td>
                <td>{{ $assignment->condition ?? 'Good' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Personnel Section -->
    @if($inspection->personnelAssignments && $inspection->personnelAssignments->count() > 0)
    <div class="section-header">PERSONNEL ASSIGNMENTS</div>
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role/Position</th>
                <th>Certification</th>
                <th>Cert. Number</th>
                <th>Expiry Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->personnelAssignments as $assignment)
            <tr>
                <td>{{ $assignment->personnel ? ($assignment->personnel->first_name . ' ' . $assignment->personnel->last_name) : $assignment->personnel_name ?? 'N/A' }}</td>
                <td>{{ $assignment->personnel->position ?? $assignment->role_position ?? 'N/A' }}</td>
                <td>{{ $assignment->personnel->qualification ?? $assignment->certification_level ?? 'N/A' }}</td>
                <td>{{ $assignment->certification_number ?? 'N/A' }}</td>
                <td>{{ $assignment->certification_expiry ? $assignment->certification_expiry->format('d-M-Y') : 'N/A' }}</td>
                <td>{{ $assignment->status ?? 'Active' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Inspection Results & Comments Section -->
    @if($inspection->inspector_comments || $inspection->defects_found || $inspection->recommendations || $inspection->overall_result || $inspection->next_inspection_date)
    <div class="section-header">INSPECTION RESULTS & COMMENTS</div>
    <table class="details-table">
        @if($inspection->inspector_comments)
        <tr>
            <td class="label" style="width: 25%;">Inspector Comments:</td>
            <td>{{ $inspection->inspector_comments }}</td>
        </tr>
        @endif
        @if($inspection->defects_found)
        <tr>
            <td class="label">Defects Found:</td>
            <td>{{ $inspection->defects_found }}</td>
        </tr>
        @endif
        @if($inspection->recommendations)
        <tr>
            <td class="label">Recommendations:</td>
            <td>{{ $inspection->recommendations }}</td>
        </tr>
        @endif
        @if($inspection->overall_result)
        <tr>
            <td class="label">Overall Result:</td>
            <td style="font-weight: bold; color: {{ $inspection->overall_result === 'pass' ? '#28a745' : ($inspection->overall_result === 'fail' ? '#dc3545' : '#fd7e14') }};">
                {{ strtoupper($inspection->overall_result) }}
            </td>
        </tr>
        @endif
        @if($inspection->next_inspection_date)
        <tr>
            <td class="label">Next Inspection Date:</td>
            <td>{{ Carbon\Carbon::parse($inspection->next_inspection_date)->format('d-M-Y') }}</td>
        </tr>
        @endif
        @if($inspection->limitations)
        <tr>
            <td class="label">Limitations:</td>
            <td>{{ $inspection->limitations }}</td>
        </tr>
        @endif
    </table>
    @endif
    
    <!-- Comments Section -->
    <div class="section-header">COMMENTS</div>
    <p>{{ $inspection->general_notes ?? 'No additional comments provided.' }}</p>
    
    <!-- Inspection Images Section -->
    @php
        $inspection_images = $inspection->inspection_images;
        if (is_string($inspection_images)) {
            $inspection_images = json_decode($inspection_images, true) ?: [];
        }
        $inspection_images = $inspection_images ?: [];
    @endphp
    @if(is_array($inspection_images) && count($inspection_images) > 0)
    <div class="page-break"></div>
    <div class="section-header">INSPECTION FILES</div>
    
    <div style="margin: 15px 0;">
        @foreach($inspection_images as $index => $image)
            @php
                // Handle both complex image objects and simple file paths
                if (is_string($image)) {
                    // Simple file path
                    $imageData = [
                        'name' => basename($image),
                        'caption' => '',
                        'path' => $image
                    ];
                } else {
                    // Complex image object
                    $imageData = is_string($image) ? json_decode($image, true) : $image;
                }
                
                // Get file extension for icon
                $fileName = $imageData['name'] ?? basename($image) ?? 'file';
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Determine file type icon (using text symbols compatible with DomPDF)
                $fileIcon = '[DOC]'; // Default
                switch($fileExtension) {
                    case 'pdf':
                        $fileIcon = '[PDF]';
                        break;
                    case 'doc':
                    case 'docx':
                        $fileIcon = '[DOC]';
                        break;
                    case 'xls':
                    case 'xlsx':
                        $fileIcon = '[XLS]';
                        break;
                    case 'ppt':
                    case 'pptx':
                        $fileIcon = '[PPT]';
                        break;
                    case 'txt':
                        $fileIcon = '[TXT]';
                        break;
                    case 'zip':
                    case 'rar':
                    case '7z':
                        $fileIcon = '[ZIP]';
                        break;
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                    case 'bmp':
                        $fileIcon = '[IMG]';
                        break;
                }
                
                // Create download URL for QR code
                $downloadUrl = url('/inspections/' . $inspection->id . '/file/' . ($index + 1) . '/download');
                
                // Generate QR Code
                $qrCodeData = '';
                try {
                    $qrCode = new \Endroid\QrCode\QrCode($downloadUrl);
                    $writer = new \Endroid\QrCode\Writer\PngWriter();
                    $result = $writer->write($qrCode);
                    $qrCodeData = 'data:image/png;base64,' . base64_encode($result->getString());
                } catch (Exception $e) {
                    $qrCodeData = '';
                }
            @endphp
            @if($imageData && (isset($imageData['dataUrl']) || isset($imageData['path'])))
                <div style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); page-break-inside: avoid;">
                    <!-- File Header -->
                    <div style="display: flex; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #dee2e6; padding-bottom: 8px;">
                        <span style="font-size: 10px; font-weight: bold; color: #ffffff; background-color: #007bff; padding: 4px 6px; border-radius: 4px; margin-right: 10px; font-family: monospace;">{{ $fileIcon }}</span>
                        <div style="flex: 1;">
                            <div style="font-weight: bold; font-size: 14px; color: #495057;">
                                File {{ $index + 1 }}:
                            </div>
                            <div style="font-size: 12px; color: #6c757d;">
                                Document Available
                            </div>
                        </div>
                    </div>
                    
                    <!-- File Details -->
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 11px; color: #495057; margin-bottom: 5px;">
                            {{ $fileName }}
                        </div>
                        @if(isset($imageData['path']) && file_exists(public_path($imageData['path'])))
                            @php
                                $fileSize = filesize(public_path($imageData['path']));
                                $fileSizeFormatted = $fileSize > 1024 * 1024 
                                    ? round($fileSize / (1024 * 1024), 2) . ' MB'
                                    : round($fileSize / 1024, 2) . ' KB';
                            @endphp
                            <div style="font-size: 10px; color: #6c757d;">
                                ({{ $fileSizeFormatted }})
                            </div>
                        @endif
                    </div>
                    
                    <!-- Download Section -->
                    <div style="text-align: center; padding: 10px; background: #ffffff; border: 1px solid #dee2e6; border-radius: 4px;">
                        <div style="font-size: 11px; margin-bottom: 8px;">
                            <span style="background-color: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold;">Download</span>
                        </div>
                        
                        <!-- QR Code -->
                        <div style="border: 2px dashed #007bff; padding: 10px; border-radius: 4px; background: #f8f9ff;">
                            @if($qrCodeData)
                                <img src="{{ $qrCodeData }}" style="width: 80px; height: 80px;" alt="QR Code">
                                <div style="font-size: 9px; color: #6c757d; margin-top: 5px;">
                                    Scan to download
                                </div>
                            @else
                                <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border: 1px solid #dee2e6; margin: 0 auto;">
                                    <span style="font-size: 10px; color: #6c757d;">QR</span>
                                </div>
                                <div style="font-size: 9px; color: #dc3545; margin-top: 5px;">
                                    QR Code unavailable<br>(Generation failed)
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Image Preview (if applicable) -->
                    @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                        @php
                            $imagePath = isset($imageData['path']) ? public_path($imageData['path']) : null;
                        @endphp
                        @if($imagePath && file_exists($imagePath))
                            <div style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 8px;">Preview:</div>
                                <img src="{{ $imagePath }}" style="max-width: 100%; max-height: 200px; border: 1px solid #ccc; border-radius: 4px;" alt="{{ $fileName }}">
                            </div>
                        @elseif(isset($imageData['dataUrl']))
                            <div style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 8px;">Preview:</div>
                                <img src="{{ $imageData['dataUrl'] }}" style="max-width: 100%; max-height: 200px; border: 1px solid #ccc; border-radius: 4px;" alt="{{ $fileName }}">
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    @endif
    
    <!-- Declaration and Signatures -->
    <div class="section-header">DECLARATION AND SIGNATURES</div>
    
    <div class="signatures">
        <div class="signature-section">
            <strong>INSPECTOR:</strong><br><br>
            Name: <span class="signature-line"></span> Signature: <span class="signature-line"></span><br><br>
            Date: <span class="signature-line"></span> Qualification: <span class="signature-line"></span>
        </div>
        
        <div class="signature-section">
            <strong>CLIENT REPRESENTATIVE:</strong><br><br>
            Name: <span class="signature-line"></span> Signature: <span class="signature-line"></span><br><br>
            Date: <span class="signature-line"></span> Position: <span class="signature-line"></span>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="position: fixed; bottom: 20px; left: 20px; font-size: 10px;">
        {{ $inspection->inspection_number ?? 'REPORT' }} - v1.0 - {{ $inspection->inspection_date ? $inspection->inspection_date->format('d-M-Y') : date('d-M-Y') }}
    </div>
</body>
</html>
