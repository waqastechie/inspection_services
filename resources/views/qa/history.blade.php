@extends('layouts.app')

@section('title', 'QA Review History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-history me-2 text-secondary"></i>
                        QA Review History
                    </h1>
                    <p class="text-muted mb-0">Previously reviewed inspections</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('qa.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <span class="badge bg-secondary fs-6">{{ $inspections->total() }} Reviewed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Review History
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
                                        <th>Review Date</th>
                                        <th>Decision</th>
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
                                                        @if($inspection->qaReviewer->id === auth()->id())
                                                            <small class="text-primary">You</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($inspection->qa_reviewed_at)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $inspection->qa_reviewed_at->format('M d, Y') }}</span>
                                                        <small class="text-muted">{{ $inspection->qa_reviewed_at->format('g:i A') }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Not recorded</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $inspection->qa_status_color }}">
                                                    {{ str_replace('_', ' ', ucfirst($inspection->qa_status)) }}
                                                </span>
                                                @if($inspection->qa_comments)
                                                    <i class="fas fa-comment-alt ms-1 text-muted" 
                                                       title="Has comments" data-bs-toggle="tooltip"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('inspections.show', $inspection->id) }}" 
                                                       class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($inspection->qa_comments || $inspection->qa_rejection_reason)
                                                        <button type="button" class="btn btn-outline-info" 
                                                                title="View Comments"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#commentsModal{{ $inspection->id }}">
                                                            <i class="fas fa-comment"></i>
                                                        </button>
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
                                <i class="fas fa-inbox fa-4x text-secondary opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-3">No Review History</h5>
                            <p class="text-muted mb-4">No inspections have been reviewed yet.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('qa.pending') }}" class="btn btn-warning">
                                    <i class="fas fa-clock me-2"></i>Start Reviewing
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

<!-- Comments Modals -->
@foreach($inspections as $inspection)
    @if($inspection->qa_comments || $inspection->qa_rejection_reason)
        <div class="modal fade" id="commentsModal{{ $inspection->id }}" tabindex="-1" aria-labelledby="commentsModalLabel{{ $inspection->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentsModalLabel{{ $inspection->id }}">
                            QA Comments - {{ $inspection->inspection_number }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($inspection->qa_rejection_reason)
                            <div class="mb-3">
                                <h6 class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Rejection Reason:
                                </h6>
                                <p class="alert alert-danger">{{ $inspection->qa_rejection_reason }}</p>
                            </div>
                        @endif
                        @if($inspection->qa_comments)
                            <div class="mb-3">
                                <h6><i class="fas fa-comment me-1"></i>Review Comments:</h6>
                                <p class="alert alert-info">{{ $inspection->qa_comments }}</p>
                            </div>
                        @endif
                        <div class="text-muted">
                            <small>
                                Reviewed by {{ $inspection->qaReviewer->name ?? 'Unknown' }} 
                                on {{ $inspection->qa_reviewed_at?->format('M d, Y \a\t g:i A') }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush