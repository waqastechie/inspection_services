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
                <strong>Client:</strong> {{ $inspection->client_name ?? 'N/A' }}<br>
                <strong>Project:</strong> {{ $inspection->project_name ?? 'N/A' }}
            </td>
            <td>
                <strong>Report Number:</strong> {{ $inspection->inspection_number ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Location:</strong> {{ $inspection->location ?? 'N/A' }}
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
                <strong>Temperature:</strong> {{ $inspection->temperature ? $inspection->temperature . '°C' : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Humidity:</strong> {{ $inspection->humidity ? $inspection->humidity . '%' : 'N/A' }}
            </td>
            <td>
                <strong>Services:</strong> 
                @if($inspection->services && $inspection->services->count() > 0)
                    @foreach($inspection->services as $service)
                        {{ $service->service_type }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    N/A
                @endif
            </td>
        </tr>
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
    
    <!-- Equipment Details -->
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
        $serviceData = is_string($service->service_data) ? json_decode($service->service_data, true) : $service->service_data;
        $serviceData = $serviceData ?: [];
    @endphp
    
    <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px;">
        <div style="background-color: #f0f0f0; padding: 5px; font-weight: bold; margin-bottom: 10px;">
            {{ $service->service_type_name }} - Status: {{ ucfirst($service->status) }}
        </div>
        
        <table class="details-table">
            @if(isset($serviceData['service_name']))
            <tr>
                <td class="label">Service Name:</td>
                <td>{{ $serviceData['service_name'] }}</td>
            </tr>
            @endif
            
            @if(isset($serviceData['service_description']))
            <tr>
                <td class="label">Description:</td>
                <td>{{ $serviceData['service_description'] }}</td>
            </tr>
            @endif
            
            @if(isset($serviceData['test_parameters']))
            <tr>
                <td class="label">Test Parameters:</td>
                <td>{{ $serviceData['test_parameters'] }}</td>
            </tr>
            @endif
            
            @if(isset($serviceData['acceptance_criteria']))
            <tr>
                <td class="label">Acceptance Criteria:</td>
                <td>{{ $serviceData['acceptance_criteria'] }}</td>
            </tr>
            @endif
            
            @if(isset($serviceData['applicable_codes']))
            <tr>
                <td class="label">Applicable Codes:</td>
                <td>{{ $serviceData['applicable_codes'] }}</td>
            </tr>
            @endif
            
            @if(isset($serviceData['estimated_duration']) || isset($serviceData['cost_estimate']))
            <tr>
                <td class="label">Duration & Cost:</td>
                <td>
                    @if(isset($serviceData['estimated_duration']))
                        Duration: {{ $serviceData['estimated_duration'] }}
                    @endif
                    @if(isset($serviceData['cost_estimate']))
                        @if(isset($serviceData['estimated_duration'])) | @endif
                        Cost: ${{ number_format($serviceData['cost_estimate']) }}
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
    <div class="section-header">EQUIPMENT</div>
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Next Certification Date</th>
                <th>Services</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->equipmentAssignments as $assignment)
            <tr>
                <td>{{ $assignment->equipment->type ?? $assignment->equipment_type ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment->description ?? $assignment->equipment_name ?? 'N/A' }}</td>
                <td>{{ $assignment->equipment && $assignment->equipment->next_certification_date ? date('d-M-Y', strtotime($assignment->equipment->next_certification_date)) : 'N/A' }}</td>
                <td>{{ is_array($assignment->assigned_services) ? implode(', ', $assignment->assigned_services) : ($assignment->assigned_services ?? 'N/A') }}</td>
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
                <th>Type</th>
                <th>Manufacturer</th>
                <th>Description</th>
                <th>Batch Number</th>
                <th>Expiry Date</th>
                <th>Services</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->consumableAssignments as $assignment)
            <tr>
                <td>{{ $assignment->consumable->type ?? $assignment->consumable_type ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable->manufacturer ?? $assignment->brand_manufacturer ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable->description ?? $assignment->consumable_name ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable->batch_number ?? $assignment->batch_lot_number ?? 'N/A' }}</td>
                <td>{{ $assignment->consumable && $assignment->consumable->expiry_date ? date('d-M-Y', strtotime($assignment->consumable->expiry_date)) : ($assignment->expiry_date ? date('d-M-Y', strtotime($assignment->expiry_date)) : 'N/A') }}</td>
                <td>{{ is_array($assignment->assigned_services) ? implode(', ', $assignment->assigned_services) : ($assignment->assigned_services ?? 'N/A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Personnel Section -->
    @if($inspection->personnelAssignments && $inspection->personnelAssignments->count() > 0)
    <div class="section-header">PERSONNEL</div>
    <table class="equipment-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Qualification</th>
                <th>Services</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->personnelAssignments as $assignment)
            <tr>
                <td>{{ $assignment->personnel ? ($assignment->personnel->first_name . ' ' . $assignment->personnel->last_name) : $assignment->personnel_name ?? 'N/A' }}</td>
                <td>{{ $assignment->personnel->position ?? $assignment->role_position ?? 'N/A' }}</td>
                <td>{{ $assignment->personnel->qualification ?? $assignment->certification_level ?? 'N/A' }}</td>
                <td>{{ is_array($assignment->assigned_services) ? implode(', ', $assignment->assigned_services) : ($assignment->assigned_services ?? 'N/A') }}</td>
            </tr>
            @endforeach
        </tbody>
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
    <div class="section-header">INSPECTION IMAGES</div>
    
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
            @endphp
            @if($imageData && (isset($imageData['dataUrl']) || isset($imageData['path'])))
                @php
                    $imageName = $imageData['name'] ?? basename($image) ?? 'Inspection Image ' . ($index + 1);
                    $imageCaption = $imageData['caption'] ?? '';
                    $imagePath = isset($imageData['path']) ? public_path($imageData['path']) : null;
                @endphp
                <div class="image-container" style="margin-bottom: 20px; page-break-inside: avoid;">
                    <div class="image-title" style="font-weight: bold; margin-bottom: 5px;">
                        Image {{ $index + 1 }}: {{ $imageName }}
                    </div>
                    @if(!empty($imageCaption))
                        <div style="font-size: 11px; color: #666; margin-bottom: 10px;">
                            {{ $imageCaption }}
                        </div>
                    @endif
                    @if($imagePath && file_exists($imagePath))
                        <div style="text-align: center; margin: 10px 0;">
                            <img src="{{ $imagePath }}" style="max-width: 100%; max-height: 300px; border: 1px solid #ccc;" alt="{{ $imageName }}">
                        </div>
                    @elseif(isset($imageData['dataUrl']))
                        <div style="text-align: center; margin: 10px 0;">
                            <img src="{{ $imageData['dataUrl'] }}" style="max-width: 100%; max-height: 300px; border: 1px solid #ccc;" alt="{{ $imageName }}">
                        </div>
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
