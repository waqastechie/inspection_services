@extends('layouts.master')

@section('title', 'Inspection Reports - Professional Inspection Services')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-list me-3"></i>
                    Inspection Reports
                </h1>
                <a href="{{ route('inspections.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    New Inspection Report
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Reports</h6>
                                    <h2 class="mb-0">{{ $inspections->count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Completed</h6>
                                    <h2 class="mb-0">{{ $inspections->where('status', 'completed')->count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">In Progress</h6>
                                    <h2 class="mb-0">{{ $inspections->where('status', 'in_progress')->count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Draft</h6>
                                    <h2 class="mb-0">{{ $inspections->where('status', 'draft')->count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-edit fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        Recent Inspection Reports
                    </h5>
                </div>
                <div class="card-body">
                    @if($inspections->count() > 0)
                        <div class="table-responsive">
                            <table id="inspectionsTable" class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Report #</th>
                                        <th>Serial #</th>
                                        <th>Client</th>
                                        <th>Project</th>
                                        <th>Date</th>
                                        <th>Inspector</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inspections as $index => $inspection)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">{{ $inspection->inspection_number }}</strong>
                                            </td>
                                            <td>
                                                @if($inspection->serial_number)
                                                    <span class="badge bg-secondary">{{ $inspection->serial_number }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $inspection->client?->client_name ?? 'N/A' }}</td>
                                            <td>{{ $inspection->project_name ?: 'N/A' }}</td>
                                            <td>{{ $inspection->inspection_date->format('d/m/Y') }}</td>
                                            <td>{{ $inspection->lead_inspector_name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $inspection->status_color }}">
                                                    {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('inspections.show', $inspection->id) }}" 
                                                       class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(auth()->user()->isSuperAdmin())
                                                        <a href="{{ route('inspections.edit', $inspection->id) }}" 
                                                           class="btn btn-outline-warning" title="Edit Report">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if($inspection->status == 'qa' && auth()->user()->isSuperAdmin())
                                                        <form method="POST" action="{{ route('inspections.complete', $inspection->id) }}" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-outline-info" title="Mark as Completed" 
                                                                    onclick="return confirm('Mark this inspection as completed?')">
                                                                <i class="fas fa-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('inspections.pdf', $inspection->id) }}" 
                                                       class="btn btn-outline-success" title="Download PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                    @if(auth()->user()->isSuperAdmin())
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                title="Delete Report"
                                                                onclick="confirmDelete({{ $inspection->id }}, '{{ $inspection->inspection_number }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif

                                                    <!-- QA Review Button and Status Badge for QA Users -->
                                                    @if(auth()->user()->canApproveInspections() && ($inspection->qa_status === 'pending_qa' || $inspection->qa_status === 'under_qa_review'))
                                                        <a href="{{ route('qa.review', $inspection) }}" class="btn btn-warning" title="QA Review">
                                                            <i class="fas fa-shield-check"></i>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->canApproveInspections() && $inspection->qa_status)
                                                        <span class="badge bg-{{ $inspection->qa_status_color }}" title="QA Status">
                                                            <i class="fas fa-shield-check me-1"></i>{{ $inspection->qa_status_name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- DataTables will handle pagination automatically -->
                    @else
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">No Reports Found</h6>
                                    <p class="mb-0">
                                        You haven't created any inspection reports yet. 
                                        <a href="{{ route('inspections.create') }}" class="alert-link">Create your first report</a> to get started.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
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

@endsection

@push('scripts')
<script>
function confirmDelete(inspectionId, inspectionNumber) {
    document.getElementById('deleteInspectionNumber').textContent = inspectionNumber;
    document.getElementById('deleteForm').action = '/inspections/' + inspectionId;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Initialize DataTable
$(document).ready(function() {
    $('#inspectionsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']], // Sort by Report # column descending
        language: {
            search: "Search reports:",
            lengthMenu: "Show _MENU_ reports per page",
            info: "Showing _START_ to _END_ of _TOTAL_ reports",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                last: '<i class="fas fa-angle-double-right"></i>'
            }
        },
        columnDefs: [
            { orderable: false, targets: [7] }, // Disable sorting on Actions column
            { searchable: false, targets: [7] }, // Disable search on Actions column
            { className: "text-center", targets: [6, 7] }, // Center align for Status, Actions
            { width: "20%", targets: 7 } // Set width for actions column
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        autoWidth: false
    });
});
</script>
@endpush
