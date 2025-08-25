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
                            <p><strong>Type:</strong> {{ $inspection->equipment_type }}</p>
                            @if($inspection->equipment_description)
                                <p><strong>Description:</strong> {{ $inspection->equipment_description }}</p>
                            @endif
                            @if($inspection->manufacturer)
                                <p><strong>Manufacturer:</strong> {{ $inspection->manufacturer }}</p>
                            @endif
                            @if($inspection->model)
                                <p><strong>Model:</strong> {{ $inspection->model }}</p>
                            @endif
                            @if($inspection->serial_number)
                                <p><strong>Serial Number:</strong> {{ $inspection->serial_number }}</p>
                            @endif
                            @if($inspection->capacity)
                                <p><strong>Capacity:</strong> {{ $inspection->capacity }} {{ $inspection->capacity_unit }}</p>
                            @endif
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
                                        <div class="col-md-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h6 class="text-primary">{{ $service->service_type_name }}</h6>
                                                @if($service->service_data && is_array($service->service_data))
                                                    @if(isset($service->service_data['description']))
                                                        <p class="small text-muted mb-2">{{ $service->service_data['description'] }}</p>
                                                    @endif
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-{{ $service->status_color }}">{{ ucfirst($service->status) }}</span>
                                                    @if($service->notes)
                                                        <small class="text-muted">{{ $service->notes }}</small>
                                                    @endif
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

<script>
function confirmDelete(inspectionId, inspectionNumber) {
    document.getElementById('deleteInspectionNumber').textContent = inspectionNumber;
    document.getElementById('deleteForm').action = '/inspections/' + inspectionId;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
