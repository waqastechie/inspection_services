<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Report #{{ $inspection->inspection_number }}</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            body { font-size: 12px; }
            .card { border: 1px solid #dee2e6 !important; }
        }
        
        body { 
            font-family: 'Arial', sans-serif; 
            line-height: 1.4;
            color: #333;
        }
        
        .inspection-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .section-title {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0 15px 0;
            font-weight: bold;
        }
        
        .info-row {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-draft { background-color: #f8d7da; color: #721c24; }
        
        .table-sm th, .table-sm td {
            padding: 6px;
            font-size: 11px;
        }
        
        .signature-section {
            margin-top: 40px;
            border-top: 2px solid #007bff;
            padding-top: 20px;
        }
        
        .signature-box {
            border: 1px solid #ccc;
            height: 60px;
            margin-top: 10px;
        }
        
        .footer-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #666;
        }
        
        .qa-section {
            background-color: #f0f8ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .qa-approved { background-color: #d4edda; border-color: #c3e6cb; }
        .qa-rejected { background-color: #f8d7da; border-color: #f5c6cb; }
        .qa-revision { background-color: #fff3cd; border-color: #ffeaa7; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="inspection-header text-center">
        <h1><i class="fas fa-clipboard-check me-2"></i>INSPECTION REPORT</h1>
        <h2>{{ $inspection->inspection_number }}</h2>
        <p class="mb-0">Professional Lifting Equipment Inspection</p>
    </div>

    <!-- No Print Controls -->
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="fas fa-print me-2"></i>Print Report
        </button>
        <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Inspection
        </a>
    </div>

    <!-- Basic Information -->
    <div class="section-title">
        <i class="fas fa-info-circle me-2"></i>BASIC INFORMATION
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Inspection Number:</span>
                <strong>{{ $inspection->inspection_number ?? 'N/A' }}</strong>
            </div>
            <div class="info-row">
                <span class="info-label">Project Name:</span>
                {{ $inspection->project_name ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Location:</span>
                {{ $inspection->location ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Inspection Date:</span>
                {{ $inspection->inspection_date ? $inspection->inspection_date->format('M j, Y') : 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ strtolower(str_replace('_', '-', $inspection->status)) }}">
                    {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Client:</span>
                {{ $inspection->client->client_name ?? $inspection->client_name ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Contract:</span>
                {{ $inspection->contract ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Work Order:</span>
                {{ $inspection->work_order ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Purchase Order:</span>
                {{ $inspection->purchase_order ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Report Date:</span>
                {{ $inspection->report_date ? $inspection->report_date->format('M j, Y') : 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Equipment Information -->
    <div class="section-title">
        <i class="fas fa-cogs me-2"></i>EQUIPMENT UNDER TEST
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Equipment Type:</span>
                {{ $inspection->equipment_type ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Description:</span>
                {{ $inspection->equipment_description ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Manufacturer:</span>
                {{ $inspection->manufacturer ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Model:</span>
                {{ $inspection->model ?? 'N/A' }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Serial Number:</span>
                {{ $inspection->serial_number ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Asset Tag:</span>
                {{ $inspection->asset_tag ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Manufacture Year:</span>
                {{ $inspection->manufacture_year ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Capacity:</span>
                {{ $inspection->capacity ? $inspection->capacity . ' ' . ($inspection->capacity_unit ?? '') : 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Standards & Certification -->
    <div class="section-title">
        <i class="fas fa-certificate me-2"></i>STANDARDS & CERTIFICATION
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Applicable Standard:</span>
                {{ $inspection->applicable_standard ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Inspection Class:</span>
                {{ $inspection->inspection_class ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Previous Certificate:</span>
                {{ $inspection->previous_certificate_number ?? 'N/A' }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Last Inspection:</span>
                {{ $inspection->last_inspection_date ? $inspection->last_inspection_date->format('M j, Y') : 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Next Inspection Due:</span>
                {{ $inspection->next_inspection_due ? $inspection->next_inspection_due->format('M j, Y') : 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Certification Body:</span>
                {{ $inspection->certification_body ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Services Performed -->
    @if($inspection->services && $inspection->services->count() > 0)
    <div class="section-title">
        <i class="fas fa-list-check me-2"></i>SERVICES PERFORMED
    </div>
    
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Service</th>
                <th>Status</th>
                <th>Inspector</th>
                <th>Notes</th>
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

    <!-- Personnel Assignments -->
    @if($inspection->personnelAssignments && $inspection->personnelAssignments->count() > 0)
    <div class="section-title">
        <i class="fas fa-users me-2"></i>ASSIGNED PERSONNEL
    </div>
    
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Role/Position</th>
                <th>Certification</th>
                <th>Certification Expiry</th>
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

    <!-- Test Results -->
    <div class="section-title">
        <i class="fas fa-clipboard-list me-2"></i>TEST RESULTS
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="info-row">
                <span class="info-label">Overall Result:</span>
                <strong class="ms-2">
                    <span class="status-badge status-{{ strtolower(str_replace('_', '-', $inspection->overall_result ?? 'pending')) }}">
                        {{ ucfirst(str_replace('_', ' ', $inspection->overall_result ?? 'Pending')) }}
                    </span>
                </strong>
            </div>
            
            @if($inspection->defects_found)
            <div class="info-row">
                <span class="info-label">Defects Found:</span>
                <div class="mt-2">{{ $inspection->defects_found }}</div>
            </div>
            @endif
            
            @if($inspection->recommendations)
            <div class="info-row">
                <span class="info-label">Recommendations:</span>
                <div class="mt-2">{{ $inspection->recommendations }}</div>
            </div>
            @endif
            
            @if($inspection->limitations)
            <div class="info-row">
                <span class="info-label">Limitations:</span>
                <div class="mt-2">{{ $inspection->limitations }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- QA Status -->
    @if($inspection->qa_status && $inspection->qa_status !== 'not_submitted')
    <div class="section-title">
        <i class="fas fa-shield-check me-2"></i>QUALITY ASSURANCE
    </div>
    
    <div class="qa-section qa-{{ str_replace(['_', 'qa_'], ['-', ''], $inspection->qa_status) }}">
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">QA Status:</span>
                    <strong>{{ $inspection->qa_status_name }}</strong>
                </div>
                @if($inspection->qaReviewer)
                <div class="info-row">
                    <span class="info-label">QA Reviewer:</span>
                    {{ $inspection->qaReviewer->name }}
                </div>
                @endif
            </div>
            <div class="col-md-6">
                @if($inspection->qa_reviewed_at)
                <div class="info-row">
                    <span class="info-label">Review Date:</span>
                    {{ $inspection->qa_reviewed_at->format('M j, Y g:i A') }}
                </div>
                @endif
            </div>
        </div>
        
        @if($inspection->qa_comments)
        <div class="info-row">
            <span class="info-label">QA Comments:</span>
            <div class="mt-2">{{ $inspection->qa_comments }}</div>
        </div>
        @endif
        
        @if($inspection->qa_rejection_reason)
        <div class="info-row">
            <span class="info-label">Rejection Reason:</span>
            <div class="mt-2 text-danger">{{ $inspection->qa_rejection_reason }}</div>
        </div>
        @endif
    </div>
    @endif

    <!-- Inspector Information -->
    <div class="section-title">
        <i class="fas fa-user-check me-2"></i>INSPECTOR INFORMATION
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Lead Inspector:</span>
                {{ $inspection->lead_inspector_name ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Certification:</span>
                {{ $inspection->lead_inspector_certification ?? 'N/A' }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-row">
                <span class="info-label">Weather Conditions:</span>
                {{ $inspection->weather_conditions ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Temperature:</span>
                {{ $inspection->temperature ? $inspection->temperature . '°C' : 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Comments -->
    @if($inspection->inspector_comments || $inspection->general_notes)
    <div class="section-title">
        <i class="fas fa-comment me-2"></i>ADDITIONAL NOTES
    </div>
    
    @if($inspection->inspector_comments)
    <div class="info-row">
        <span class="info-label">Inspector Comments:</span>
        <div class="mt-2">{{ $inspection->inspector_comments }}</div>
    </div>
    @endif
    
    @if($inspection->general_notes)
    <div class="info-row">
        <span class="info-label">General Notes:</span>
        <div class="mt-2">{{ $inspection->general_notes }}</div>
    </div>
    @endif
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="row">
            <div class="col-md-6">
                <div class="text-center">
                    <div class="signature-box"></div>
                    <p class="mt-2 mb-0"><strong>Inspector Signature</strong></p>
                    <small>{{ $inspection->lead_inspector_name ?? 'N/A' }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <div class="signature-box"></div>
                    <p class="mt-2 mb-0"><strong>QA Signature</strong></p>
                    <small>{{ $inspection->qaReviewer->name ?? 'N/A' }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-info text-center">
        <p class="mb-1">This report was generated on {{ now()->format('M j, Y g:i A') }}</p>
        <p class="mb-0">Professional Lifting Equipment Inspection Services</p>
    </div>

    <!-- Print Scripts -->
    <script>
        // Auto-print if requested
        if (window.location.search.includes('print=1')) {
            window.print();
        }
        
        // Print functionality
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>