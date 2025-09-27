@extends('layouts.app')

@section('title', 'Under QA Review')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-eye me-2 text-info"></i>
                        Under QA Review
                    </h1>
                    <p class="text-muted mb-0">Inspections currently being reviewed</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('qa.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <span class="badge bg-info fs-6">{{ $inspections->total() }} Under Review</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Under Review Inspections Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Inspections Under Review
                        </h5>
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
                                        <th>Reviewer</th>
                                        <th>Review Started</th>
                                        <th>Duration</th>
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
                                                @if($inspection->qaReviewer)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $inspection->qaReviewer->name }}</span>
                                                        <small class="text-muted">{{ $inspection->qaReviewer->email }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $inspection->updated_at->format('M d, Y') }}</span>
                                                    <small class="text-muted">{{ $inspection->updated_at->format('g:i A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $duration = $inspection->updated_at->diffForHumans(null, true);
                                                    $hours = $inspection->updated_at->diffInHours(now());
                                                    $durationClass = $hours > 24 ? 'danger' : ($hours > 8 ? 'warning' : 'success');
                                                @endphp
                                                <span class="badge bg-{{ $durationClass }}">{{ $duration }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('inspections.show', $inspection->id) }}" 
                                                       class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($inspection->qa_reviewer_id === auth()->id() || auth()->user()->isSuperAdmin())
                                                        <a href="{{ route('qa.review', $inspection) }}" 
                                                           class="btn btn-info" title="Continue Review">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
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
                                <i class="fas fa-search fa-4x text-info opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-3">No Inspections Under Review</h5>
                            <p class="text-muted mb-4">No inspections are currently being reviewed.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('qa.pending') }}" class="btn btn-warning">
                                    <i class="fas fa-clock me-2"></i>Check Pending Reviews
                                </a>
                                <a href="{{ route('qa.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
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