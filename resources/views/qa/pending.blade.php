@extends('layouts.app')

@section('title', 'Pending QA Review')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-clock me-2 text-warning"></i>
                        Pending QA Review
                    </h1>
                    <p class="text-muted mb-0">Inspections awaiting quality assurance review</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('qa.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <span class="badge bg-warning fs-6">{{ $inspections->total() }} Pending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Inspections Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Inspections Awaiting Review
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($inspections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Inspection #</th>
                                        <th>Client</th>
                                        <th>Project</th>
                                        <th>Inspector</th>
                                        <th>Date Created</th>
                                        <th>Services</th>
                                        <th>Priority</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inspections as $inspection)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong>{{ $inspection->inspection_number }}</strong>
                                                    <small class="text-muted">ID: {{ $inspection->id }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $inspection->client->client_name ?? 'Unknown' }}</span>
                                                    @if($inspection->location)
                                                        <small class="text-muted">{{ Str::limit($inspection->location, 30) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($inspection->project_name)
                                                    <span>{{ Str::limit($inspection->project_name, 40) }}</span>
                                                @else
                                                    <span class="text-muted">No project name</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $inspection->lead_inspector_name ?? 'Not assigned' }}</span>
                                                    @if($inspection->lead_inspector_certification)
                                                        <small class="text-muted">{{ $inspection->lead_inspector_certification }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $inspection->created_at->format('M d, Y') }}</span>
                                                    <small class="text-muted">{{ $inspection->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($inspection->services->count() > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($inspection->services->take(2) as $service)
                                                            <span class="badge bg-primary">{{ $service->service_type }}</span>
                                                        @endforeach
                                                        @if($inspection->services->count() > 2)
                                                            <span class="badge bg-secondary">+{{ $inspection->services->count() - 2 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No services</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $daysSinceCreated = $inspection->created_at->diffInDays(now());
                                                    $priorityClass = $daysSinceCreated > 7 ? 'danger' : ($daysSinceCreated > 3 ? 'warning' : 'success');
                                                    $priorityText = $daysSinceCreated > 7 ? 'High' : ($daysSinceCreated > 3 ? 'Medium' : 'Normal');
                                                @endphp
                                                <span class="badge bg-{{ $priorityClass }}">{{ $priorityText }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('inspections.show', $inspection->id) }}" 
                                                       class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('qa.review', $inspection) }}" 
                                                       class="btn btn-warning" title="Start Review">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($inspections->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Showing {{ $inspections->firstItem() }} to {{ $inspections->lastItem() }} 
                                        of {{ $inspections->total() }} results
                                    </div>
                                    {{ $inspections->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle fa-4x text-success opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-3">No Pending Reviews</h5>
                            <p class="text-muted mb-4">Great job! All inspections have been reviewed.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('qa.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                </a>
                                <a href="{{ route('qa.history') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-history me-2"></i>View History
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
    
    // Add tooltips to action buttons
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush