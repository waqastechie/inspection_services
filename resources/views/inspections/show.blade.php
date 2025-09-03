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
                </div>
            </div>

            <!-- Basic Information -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Client:</strong> {{ $inspection->client_name }}</p>
                                    <p><strong>Project:</strong> {{ $inspection->project_name }}</p>
                                    <p><strong>Location:</strong> {{ $inspection->location }}</p>
                                    <p><strong>Inspection Date:</strong> {{ $inspection->inspection_date->format('d/m/Y') }}</p>
                                    @if($inspection->weather_conditions)
                                        <p><strong>Weather:</strong> {{ $inspection->weather_conditions }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Lead Inspector:</strong> {{ $inspection->lead_inspector_name }}</p>
                                    <p><strong>Certification:</strong> {{ $inspection->lead_inspector_certification }}</p>
                                    <p><strong>Report Date:</strong> {{ $inspection->report_date->format('d/m/Y') }}</p>
                                    @if($inspection->temperature)
                                        <p><strong>Temperature:</strong> {{ $inspection->temperature }}°C</p>
                                    @endif
                                    @if($inspection->humidity)
                                        <p><strong>Humidity:</strong> {{ $inspection->humidity }}%</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs me-2"></i>Equipment Under Test
                            </h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Type:</strong> {{ $inspection->equipment_type ?? '-' }}</p>
                            <p><strong>Description:</strong> {{ $inspection->equipment_description ?? '-' }}</p>
                            <p><strong>Manufacturer:</strong> {{ $inspection->manufacturer ?? '-' }}</p>
                            <p><strong>Model:</strong> {{ $inspection->model ?? '-' }}</p>
                            <p><strong>Serial Number:</strong> {{ $inspection->serial_number ?? '-' }}</p>
                            <p><strong>Capacity:</strong> {{ $inspection->capacity ?? '-' }} {{ $inspection->capacity_unit ?? '' }}</p>
                            <p><strong>Year:</strong> {{ $inspection->manufacture_year ?? '-' }}</p>
                            <p><strong>Notes:</strong> {{ $inspection->general_notes ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services -->
            @if($inspection->services->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-list-check me-2"></i>Selected Services ({{ $inspection->services->count() }})
                                </h5>
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
                                                        <span class="badge bg-{{ $service->status_color }} fs-6">{{ ucfirst($service->status) }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @php
                                                        $serviceData = is_string($service->service_data) ? json_decode($service->service_data, true) : $service->service_data;
                                                        $serviceData = $serviceData ?: [];
                                                    @endphp
                                                    
                                                    @if(isset($serviceData['service_name']))
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Service Name:</strong>
                                                            <p class="mb-1">{{ $serviceData['service_name'] }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(isset($serviceData['service_description']))
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Description:</strong>
                                                            <p class="mb-1 text-muted">{{ $serviceData['service_description'] }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(isset($serviceData['test_parameters']))
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Test Parameters:</strong>
                                                            <p class="mb-1">{{ $serviceData['test_parameters'] }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(isset($serviceData['acceptance_criteria']))
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Acceptance Criteria:</strong>
                                                            <p class="mb-1">{{ $serviceData['acceptance_criteria'] }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(isset($serviceData['applicable_codes']))
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Applicable Codes:</strong>
                                                            <p class="mb-1">{{ $serviceData['applicable_codes'] }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="row">
                                                        @if(isset($serviceData['estimated_duration']))
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <strong class="text-primary">Duration:</strong>
                                                                    <p class="mb-1">{{ $serviceData['estimated_duration'] }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                        @if(isset($serviceData['cost_estimate']))
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <strong class="text-primary">Cost Estimate:</strong>
                                                                    <p class="mb-1">${{ number_format($serviceData['cost_estimate']) }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Personnel Assignments -->
            @if($inspection->personnelAssignments->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users me-2"></i>Personnel Assignments ({{ $inspection->personnelAssignments->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
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
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Equipment Assignments -->
            @if($inspection->equipmentAssignments->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-tools me-2"></i>Equipment Assignments ({{ $inspection->equipmentAssignments->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
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
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Consumables -->
            @if($inspection->consumableAssignments->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-boxes me-2"></i>Consumables ({{ $inspection->consumableAssignments->count() }})
                                </h5>
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
            @endif

            <!-- General Notes -->
            @if($inspection->general_notes)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>General Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $inspection->general_notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Inspection Images -->
            @php
                $inspection_images = $inspection->inspection_images;
                if (is_string($inspection_images)) {
                    $inspection_images = json_decode($inspection_images, true) ?: [];
                }
                $inspection_images = $inspection_images ?: [];
            @endphp
            @if(is_array($inspection_images) && count($inspection_images) > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-camera me-2"></i>Inspection Images ({{ count($inspection_images) }})
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($inspection_images as $index => $image)
                                        @php
                                            // Handle both complex image objects and simple file paths
                                            if (is_string($image)) {
                                                // Simple file path
                                                $imageData = [
                                                    'dataUrl' => asset($image),
                                                    'name' => basename($image),
                                                    'caption' => '',
                                                    'size' => 'Unknown size',
                                                    'uploadedAt' => null
                                                ];
                                            } else {
                                                // Complex image object
                                                $imageData = is_string($image) ? json_decode($image, true) : $image;
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
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
@endsection
