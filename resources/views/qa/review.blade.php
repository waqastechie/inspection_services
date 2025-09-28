@extends('layouts.app')

@section('title', 'QA Review - ' . $inspection->inspection_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-clipboard-check me-2 text-info"></i>
                        QA Review: {{ $inspection->inspection_number }}
                    </h1>
                    <p class="text-muted mb-0">Quality assurance review for inspection report</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('qa.pending') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Pending
                    </a>
                    <span class="badge bg-{{ $inspection->qa_status_color }} fs-6">{{ $inspection->qa_status_name }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Inspection Summary -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Inspection Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="fw-bold">Inspection Number:</td>
                                    <td>{{ $inspection->inspection_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Client:</td>
                                    <td>{{ $inspection->client->client_name ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Project:</td>
                                    <td>{{ $inspection->project_name ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Location:</td>
                                    <td>{{ $inspection->location ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Inspection Date:</td>
                                    <td>{{ $inspection->inspection_date ? $inspection->inspection_date->format('M d, Y') : 'Not set' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="fw-bold">Lead Inspector:</td>
                                    <td>{{ $inspection->lead_inspector_name ?? 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Equipment Type:</td>
                                    <td>{{ $inspection->equipment_type ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Overall Result:</td>
                                    <td>
                                        @if($inspection->overall_result)
                                            <span class="badge bg-{{ $inspection->overall_result == 'pass' ? 'success' : ($inspection->overall_result == 'fail' ? 'danger' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $inspection->overall_result)) }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not determined</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Report Date:</td>
                                    <td>{{ $inspection->report_date ? $inspection->report_date->format('M d, Y') : 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Created:</td>
                                    <td>
                                        @php
                                            $createdAt = $inspection->created_at;
                                            if (is_string($createdAt)) {
                                                $createdAt = \Carbon\Carbon::parse($createdAt);
                                            }
                                        @endphp
                                        @if($createdAt && $createdAt instanceof \Carbon\Carbon)
                                            {{ $createdAt->format('M d, Y') }} ({{ $createdAt->diffForHumans() }})
                                        @else
                                            {{ $inspection->created_at ?? 'Not set' }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Services -->
                    @if($inspection->services->count() > 0)
                        <div class="mt-3">
                            <h6 class="fw-bold">Services Performed:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($inspection->services as $service)
                                    <span class="badge bg-primary">{{ $service->service_type }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <div class="d-flex gap-2">
                            <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i>View Full Report
                            </a>
                            <a href="{{ route('inspections.pdf', $inspection->id) }}" class="btn btn-outline-success" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QA Review Form -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>QA Review Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($inspection->qa_status === 'pending_qa' || $inspection->qa_status === 'under_qa_review')
                        <!-- Review Form -->
                        <form id="qaReviewForm">
                            @csrf
                            
                            <!-- QA Comments -->
                            <div class="mb-3">
                                <label for="qa_comments" class="form-label fw-bold">
                                    <i class="fas fa-comment me-1"></i>Review Comments
                                </label>
                                <textarea class="form-control" id="qa_comments" name="qa_comments" rows="4" 
                                          placeholder="Enter your review comments here..."></textarea>
                                <div class="form-text">Optional comments about the inspection quality and completeness.</div>
                            </div>

                            <!-- Rejection Reason (always visible) -->
                            <div class="mb-3" id="rejectionReasonSection">
                                <label for="qa_rejection_reason" class="form-label fw-bold">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Reason <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="qa_rejection_reason" name="qa_rejection_reason" rows="3"
                                          placeholder="Specify the reason for rejection or revision request..."></textarea>
                                <div class="form-text">Required when rejecting or requesting revision.</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" onclick="submitQADecision('approve')">
                                    <i class="fas fa-check-circle me-2"></i>Approve Inspection
                                </button>
                                <button type="button" class="btn btn-warning" onclick="submitQADecision('revision')">
                                    <i class="fas fa-edit me-2"></i>Request Revision
                                </button>
                                <button type="button" class="btn btn-danger" onclick="submitQADecision('reject')">
                                    <i class="fas fa-times-circle me-2"></i>Reject Inspection
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Already Reviewed -->
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-{{ $inspection->qa_status === 'qa_approved' ? 'check-circle text-success' : ($inspection->qa_status === 'qa_rejected' ? 'times-circle text-danger' : 'edit text-warning') }} fa-3x"></i>
                            </div>
                            <h6>{{ $inspection->qa_status_name }}</h6>
                            @if($inspection->qaReviewer)
                                <p class="text-muted">Reviewed by: {{ $inspection->qaReviewer->name }}</p>
                                <p class="text-muted">
                                    @if($inspection->qa_reviewed_at instanceof \Carbon\Carbon)
                                        {{ $inspection->qa_reviewed_at->format('M d, Y \a\t g:i A') }}
                                    @else
                                        {{ $inspection->qa_reviewed_at ?? 'Not set' }}
                                    @endif
                                </p>
                            @endif
                            @if($inspection->qa_comments)
                                <div class="mt-3">
                                    <h6>Comments:</h6>
                                    <p class="text-muted">{{ $inspection->qa_comments }}</p>
                                </div>
                            @endif
                            @if($inspection->qa_rejection_reason)
                                <div class="mt-3">
                                    <h6>Rejection Reason:</h6>
                                    <p class="text-danger">{{ $inspection->qa_rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent QA Activity -->
    @if($inspection->qa_reviewed_at)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>QA Review History
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $inspection->qa_status_color }}"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">{{ $inspection->qa_status_name }}</h6>
                                    <p class="timeline-description">
                                        Reviewed by {{ $inspection->qaReviewer->name ?? 'Unknown' }} 
                                        on 
                                        @if($inspection->qa_reviewed_at instanceof \Carbon\Carbon)
                                            {{ $inspection->qa_reviewed_at->format('M d, Y \a\t g:i A') }}
                                        @else
                                            {{ $inspection->qa_reviewed_at ?? 'Not set' }}
                                        @endif
                                    </p>
                                    @if($inspection->qa_comments)
                                        <div class="timeline-comments">
                                            <strong>Comments:</strong> {{ $inspection->qa_comments }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm QA Decision</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage"></p>
                <div id="confirmationDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }
    
    .timeline-content {
        padding-left: 0.5rem;
    }
    
    .timeline-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .timeline-description {
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .timeline-comments {
        background-color: #f8f9fa;
        padding: 0.75rem;
        border-radius: 0.375rem;
        border-left: 4px solid #0d6efd;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentAction = '';
    
    window.submitQADecision = function(action) {
        currentAction = action;
        
        // Rejection reason field is always visible; only set required for reject/revision
        const rejectionInput = document.getElementById('qa_rejection_reason');
        if (action === 'reject' || action === 'revision') {
            rejectionInput.required = true;
        } else {
            rejectionInput.required = false;
        }
        
        // Prepare confirmation modal
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        const message = document.getElementById('confirmationMessage');
        const details = document.getElementById('confirmationDetails');
        const confirmBtn = document.getElementById('confirmActionBtn');
        
        // Set modal content based on action
        switch (action) {
            case 'approve':
                message.textContent = 'Are you sure you want to approve this inspection?';
                details.innerHTML = '<div class="alert alert-success">This will mark the inspection as QA approved and allow it to be completed.</div>';
                confirmBtn.className = 'btn btn-success';
                confirmBtn.textContent = 'Approve';
                break;
            case 'revision':
                message.textContent = 'Are you sure you want to request revision for this inspection?';
                details.innerHTML = '<div class="alert alert-warning">This will send the inspection back to the inspector for revision.</div>';
                confirmBtn.className = 'btn btn-warning';
                confirmBtn.textContent = 'Request Revision';
                break;
            case 'reject':
                message.textContent = 'Are you sure you want to reject this inspection?';
                details.innerHTML = '<div class="alert alert-danger">This will mark the inspection as rejected and require significant rework.</div>';
                confirmBtn.className = 'btn btn-danger';
                confirmBtn.textContent = 'Reject';
                break;
        }
        
        modal.show();
    }
    
    // Confirm action button click
    document.getElementById('confirmActionBtn').addEventListener('click', function() {
        if (currentAction === 'reject' || currentAction === 'revision') {
            const rejectionReason = document.getElementById('qa_rejection_reason').value.trim();
            if (!rejectionReason) {
                alert('Please provide a reason for ' + (currentAction === 'reject' ? 'rejection' : 'revision request') + '.');
                return;
            }
        }
        
        // Submit the form
        const formData = new FormData(document.getElementById('qaReviewForm'));
        let submitUrl = '';
        if (currentAction === 'approve') {
            submitUrl = '/qa/approve/{{ $inspection->id }}';
        } else if (currentAction === 'revision') {
            submitUrl = '/qa/request-revision/{{ $inspection->id }}';
        } else if (currentAction === 'reject') {
            submitUrl = '/qa/reject/{{ $inspection->id }}';
        }
        
        // Debug: Log the URL being used
        console.log('Submitting to URL:', submitUrl);
        console.log('Action:', currentAction);

        // Show loading state
        const confirmBtn = document.getElementById('confirmActionBtn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'Processing...';
        confirmBtn.disabled = true;

        // Create a temporary form for submission
        const tempForm = document.createElement('form');
        tempForm.method = 'POST';
        tempForm.action = submitUrl;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        tempForm.appendChild(csrfInput);
        
        // Add method spoofing for PATCH
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        tempForm.appendChild(methodInput);
        
        // Add form data (fix duplicate declaration)
        const qaFormData = new FormData(document.getElementById('qaReviewForm'));
        for (let [key, value] of qaFormData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            tempForm.appendChild(input);
        }
        
        // Submit form
        document.body.appendChild(tempForm);
        tempForm.submit();
    });
    
    // Auto-save draft comments
    let commentTimeout;
    document.getElementById('qa_comments').addEventListener('input', function() {
        clearTimeout(commentTimeout);
        commentTimeout = setTimeout(function() {
            // Could implement auto-save functionality here
        }, 2000);
    });
</script>
@endpush