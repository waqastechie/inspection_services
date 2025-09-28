@extends('layouts.master')

@section('title', 'Inspection Report #' . $inspection->inspection_number)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>
                        <i class="fas fa-eye me-3"></i>
                        Inspection Report
                    </h1>
                    <h2 class="h4 text-primary">{{ $inspection->inspection_number }}</h2>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $inspection->status_color }} fs-6 me-3">
                        {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                    </span>
                    <a href="{{ route('inspections.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to List
                    </a>
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit Report
                        </a>
                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDelete({{ $inspection->id }}, '{{ $inspection->inspection_number }}')">
                            <i class="fas fa-trash me-2"></i>
                            Delete
                        </button>
                    @endif
                    <a href="{{ route('inspections.preview-pdf', $inspection->id) }}" class="btn btn-info me-2" target="_blank">
                        <i class="fas fa-eye me-2"></i>
                        Preview PDF
                    </a>
                    <a href="{{ route('inspections.pdf', $inspection->id) }}" class="btn btn-success">
                        <i class="fas fa-file-pdf me-2"></i>
                        Download PDF
                    </a>
                    <button class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        Print
                    </button>
                    
                    @if(auth()->user()->canApproveInspections() && ($inspection->qa_status === 'pending_qa' || $inspection->qa_status === 'under_qa_review'))
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shield-check me-2"></i>QA Review
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('qa.review', $inspection) }}">
                                        <i class="fas fa-clipboard-check me-2"></i>Review Inspection
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <h6 class="dropdown-header">Quick Actions</h6>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item text-success" onclick="quickQAAction('approve')">
                                        <i class="fas fa-check-circle me-2"></i>Quick Approve
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item text-warning" onclick="quickQAAction('revision')">
                                        <i class="fas fa-edit me-2"></i>Request Revision
                                    </button>
                                </li>
                            </ul>
                        </div>
                    @elseif(auth()->user()->canApproveInspections() && $inspection->qa_status)
                        <span class="badge bg-{{ $inspection->qa_status_color }} fs-6">
                            <i class="fas fa-shield-check me-1"></i>{{ $inspection->qa_status_name }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Step Navigation (Quick Jump) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-2">
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <a href="#step1" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-info-circle me-1"></i>Step 1: Client Info
                                </a>
                                <a href="#step2" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-tools me-1"></i>Step 2: Equipment Details
                                </a>
                                <a href="#step3" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-list-check me-1"></i>Step 3: Services
                                </a>
                                <a href="#step4" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-cogs me-1"></i>Step 4: Equipment Assignments
                                </a>
                                <a href="#step5" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-boxes me-1"></i>Step 5: Consumables
                                </a>
                                <a href="#step6" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-camera me-1"></i>Step 6: Images
                                </a>
                                <a href="#step7" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-comments me-1"></i>Step 7: Final Review
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 1: Client Information & Job Details -->
            <div id="step1" class="row mb-5">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Step 1: Client Information & Job Details
                            </h4>
                            <small class="opacity-75">Basic client and project information</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Client Information -->
                                <div class="col-lg-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-building me-2"></i>Client Information
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Client:</strong> {{ $inspection->client?->client_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Project Name:</strong> {{ $inspection->project_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Location:</strong> {{ $inspection->location ?? 'N/A' }}</p>
                                        </div>
                                        @if($inspection->contract)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Contract:</strong> {{ $inspection->contract }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->work_order)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Work Order:</strong> {{ $inspection->work_order }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->purchase_order)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Purchase Order:</strong> {{ $inspection->purchase_order }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->client_job_reference || $inspection->job_ref)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Job Reference:</strong> {{ $inspection->client_job_reference ?? $inspection->job_ref }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Job Details -->
                                <div class="col-lg-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-clipboard-list me-2"></i>Job Details
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Inspection Date:</strong> {{ $inspection->inspection_date ? $inspection->inspection_date->format('d/m/Y') : 'Not set' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Report Date:</strong> {{ $inspection->report_date ? $inspection->report_date->format('d/m/Y') : 'Not set' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Lead Inspector:</strong> {{ $inspection->lead_inspector_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Inspector Certification:</strong> {{ $inspection->lead_inspector_certification ?? 'N/A' }}</p>
                                        </div>
                                        @if($inspection->standards)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Standards:</strong> {{ $inspection->standards }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->local_procedure_number)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Local Procedure:</strong> {{ $inspection->local_procedure_number }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->drawing_number)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Drawing Number:</strong> {{ $inspection->drawing_number }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->area_of_examination)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Area of Examination:</strong> {{ $inspection->area_of_examination }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->test_restrictions)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Test Restrictions:</strong> {{ $inspection->test_restrictions }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Environmental Conditions -->
                            @if($inspection->weather_conditions || $inspection->temperature || $inspection->humidity)
                                <hr class="my-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-cloud-sun me-2"></i>Environmental Conditions
                                        </h6>
                                        <div class="row g-2">
                                            @if($inspection->weather_conditions)
                                                <div class="col-md-4">
                                                    <p class="mb-2"><strong>Weather:</strong> {{ $inspection->weather_conditions }}</p>
                                                </div>
                                            @endif
                                            @if($inspection->temperature)
                                                <div class="col-md-4">
                                                    <p class="mb-2"><strong>Temperature:</strong> {{ $inspection->temperature }}°C</p>
                                                </div>
                                            @endif
                                            @if($inspection->humidity)
                                                <div class="col-md-4">
                                                    <p class="mb-2"><strong>Humidity:</strong> {{ $inspection->humidity }}%</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Equipment Details -->
            <div id="step2" class="row mb-5">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-tools me-2"></i>
                                Step 2: Equipment Details
                            </h4>
                            <small class="opacity-75">Equipment under test specifications</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-cogs me-2"></i>Equipment Specifications
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Equipment Type:</strong> 
                                                @if($inspection->equipmentType)
                                                    {{ $inspection->equipmentType->name }}
                                                @elseif($inspection->equipment_type && !is_numeric($inspection->equipment_type))
                                                    {{ $inspection->equipment_type }}
                                                @elseif($inspection->equipment_type && is_numeric($inspection->equipment_type))
                                                    Equipment ID: {{ $inspection->equipment_type }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Description:</strong> {{ $inspection->equipment_description ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Manufacturer:</strong> {{ $inspection->manufacturer ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Model:</strong> {{ $inspection->model ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Serial Number:</strong> {{ $inspection->serial_number ?? 'N/A' }}</p>
                                        </div>
                                        @if($inspection->asset_tag)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Asset Tag:</strong> {{ $inspection->asset_tag }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Technical Details
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Capacity:</strong> {{ $inspection->capacity ?? 'N/A' }} {{ $inspection->capacity_unit ?? '' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Manufacturing Year:</strong> {{ $inspection->manufacture_year ?? 'N/A' }}</p>
                                        </div>
                                        @if($inspection->applicable_standard)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Applicable Standard:</strong> {{ $inspection->applicable_standard }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->inspection_class)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Inspection Class:</strong> {{ $inspection->inspection_class }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->certification_body)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Certification Body:</strong> {{ $inspection->certification_body }}</p>
                                            </div>
                                        @endif
                                        @if($inspection->surface_condition)
                                            <div class="col-12">
                                                <p class="mb-2"><strong>Surface Condition:</strong> {{ $inspection->surface_condition }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($inspection->general_notes)
                                <hr class="my-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-success mb-3">
                                            <i class="fas fa-sticky-note me-2"></i>Equipment Notes
                                        </h6>
                                        <div class="bg-light p-3 rounded">
                                            {{ $inspection->general_notes }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>



            <!-- Step 3: Services -->
            @php
                $hasLiftingExamination = $inspection->liftingExamination !== null;
                $hasMpiInspection = $inspection->mpiInspection !== null;
                $totalServiceData = $inspection->services->count() + ($hasLiftingExamination ? 1 : 0) + ($hasMpiInspection ? 1 : 0);
            @endphp
            @if($inspection->services->count() > 0 || $hasLiftingExamination || $hasMpiInspection)
                <div id="step3" class="row mb-5">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-list-check me-2"></i>
                                    Step 3: Selected Services ({{ $totalServiceData }})
                                </h4>
                                <small class="opacity-75">Inspection services and detailed examination data</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($inspection->services as $service)
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-header bg-light">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 text-primary fw-bold">
                                                            <i class="fas fa-cog me-2"></i>{{ $service->service_type_name }}
                                                        </h6>
                                                       
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @php
                                                        $serviceData = $service->service_data;
                                                        
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
                                                    
                                                    {{-- Service Name - handle multiple possible field names --}}
                                                    @php
                                                        $serviceName = $serviceData['service_name'] ?? $serviceData['name'] ?? null;
                                                    @endphp
                                                    @if($serviceName)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Service Name:</strong>
                                                            <p class="mb-1">{{ $serviceName }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Service Description - handle multiple possible field names --}}
                                                    @php
                                                        $serviceDescription = $serviceData['service_description'] ?? $serviceData['description'] ?? null;
                                                    @endphp
                                                    @if($serviceDescription)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Description:</strong>
                                                            <p class="mb-1 text-muted">{{ $serviceDescription }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Test Parameters - handle multiple possible field names --}}
                                                    @php
                                                        $testParameters = $serviceData['test_parameters'] ?? $serviceData['parameters'] ?? null;
                                                    @endphp
                                                    @if($testParameters)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Test Parameters:</strong>
                                                            <p class="mb-1">{{ $testParameters }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Acceptance Criteria - handle multiple possible field names --}}
                                                    @php
                                                        $acceptanceCriteria = $serviceData['acceptance_criteria'] ?? $serviceData['criteria'] ?? null;
                                                    @endphp
                                                    @if($acceptanceCriteria)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Acceptance Criteria:</strong>
                                                            <p class="mb-1">{{ $acceptanceCriteria }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Applicable Codes - handle multiple possible field names --}}
                                                    @php
                                                        $applicableCodes = $serviceData['applicable_codes'] ?? $serviceData['codes'] ?? null;
                                                    @endphp
                                                    @if($applicableCodes)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Applicable Codes:</strong>
                                                            <p class="mb-1">{{ $applicableCodes }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="row">
                                                        {{-- Duration - handle multiple possible field names --}}
                                                        @php
                                                            $duration = $serviceData['estimated_duration'] ?? $serviceData['duration'] ?? null;
                                                        @endphp
                                                        @if($duration)
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <strong class="text-primary">Duration:</strong>
                                                                    <p class="mb-1">{{ $duration }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                        {{-- Cost - handle multiple possible field names --}}
                                                        @php
                                                            $cost = $serviceData['cost_estimate'] ?? $serviceData['cost'] ?? null;
                                                        @endphp
                                                        @if($cost)
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <strong class="text-primary">Cost Estimate:</strong>
                                                                    <p class="mb-1">${{ is_numeric($cost) ? number_format($cost) : $cost }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    {{-- Additional service data fields - show any remaining data --}}
                                                    @php
                                                        $displayedFields = ['service_name', 'name', 'service_description', 'description', 
                                                                          'test_parameters', 'parameters', 'acceptance_criteria', 'criteria',
                                                                          'applicable_codes', 'codes', 'estimated_duration', 'duration',
                                                                          'cost_estimate', 'cost', 'section_data'];
                                                        $remainingData = array_diff_key($serviceData, array_flip($displayedFields));
                                                    @endphp
                                                    @if(!empty($remainingData))
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Additional Information:</strong>
                                                            <div class="bg-light p-2 rounded mt-2">
                                                                @foreach($remainingData as $key => $value)
                                                                    @if(!is_array($value))
                                                                        <div class="small">
                                                                            <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                                                            {{ $value }}
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Show section data if available --}}
                                                    @if(isset($serviceData['section_data']) && is_array($serviceData['section_data']))
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Section Details:</strong>
                                                            <div class="bg-light p-2 rounded mt-2">
                                                                @foreach($serviceData['section_data'] as $sectionKey => $sectionValue)
                                                                    @if(!is_array($sectionValue))
                                                                        <div class="small">
                                                                            <strong>{{ ucwords(str_replace('_', ' ', $sectionKey)) }}:</strong>
                                                                            {{ $sectionValue }}
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($service->notes)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Service Notes:</strong>
                                                            <div class="bg-light p-2 rounded">
                                                                <p class="mb-0">{{ $service->notes }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Show assigned inspector for this service if any --}}
                                                    @php
                                                        $serviceInspector = $inspection->personnelAssignments
                                                            ->where('assignment_type', 'service_specific')
                                                            ->where('assignment_details', 'like', '%"service_id":' . $service->id . '%')
                                                            ->first();
                                                    @endphp
                                                    
                                                    @if($serviceInspector)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Assigned Inspector:</strong>
                                                            <div class="d-flex align-items-center mt-1">
                                                                <div class="bg-info bg-opacity-10 rounded-pill px-3 py-1">
                                                                    <i class="fas fa-user-check text-info me-2"></i>
                                                                    <span class="fw-semibold">{{ $serviceInspector->personnel->name }}</span>
                                                                    <small class="text-muted ms-2">({{ $serviceInspector->role }})</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Show service results if any --}}
                                                    @if($service->results && $service->results->count() > 0)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Results:</strong>
                                                            <div class="mt-2">
                                                                @foreach($service->results as $result)
                                                                    <div class="border-start border-3 border-success ps-3 mb-2">
                                                                        <div class="fw-semibold">{{ $result->result_type }}</div>
                                                                        @if($result->result_value)
                                                                            <div class="text-muted small">Value: {{ $result->result_value }} {{ $result->unit }}</div>
                                                                        @endif
                                                                        @if($result->notes)
                                                                            <div class="text-muted small">{{ $result->notes }}</div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="card-footer bg-light">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Created: {{ $service->created_at->format('M d, Y H:i') }}
                                                        </small>
                                                        @if($service->updated_at != $service->created_at)
                                                            <small class="text-muted">
                                                                <i class="fas fa-edit me-1"></i>
                                                                Updated: {{ $service->updated_at->format('M d, Y H:i') }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Service-specific examination data -->
                                    @if($hasLiftingExamination || $hasMpiInspection)
                                        <div class="col-12 mt-4">
                                            <hr>
                                            <h6 class="text-info mb-3">
                                                <i class="fas fa-microscope me-2"></i>Detailed Examination Data
                                            </h6>
                                        </div>

                                        @if($hasLiftingExamination)
                                            <div class="col-lg-6 mb-4">
                                                <div class="card h-100 shadow-sm border-success">
                                                    <div class="card-header bg-success text-white">
                                                        <h6 class="mb-0 fw-bold">
                                                            <i class="fas fa-clipboard-check me-2"></i>Lifting Equipment Examination
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <strong class="text-success">Inspector:</strong>
                                                                <p class="mb-2">{{ $inspection->liftingExamination->inspector_name }}</p>
                                                            </div>
                                                            <div class="col-12">
                                                                <strong class="text-success">First Examination:</strong>
                                                                <p class="mb-2">
                                                                    <span class="badge bg-{{ $inspection->liftingExamination->isFirstExamination() ? 'primary' : 'secondary' }}">
                                                                        {{ $inspection->liftingExamination->first_examination === 'yes' ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            @if($inspection->liftingExamination->equipment_installation_details)
                                                                <div class="col-12">
                                                                    <strong class="text-success">Installation Details:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->liftingExamination->equipment_installation_details }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12">
                                                                <strong class="text-success">Safe to Operate:</strong>
                                                                <p class="mb-2">
                                                                    <span class="badge bg-{{ $inspection->liftingExamination->isSafeToOperate() ? 'success' : 'danger' }}">
                                                                        {{ $inspection->liftingExamination->safe_to_operate === 'yes' ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            <div class="col-12">
                                                                <strong class="text-success">Defects Found:</strong>
                                                                <p class="mb-2">
                                                                    <span class="badge bg-{{ $inspection->liftingExamination->hasDefects() ? 'warning' : 'success' }}">
                                                                        {{ $inspection->liftingExamination->equipment_defect === 'yes' ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            @if($inspection->liftingExamination->defect_description)
                                                                <div class="col-12">
                                                                    <strong class="text-success">Defect Description:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->liftingExamination->defect_description }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($inspection->liftingExamination->test_details)
                                                                <div class="col-12">
                                                                    <strong class="text-success">Test Details:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->liftingExamination->test_details }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12 mt-3">
                                                                <strong class="text-success">Overall Status:</strong>
                                                                <span class="badge bg-{{ $inspection->liftingExamination->overall_status === 'satisfactory' ? 'success' : 'danger' }} ms-2">
                                                                    {{ ucfirst($inspection->liftingExamination->overall_status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-light">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Last updated: {{ $inspection->liftingExamination->updated_at->format('M d, Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($hasMpiInspection)
                                            <div class="col-lg-6 mb-4">
                                                <div class="card h-100 shadow-sm border-warning">
                                                    <div class="card-header bg-warning text-dark">
                                                        <h6 class="mb-0 fw-bold">
                                                            <i class="fas fa-search me-2"></i>Magnetic Particle Inspection (MPI)
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <strong class="text-warning">Inspector:</strong>
                                                                <p class="mb-2">{{ $inspection->mpiInspection->inspector_name }}</p>
                                                            </div>
                                                            @if($inspection->mpiInspection->contrast_paint_method)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Contrast Paint Method:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->contrast_paint_method_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->magnetic_flow)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Magnetic Flow:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->magnetic_flow_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->current_flow)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Current Flow:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->current_flow_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->ink_powder_1_method)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Test Method:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->ink_powder_1_method_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->test_temperature)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Test Temperature:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->test_temperature }}°C</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->black_light_intensity_begin || $inspection->mpiInspection->black_light_intensity_end)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Black Light Intensity:</strong>
                                                                    <p class="mb-2">
                                                                        Begin: {{ $inspection->mpiInspection->black_light_intensity_begin ?? 'N/A' }} μW/cm²
                                                                        | End: {{ $inspection->mpiInspection->black_light_intensity_end ?? 'N/A' }} μW/cm²
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->mpi_results)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">MPI Results:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->mpiInspection->mpi_results }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12 mt-3">
                                                                <strong class="text-warning">Overall Status:</strong>
                                                                <span class="badge bg-{{ $inspection->mpiInspection->overall_status === 'completed' ? 'success' : ($inspection->mpiInspection->overall_status === 'defects_found' ? 'danger' : 'secondary') }} ms-2">
                                                                    {{ ucfirst(str_replace('_', ' ', $inspection->mpiInspection->overall_status)) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-light">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Last updated: {{ $inspection->mpiInspection->updated_at->format('M d, Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Check for service-specific data in dedicated tables -->
                @php
                    $hasLiftingExamination = $inspection->liftingExamination !== null;
                    $hasMpiInspection = $inspection->mpiInspection !== null;
                    $totalServiceData = ($hasLiftingExamination ? 1 : 0) + ($hasMpiInspection ? 1 : 0);
                @endphp

                @if($totalServiceData > 0)
                    <!-- Step 3 with service-specific data -->
                    <div id="step3" class="row mb-5">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-list-check me-2"></i>
                                        Step 3: Selected Services ({{ $totalServiceData }})
                                    </h4>
                                    <small class="opacity-75">Inspection services and detailed examination data</small>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($hasLiftingExamination)
                                            <div class="col-lg-6 mb-4">
                                                <div class="card h-100 shadow-sm border-success">
                                                    <div class="card-header bg-success text-white">
                                                        <h6 class="mb-0 fw-bold">
                                                            <i class="fas fa-clipboard-check me-2"></i>Lifting Equipment Examination
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <strong class="text-success">Inspector:</strong>
                                                                <p class="mb-2">{{ $inspection->liftingExamination->inspector_name }}</p>
                                                            </div>
                                                            <div class="col-12">
                                                                <strong class="text-success">First Examination:</strong>
                                                                <p class="mb-2">
                                                                    <span class="badge bg-{{ $inspection->liftingExamination->isFirstExamination() ? 'primary' : 'secondary' }}">
                                                                        {{ $inspection->liftingExamination->first_examination === 'yes' ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            @if($inspection->liftingExamination->equipment_installation_details)
                                                                <div class="col-12">
                                                                    <strong class="text-success">Installation Details:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->liftingExamination->equipment_installation_details }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12">
                                                                <strong class="text-success">Safe to Operate:</strong>
                                                                <p class="mb-2">
                                                                    <span class="badge bg-{{ $inspection->liftingExamination->isSafeToOperate() ? 'success' : 'danger' }}">
                                                                        {{ $inspection->liftingExamination->safe_to_operate === 'yes' ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            <div class="col-12">
                                                                <strong class="text-success">Defects Found:</strong>
                                                                <p class="mb-2">
                                                                    <span class="badge bg-{{ $inspection->liftingExamination->hasDefects() ? 'warning' : 'success' }}">
                                                                        {{ $inspection->liftingExamination->equipment_defect === 'yes' ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            @if($inspection->liftingExamination->defect_description)
                                                                <div class="col-12">
                                                                    <strong class="text-success">Defect Description:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->liftingExamination->defect_description }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($inspection->liftingExamination->test_details)
                                                                <div class="col-12">
                                                                    <strong class="text-success">Test Details:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->liftingExamination->test_details }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12 mt-3">
                                                                <strong class="text-success">Overall Status:</strong>
                                                                <span class="badge bg-{{ $inspection->liftingExamination->overall_status === 'satisfactory' ? 'success' : 'danger' }} ms-2">
                                                                    {{ ucfirst($inspection->liftingExamination->overall_status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-light">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Last updated: {{ $inspection->liftingExamination->updated_at->format('M d, Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($hasMpiInspection)
                                            <div class="col-lg-6 mb-4">
                                                <div class="card h-100 shadow-sm border-warning">
                                                    <div class="card-header bg-warning text-dark">
                                                        <h6 class="mb-0 fw-bold">
                                                            <i class="fas fa-search me-2"></i>Magnetic Particle Inspection (MPI)
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <strong class="text-warning">Inspector:</strong>
                                                                <p class="mb-2">{{ $inspection->mpiInspection->inspector_name }}</p>
                                                            </div>
                                                            @if($inspection->mpiInspection->contrast_paint_method)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Contrast Paint Method:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->contrast_paint_method_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->magnetic_flow)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Magnetic Flow:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->magnetic_flow_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->current_flow)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Current Flow:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->current_flow_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->ink_powder_1_method)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Test Method:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->ink_powder_1_method_name }}</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->test_temperature)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Test Temperature:</strong>
                                                                    <p class="mb-2">{{ $inspection->mpiInspection->test_temperature }}°C</p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->black_light_intensity_begin || $inspection->mpiInspection->black_light_intensity_end)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">Black Light Intensity:</strong>
                                                                    <p class="mb-2">
                                                                        Begin: {{ $inspection->mpiInspection->black_light_intensity_begin ?? 'N/A' }} μW/cm²
                                                                        | End: {{ $inspection->mpiInspection->black_light_intensity_end ?? 'N/A' }} μW/cm²
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if($inspection->mpiInspection->mpi_results)
                                                                <div class="col-12">
                                                                    <strong class="text-warning">MPI Results:</strong>
                                                                    <div class="bg-light p-2 rounded mt-1">
                                                                        <small>{{ $inspection->mpiInspection->mpi_results }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12 mt-3">
                                                                <strong class="text-warning">Overall Status:</strong>
                                                                <span class="badge bg-{{ $inspection->mpiInspection->overall_status === 'completed' ? 'success' : ($inspection->mpiInspection->overall_status === 'defects_found' ? 'danger' : 'secondary') }} ms-2">
                                                                    {{ ucfirst(str_replace('_', ' ', $inspection->mpiInspection->overall_status)) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-light">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Last updated: {{ $inspection->mpiInspection->updated_at->format('M d, Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Step 3 placeholder -->
                    <div id="step3" class="row mb-5">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-list-check me-2"></i>
                                        Step 3: Selected Services (0)
                                    </h4>
                                    <small class="opacity-75">Inspection services and test methods</small>
                                </div>
                                <div class="card-body">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                                        <p class="mb-0">No services have been selected for this inspection.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Step 4: Equipment Assignments -->
            @if($inspection->equipmentAssignments->count() > 0 || $inspection->inspectionEquipment->count() > 0)
                <div id="step4" class="row mb-5">
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-tools me-2"></i>
                                    Step 4: Equipment Assignments ({{ $inspection->equipmentAssignments->count() + $inspection->inspectionEquipment->count() }})
                                </h4>
                                <small class="opacity-75">Testing and inspection equipment assignments</small>
                            </div>
                            <div class="card-body">
                                @if($inspection->equipmentAssignments->count() > 0)
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-tools me-2"></i>Testing Equipment
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Equipment Name</th>
                                                    <th>Type</th>
                                                    <th>Brand/Model</th>
                                                    <th>Serial Number</th>
                                                    <th>Calibration</th>
                                                    <th>Condition</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($inspection->equipmentAssignments as $equipment)
                                                    <tr>
                                                        <td>{{ $equipment->equipment_name }}</td>
                                                        <td>{{ $equipment->equipment_type_name }}</td>
                                                        <td>{{ $equipment->brand_model ?? '-' }}</td>
                                                        <td>{{ $equipment->serial_number ?? '-' }}</td>
                                                        <td>
                                                            @if($equipment->calibration_due)
                                                                Due: {{ $equipment->calibration_due->format('d/m/Y') }}
                                                                @if($equipment->is_calibration_due)
                                                                    <br><span class="badge bg-warning">Due Soon</span>
                                                                @elseif($equipment->is_calibration_overdue)
                                                                    <br><span class="badge bg-danger">Overdue</span>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">Not Required</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">{{ $equipment->condition }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if($inspection->inspectionEquipment->count() > 0)
                                    @if($inspection->equipmentAssignments->count() > 0)
                                        <hr class="my-4">
                                    @endif
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-cogs me-2"></i>Equipment Under Inspection
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Equipment Type</th>
                                                    <th>Serial Number</th>
                                                    <th>Description</th>
                                                    <th>SWL</th>
                                                    <th>Test Load</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($inspection->inspectionEquipment as $equipment)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ ucfirst($equipment->category) }}</span>
                                                        </td>
                                                        <td>{{ $equipment->equipment_type }}</td>
                                                        <td>{{ $equipment->serial_number ?? '-' }}</td>
                                                        <td>{{ $equipment->description ?? '-' }}</td>
                                                        <td>
                                                            @if($equipment->swl)
                                                                {{ number_format($equipment->swl, 2) }} T
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($equipment->test_load_applied)
                                                                {{ number_format($equipment->test_load_applied, 2) }} T
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $equipment->status === 'pass' ? 'success' : ($equipment->status === 'fail' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($equipment->status ?: 'Pending') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Step 4 placeholder -->
                <div id="step4" class="row mb-5">
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-tools me-2"></i>
                                    Step 4: Equipment Assignments (0)
                                </h4>
                                <small class="opacity-75">Testing and inspection equipment assignments</small>
                            </div>
                            <div class="card-body">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <p class="mb-0">No equipment assignments found for this inspection.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 5: Consumables -->
            @if($inspection->consumableAssignments->count() > 0)
                <div id="step5" class="row mb-5">
                    <div class="col-12">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-boxes me-2"></i>
                                    Step 5: Consumables ({{ $inspection->consumableAssignments->count() }})
                                </h4>
                                <small class="opacity-75">Materials and consumables used in inspection</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Consumable</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Cost</th>
                                                <th>Expiry</th>
                                                <th>Condition</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection->consumableAssignments as $consumable)
                                                <tr>
                                                    <td>
                                                        {{ $consumable->consumable_name }}
                                                        @if($consumable->brand_manufacturer)
                                                            <br><small class="text-muted">{{ $consumable->brand_manufacturer }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $consumable->consumable_type_name }}</td>
                                                    <td>
                                                        @if($consumable->quantity_used)
                                                            {{ $consumable->quantity_used }} {{ $consumable->unit }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($consumable->total_cost)
                                                            ${{ number_format($consumable->total_cost, 2) }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($consumable->expiry_date)
                                                            {{ $consumable->expiry_date->format('d/m/Y') }}
                                                            @if($consumable->is_expiring)
                                                                <br><span class="badge bg-warning">Expiring</span>
                                                            @elseif($consumable->is_expired)
                                                                <br><span class="badge bg-danger">Expired</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">{{ $consumable->condition }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Step 5 placeholder -->
                <div id="step5" class="row mb-5">
                    <div class="col-12">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-boxes me-2"></i>
                                    Step 5: Consumables (0)
                                </h4>
                                <small class="opacity-75">Materials and consumables used in inspection</small>
                            </div>
                            <div class="card-body">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <p class="mb-0">No consumables were assigned to this inspection.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 6: Images & Documentation -->
            @php
                // Get images using the new relationship
                $images = $inspection->images;
                
                // Determine format based on image type - check if it's InspectionImage model instances
                $useNewFormat = $images->count() > 0 && $images->first() instanceof \App\Models\InspectionImage;
            @endphp
            
            @if($images && $images->count() > 0)
                <div id="step6" class="row mb-5">
                    <div class="col-12">
                        <div class="card border-dark">
                            <div class="card-header bg-dark text-white">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-camera me-2"></i>
                                    Step 6: Images & Documentation ({{ $images->count() }})
                                </h4>
                                <small class="opacity-75">Inspection photos and supporting documentation</small>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($images as $index => $image)
                                        @if($useNewFormat)
                                            {{-- New format with InspectionImage model --}}
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card inspection-image-card h-100">
                                                    <div class="position-relative">
                                                        <img src="{{ $image->url }}" 
                                                             alt="{{ $image->original_name }}" 
                                                             class="card-img-top inspection-image-preview"
                                                             style="height: 200px; object-fit: cover; cursor: pointer;"
                                                             onclick="showImageModal('{{ $image->url }}', '{{ $image->original_name }}', '{{ $image->caption }}')">
                                                        <div class="position-absolute top-0 end-0 m-2">
                                                            <button type="button" class="btn btn-light btn-sm rounded-circle" 
                                                                    onclick="showImageModal('{{ $image->url }}', '{{ $image->original_name }}', '{{ $image->caption }}')"
                                                                    title="View Full Size">
                                                                <i class="fas fa-expand"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <h6 class="card-title text-truncate" title="{{ $image->original_name }}">
                                                            {{ $image->original_name }}
                                                        </h6>
                                                        @if($image->caption)
                                                            <p class="card-text small text-muted">{{ $image->caption }}</p>
                                                        @endif
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">{{ $image->formatted_size }}</small>
                                                            <small class="text-muted">{{ $image->created_at ? $image->created_at->format('d/m/Y H:i') : 'Unknown date' }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Old format with JSON data --}}
                                            @php
                                                $imageData = is_string($image) ? json_decode($image, true) : $image;
                                                if (is_string($image)) {
                                                    $imageData = [
                                                        'dataUrl' => asset($image),
                                                        'name' => basename($image),
                                                        'caption' => '',
                                                        'size' => 'Unknown size'
                                                    ];
                                                }
                                            @endphp
                                            @if($imageData && (isset($imageData['dataUrl']) || isset($imageData['url'])))
                                                @php
                                                    $imageUrl = $imageData['dataUrl'] ?? $imageData['url'] ?? asset($image);
                                                    $imageName = $imageData['name'] ?? basename($image) ?? 'Inspection Image ' . ($index + 1);
                                                    $imageCaption = $imageData['caption'] ?? '';
                                                    $imageSize = $imageData['size'] ?? 'Unknown size';
                                                    $uploadDate = isset($imageData['uploadedAt']) ? \Carbon\Carbon::parse($imageData['uploadedAt'])->format('d/m/Y H:i') : null;
                                                @endphp
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="card inspection-image-card h-100">
                                                        <div class="position-relative">
                                                            <img src="{{ $imageUrl }}" 
                                                                 alt="{{ $imageName }}" 
                                                                 class="card-img-top inspection-image-preview"
                                                                 style="height: 200px; object-fit: cover; cursor: pointer;"
                                                                 onclick="showImageModal('{{ $imageUrl }}', '{{ $imageName }}', '{{ $imageCaption }}')">
                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                <button type="button" class="btn btn-light btn-sm rounded-circle" 
                                                                        onclick="showImageModal('{{ $imageUrl }}', '{{ $imageName }}', '{{ $imageCaption }}')"
                                                                        title="View Full Size">
                                                                    <i class="fas fa-expand"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h6 class="card-title text-truncate" title="{{ $imageName }}">
                                                                {{ $imageName }}
                                                            </h6>
                                                            @if(!empty($imageCaption))
                                                                <p class="card-text small text-muted">{{ $imageCaption }}</p>
                                                            @endif
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">{{ $imageSize }}</small>
                                                                @if($uploadDate)
                                                                    <small class="text-muted">{{ $uploadDate }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Step 6 placeholder -->
                <div id="step6" class="row mb-5">
                    <div class="col-12">
                        <div class="card border-dark">
                            <div class="card-header bg-dark text-white">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-camera me-2"></i>
                                    Step 6: Images & Documentation (0)
                                </h4>
                                <small class="opacity-75">Inspection photos and supporting documentation</small>
                            </div>
                            <div class="card-body">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <p class="mb-0">No images or documentation were uploaded for this inspection.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 7: Final Review & Results -->
            <div id="step7" class="row mb-5">
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-comments me-2"></i>
                                Step 7: Final Review & Results
                            </h4>
                            <small class="opacity-75">Inspector comments, findings, and recommendations</small>
                        </div>
                        <div class="card-body">
                            <!-- Comments & Recommendations -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-danger mb-3">
                                        <i class="fas fa-comment-dots me-2"></i>Inspector Comments
                                    </h6>
                                    <div class="bg-light p-3 rounded">
                                        {{ $inspection->inspector_comments ?: 'No comments provided' }}
                                    </div>

                                    <h6 class="text-danger mt-4 mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Defects Found
                                    </h6>
                                    <div class="bg-light p-3 rounded">
                                        {{ $inspection->defects_found ?: 'No defects reported' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-danger mb-3">
                                        <i class="fas fa-lightbulb me-2"></i>Recommendations
                                    </h6>
                                    <div class="bg-light p-3 rounded">
                                        {{ $inspection->recommendations ?: 'No recommendations provided' }}
                                    </div>

                                    <h6 class="text-danger mt-4 mb-3">
                                        <i class="fas fa-check-circle me-2"></i>Overall Result
                                    </h6>
                                    <div class="mb-3">
                                        @if($inspection->overall_result)
                                            <span class="badge bg-{{ $inspection->overall_result === 'pass' ? 'success' : ($inspection->overall_result === 'fail' ? 'danger' : 'warning') }} fs-6">
                                                {{ ucfirst(str_replace('_', ' ', $inspection->overall_result)) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">Not set</span>
                                        @endif
                                    </div>

                                    @if($inspection->next_inspection_date)
                                        <h6 class="text-danger mt-4 mb-3">
                                            <i class="fas fa-calendar-alt me-2"></i>Next Inspection Date
                                        </h6>
                                        <div class="bg-light p-3 rounded">
                                            {{ Carbon\Carbon::parse($inspection->next_inspection_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Service Inspector Assignments -->
                            @if($inspection->lifting_examination_inspector || $inspection->load_test_inspector || $inspection->thorough_examination_inspector || $inspection->mpi_service_inspector || $inspection->visual_inspector)
                                <hr class="my-4">
                                <h6 class="text-danger mb-3">
                                    <i class="fas fa-user-check me-2"></i>Service Inspector Assignments
                                </h6>
                                <div class="row">
                                    @if($inspection->lifting_examination_inspector)
                                        @php $liftingInspector = \App\Models\Personnel::find($inspection->lifting_examination_inspector); @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="border p-3 rounded">
                                                <strong class="text-primary">Lifting Examination Inspector:</strong>
                                                <div class="mt-2">
                                                    @if($liftingInspector)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-check text-success me-2"></i>
                                                            <span>{{ $liftingInspector->first_name }} {{ $liftingInspector->last_name }}</span>
                                                        </div>
                                                        @if($liftingInspector->position)
                                                            <small class="text-muted">{{ $liftingInspector->position }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Inspector ID: {{ $inspection->lifting_examination_inspector }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($inspection->load_test_inspector)
                                        @php $loadTestInspector = \App\Models\Personnel::find($inspection->load_test_inspector); @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="border p-3 rounded">
                                                <strong class="text-primary">Load Test Inspector:</strong>
                                                <div class="mt-2">
                                                    @if($loadTestInspector)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-check text-success me-2"></i>
                                                            <span>{{ $loadTestInspector->first_name }} {{ $loadTestInspector->last_name }}</span>
                                                        </div>
                                                        @if($loadTestInspector->position)
                                                            <small class="text-muted">{{ $loadTestInspector->position }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Inspector ID: {{ $inspection->load_test_inspector }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($inspection->thorough_examination_inspector)
                                        @php $thoroughInspector = \App\Models\Personnel::find($inspection->thorough_examination_inspector); @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="border p-3 rounded">
                                                <strong class="text-primary">Thorough Examination Inspector:</strong>
                                                <div class="mt-2">
                                                    @if($thoroughInspector)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-check text-success me-2"></i>
                                                            <span>{{ $thoroughInspector->first_name }} {{ $thoroughInspector->last_name }}</span>
                                                        </div>
                                                        @if($thoroughInspector->position)
                                                            <small class="text-muted">{{ $thoroughInspector->position }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Inspector ID: {{ $inspection->thorough_examination_inspector }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($inspection->mpi_service_inspector)
                                        @php $mpiInspector = \App\Models\Personnel::find($inspection->mpi_service_inspector); @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="border p-3 rounded">
                                                <strong class="text-primary">MPI Service Inspector:</strong>
                                                <div class="mt-2">
                                                    @if($mpiInspector)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-check text-success me-2"></i>
                                                            <span>{{ $mpiInspector->first_name }} {{ $mpiInspector->last_name }}</span>
                                                        </div>
                                                        @if($mpiInspector->position)
                                                            <small class="text-muted">{{ $mpiInspector->position }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Inspector ID: {{ $inspection->mpi_service_inspector }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($inspection->visual_inspector)
                                        @php $visualInspector = \App\Models\Personnel::find($inspection->visual_inspector); @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="border p-3 rounded">
                                                <strong class="text-primary">Visual Inspector:</strong>
                                                <div class="mt-2">
                                                    @if($visualInspector)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-check text-success me-2"></i>
                                                            <span>{{ $visualInspector->first_name }} {{ $visualInspector->last_name }}</span>
                                                        </div>
                                                        @if($visualInspector->position)
                                                            <small class="text-muted">{{ $visualInspector->position }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Inspector ID: {{ $inspection->visual_inspector }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Personnel Assignments -->
                            @if($inspection->personnelAssignments->count() > 0)
                                <hr class="my-4">
                                <h6 class="text-danger mb-3">
                                    <i class="fas fa-users me-2"></i>Personnel Assignments ({{ $inspection->personnelAssignments->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Role</th>
                                                <th>Certification</th>
                                                <th>Expiry</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection->personnelAssignments as $person)
                                                <tr>
                                                    <td>{{ $person->personnel_name }}</td>
                                                    <td>{{ $person->role_position }}</td>
                                                    <td>
                                                        @if($person->certification_level)
                                                            {{ $person->certification_level }}
                                                            @if($person->certification_number)
                                                                <br><small class="text-muted">{{ $person->certification_number }}</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($person->certification_expiry)
                                                            {{ $person->certification_expiry->format('d/m/Y') }}
                                                            @if($person->is_certification_expiring)
                                                                <br><span class="badge bg-warning">Expiring Soon</span>
                                                            @elseif($person->is_certification_expired)
                                                                <br><span class="badge bg-danger">Expired</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">{{ $person->status }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="col-12">
            <div class="card border-info mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        Comments & Communication
                    </h4>
                    <small class="badge bg-light text-info" id="commentCount">
                        {{ $inspection->activeComments->count() }} comments
                    </small>
                </div>
                <div class="card-body">
                    <!-- Comment Timeline -->
                    <div id="commentsTimeline" class="mb-4">
                        @forelse($inspection->activeComments as $comment)
                            <div class="comment-item mb-3 p-3 border rounded" id="comment-{{ $comment->id }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="comment-avatar me-3">
                                            <i class="fas fa-user-circle fa-2x text-{{ $comment->type_color }}"></i>
                                        </div>
                                        <div>
                                            <strong class="text-{{ $comment->type_color }}">{{ $comment->user->name }}</strong>
                                            <span class="badge bg-{{ $comment->type_color }} ms-2">{{ $comment->user->role }}</span>
                                            <small class="text-muted d-block">
                                                <i class="{{ $comment->type_icon }} me-1"></i>
                                                {{ $comment->formatted_type }} • {{ $comment->created_at->format('M j, Y g:i A') }}
                                                @if(isset($comment->metadata['edited_at']))
                                                    <span class="text-warning">(edited)</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    @if(auth()->user()->id === $comment->user_id || auth()->user()->hasRole(['super_admin', 'admin']))
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }})">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteComment({{ $comment->id }})">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </a></li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="comment-content">
                                    <p class="mb-0">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4" id="noCommentsMessage">
                                <i class="fas fa-comment-slash fa-3x mb-3"></i>
                                <p class="mb-0">No comments yet. Be the first to start the conversation!</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Add Comment Form -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <form id="addCommentForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group mb-3">
                                            <label for="commentType" class="form-label">Comment Type</label>
                                            <select class="form-select" id="commentType" name="comment_type">
                                                <option value="general">General Comment</option>
                                                @if(auth()->user()->hasRole(['qa', 'admin', 'super_admin']))
                                                    <option value="qa_review">QA Review</option>
                                                @endif
                                                @if(auth()->user()->hasRole(['inspector']) && $inspection->requiresRevision())
                                                    <option value="revision_response">Revision Response</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="commentText" class="form-label">Your Comment</label>
                                            <textarea class="form-control" id="commentText" name="comment" rows="3" 
                                                    placeholder="Add your comment or question here..." required></textarea>
                                            <div class="form-text">Maximum 2000 characters</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100" id="submitCommentBtn">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            Post Comment
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-warning me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone!
                </div>
                <p>Are you sure you want to delete inspection report <strong id="deleteInspectionNumber"></strong>?</p>
                <p class="text-muted">This will permanently remove:</p>
                <ul class="text-muted">
                    <li>The inspection report and all its data</li>
                    <li>All associated services</li>
                    <li>Personnel assignments</li>
                    <li>Equipment assignments</li>
                    <li>Consumable assignments</li>
                    <li>Any inspection results</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Image View Modal -->
<div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="imageViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageViewModalLabel">
                    <i class="fas fa-image me-2"></i>
                    <span id="imageModalTitle">Inspection Image</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="imageModalImg" src="" alt="" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                <div id="imageModalCaption" class="mt-3 text-muted" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <a id="imageDownloadLink" href="" download="" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Download Image
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.inspection-image-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.inspection-image-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.inspection-image-preview {
    transition: transform 0.2s ease-in-out;
}

.inspection-image-preview:hover {
    transform: scale(1.02);
}

@media print {
    .inspection-image-card {
        break-inside: avoid;
        page-break-inside: avoid;
    }
    
    .inspection-image-preview {
        height: auto !important;
        max-height: 300px;
    }
}
</style>

<script>
function confirmDelete(inspectionId, inspectionNumber) {
    document.getElementById('deleteInspectionNumber').textContent = inspectionNumber;
    document.getElementById('deleteForm').action = '/inspections/' + inspectionId;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function showImageModal(imageSrc, imageName, imageCaption) {
    const modal = document.getElementById('imageViewModal');
    const modalImg = document.getElementById('imageModalImg');
    const modalTitle = document.getElementById('imageModalTitle');
    const modalCaption = document.getElementById('imageModalCaption');
    const downloadLink = document.getElementById('imageDownloadLink');
    
    // Set image source and title
    modalImg.src = imageSrc;
    modalImg.alt = imageName;
    modalTitle.textContent = imageName;
    
    // Set caption if available
    if (imageCaption && imageCaption.trim() !== '') {
        modalCaption.textContent = imageCaption;
        modalCaption.style.display = 'block';
    } else {
        modalCaption.style.display = 'none';
    }
    
    // Set download link
    downloadLink.href = imageSrc;
    downloadLink.download = imageName;
    
    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}
</script>

@push('styles')
<style>
/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}

/* Step cards styling */
.card.border-primary,
.card.border-success,
.card.border-info,
.card.border-warning,
.card.border-secondary,
.card.border-dark,
.card.border-danger {
    border-width: 2px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

/* Step header styling */
.card-header h4 {
    font-weight: 600;
}

.card-header small {
    font-size: 0.85rem;
}

/* Navigation buttons styling */
.btn-sm {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
}

/* Improve table responsiveness */
.table-responsive {
    border-radius: 0.375rem;
    overflow: hidden;
}

/* Step navigation enhancement */
.step-nav-card {
    position: sticky;
    top: 20px;
    z-index: 100;
}

/* Badge styling improvements */
.badge {
    font-size: 0.75rem;
}

/* Print styles */
@media print {
    .btn, .btn-group {
        display: none !important;
    }
    
    .card.border-primary .card-header,
    .card.border-success .card-header,
    .card.border-info .card-header,
    .card.border-warning .card-header,
    .card.border-secondary .card-header,
    .card.border-dark .card-header,
    .card.border-danger .card-header {
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .d-flex.flex-wrap.gap-2 {
        flex-direction: column;
    }
    
    .btn-sm {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
    }
}

/* Comments Section Styles */
.comment-item {
    transition: all 0.3s ease;
    position: relative;
}

.comment-item:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6 !important;
    transform: translateX(5px);
}

.comment-avatar {
    flex-shrink: 0;
}

.comment-content {
    word-wrap: break-word;
    line-height: 1.5;
}

.comment-item .dropdown-toggle {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.comment-item:hover .dropdown-toggle {
    opacity: 1;
}

#commentsTimeline {
    max-height: 600px;
    overflow-y: auto;
}

#commentsTimeline::-webkit-scrollbar {
    width: 6px;
}

#commentsTimeline::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#commentsTimeline::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#commentsTimeline::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.comment-item::before {
    content: '';
    position: absolute;
    left: -1px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: transparent;
    transition: background-color 0.3s ease;
}

.comment-item:hover::before {
    background: var(--bs-info);
}
</style>
@endpush

@push('scripts')
<script>
function quickQAAction(action) {
    // Redirect to QA review page for the action
    if (action === 'approve' || action === 'revision') {
        window.location.href = '/qa/review/{{ $inspection->id }}';
    }
}

// Comments functionality
document.addEventListener('DOMContentLoaded', function() {
    const addCommentForm = document.getElementById('addCommentForm');
    const commentsTimeline = document.getElementById('commentsTimeline');
    const commentCount = document.getElementById('commentCount');
    const noCommentsMessage = document.getElementById('noCommentsMessage');
    
    // Handle form submission
    addCommentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('submitCommentBtn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Posting...';
        
        fetch('{{ route("inspections.comments.store", $inspection->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new comment to timeline
                addCommentToTimeline(data.comment);
                
                // Reset form
                document.getElementById('commentText').value = '';
                document.getElementById('commentType').selectedIndex = 0;
                
                // Update comment count
                updateCommentCount(1);
                
                // Hide no comments message if visible
                if (noCommentsMessage && !noCommentsMessage.classList.contains('d-none')) {
                    noCommentsMessage.style.display = 'none';
                }
                
                // Show success message
                showAlert('Comment posted successfully!', 'success');
            } else {
                showAlert(data.message || 'Failed to post comment', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while posting the comment', 'danger');
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
});

function addCommentToTimeline(comment) {
    const commentsTimeline = document.getElementById('commentsTimeline');
    const noCommentsMessage = document.getElementById('noCommentsMessage');
    
    // Hide no comments message if it exists
    if (noCommentsMessage) {
        noCommentsMessage.style.display = 'none';
    }
    
    // Create comment HTML
    const commentHtml = `
        <div class="comment-item mb-3 p-3 border rounded" id="comment-${comment.id}">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center">
                    <div class="comment-avatar me-3">
                        <i class="fas fa-user-circle fa-2x text-${comment.type_color}"></i>
                    </div>
                    <div>
                        <strong class="text-${comment.type_color}">${comment.user.name}</strong>
                        <span class="badge bg-${comment.type_color} ms-2">${comment.user.role}</span>
                        <small class="text-muted d-block">
                            <i class="${comment.type_icon} me-1"></i>
                            ${comment.formatted_type} • ${comment.created_at}
                        </small>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="editComment(${comment.id})">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteComment(${comment.id})">
                            <i class="fas fa-trash me-2"></i>Delete
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="comment-content">
                <p class="mb-0">${comment.comment}</p>
            </div>
        </div>
    `;
    
    // Add to timeline
    commentsTimeline.insertAdjacentHTML('beforeend', commentHtml);
    
    // Scroll to new comment
    const newComment = document.getElementById(`comment-${comment.id}`);
    newComment.scrollIntoView({ behavior: 'smooth' });
}

function updateCommentCount(change) {
    const commentCount = document.getElementById('commentCount');
    if (commentCount) {
        const currentCount = parseInt(commentCount.textContent.match(/\d+/)[0]);
        const newCount = currentCount + change;
        commentCount.textContent = `${newCount} comments`;
    }
}

function editComment(commentId) {
    // Implement edit functionality
    showAlert('Edit functionality coming soon!', 'info');
}

function deleteComment(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        fetch(`{{ route("inspections.comments.store", $inspection->id) }}`.replace('/comments', `/comments/${commentId}`), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove comment from timeline
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                    updateCommentCount(-1);
                }
                showAlert('Comment deleted successfully!', 'success');
            } else {
                showAlert(data.message || 'Failed to delete comment', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting the comment', 'danger');
        });
    }
}

function showAlert(message, type) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert && alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush

@endsection
