{{-- Edit Status Section --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-clipboard-check me-2"></i>Inspection Status & Workflow
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <p class="text-muted mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Manage the inspection status, workflow progression, and approval process. 
                    Update the status based on inspection progress and findings.
                </p>

                {{-- Current Status --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="inspection_status" class="form-label">
                            <i class="fas fa-flag me-1"></i>Inspection Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="inspection_status" name="inspection_status" required>
                            <option value="">Select Status</option>
                            <option value="draft" {{ (isset($inspection) && $inspection->status == 'draft') ? 'selected' : '' }}>
                                <i class="fas fa-edit"></i> Draft
                            </option>
                            <option value="in_progress" {{ (isset($inspection) && $inspection->status == 'in_progress') ? 'selected' : '' }}>
                                <i class="fas fa-play"></i> In Progress
                            </option>
                            <option value="on_hold" {{ (isset($inspection) && $inspection->status == 'on_hold') ? 'selected' : '' }}>
                                <i class="fas fa-pause"></i> On Hold
                            </option>
                            <option value="completed" {{ (isset($inspection) && $inspection->status == 'completed') ? 'selected' : '' }}>
                                <i class="fas fa-check"></i> Completed
                            </option>
                            <option value="under_review" {{ (isset($inspection) && $inspection->status == 'under_review') ? 'selected' : '' }}>
                                <i class="fas fa-search"></i> Under Review
                            </option>
                            <option value="approved" {{ (isset($inspection) && $inspection->status == 'approved') ? 'selected' : '' }}>
                                <i class="fas fa-check-circle"></i> Approved
                            </option>
                            <option value="rejected" {{ (isset($inspection) && $inspection->status == 'rejected') ? 'selected' : '' }}>
                                <i class="fas fa-times-circle"></i> Rejected
                            </option>
                            <option value="cancelled" {{ (isset($inspection) && $inspection->status == 'cancelled') ? 'selected' : '' }}>
                                <i class="fas fa-ban"></i> Cancelled
                            </option>
                        </select>
                        <div class="form-text">Current status of the inspection</div>
                    </div>

                    <div class="col-md-6">
                        <label for="completion_percentage" class="form-label">
                            <i class="fas fa-percentage me-1"></i>Completion Percentage
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="completion_percentage" name="completion_percentage" 
                                   min="0" max="100" value="{{ $inspection->completion_percentage ?? 0 }}" 
                                   placeholder="0">
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="form-text">Estimated completion percentage</div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-chart-line me-1"></i>Progress Overview
                    </label>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar" id="progressBar" role="progressbar" 
                             style="width: {{ $inspection->completion_percentage ?? 0 }}%;" 
                             aria-valuenow="{{ $inspection->completion_percentage ?? 0 }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            <span id="progressText">{{ $inspection->completion_percentage ?? 0 }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Status Change Reason --}}
                <div class="mb-4" id="statusChangeReason" style="display: none;">
                    <label for="status_change_reason" class="form-label">
                        <i class="fas fa-comment-alt me-1"></i>Reason for Status Change
                    </label>
                    <textarea class="form-control" id="status_change_reason" name="status_change_reason" rows="3" 
                              placeholder="Please provide a reason for changing the inspection status...">{{ $inspection->status_change_reason ?? '' }}</textarea>
                    <div class="form-text">Explain why the status is being changed</div>
                </div>

                {{-- Workflow Information --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="assigned_to" class="form-label">
                            <i class="fas fa-user me-1"></i>Assigned To
                        </label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">Select Assignee</option>
                            @if(isset($inspectors))
                                @foreach($inspectors as $inspector)
                                    <option value="{{ $inspector->id }}" 
                                            {{ (isset($inspection) && $inspection->assigned_to == $inspector->id) ? 'selected' : '' }}>
                                        {{ $inspector->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="1" {{ (isset($inspection) && $inspection->assigned_to == '1') ? 'selected' : '' }}>John Smith</option>
                                <option value="2" {{ (isset($inspection) && $inspection->assigned_to == '2') ? 'selected' : '' }}>Sarah Johnson</option>
                                <option value="3" {{ (isset($inspection) && $inspection->assigned_to == '3') ? 'selected' : '' }}>Mike Wilson</option>
                                <option value="4" {{ (isset($inspection) && $inspection->assigned_to == '4') ? 'selected' : '' }}>Emma Davis</option>
                            @endif
                        </select>
                        <div class="form-text">Person responsible for this inspection</div>
                    </div>

                    <div class="col-md-6">
                        <label for="reviewer" class="form-label">
                            <i class="fas fa-user-check me-1"></i>Reviewer
                        </label>
                        <select class="form-select" id="reviewer" name="reviewer">
                            <option value="">Select Reviewer</option>
                            @if(isset($inspectors))
                                @foreach($inspectors as $inspector)
                                    <option value="{{ $inspector->id }}" 
                                            {{ (isset($inspection) && $inspection->reviewer == $inspector->id) ? 'selected' : '' }}>
                                        {{ $inspector->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="1" {{ (isset($inspection) && $inspection->reviewer == '1') ? 'selected' : '' }}>John Smith</option>
                                <option value="2" {{ (isset($inspection) && $inspection->reviewer == '2') ? 'selected' : '' }}>Sarah Johnson</option>
                                <option value="3" {{ (isset($inspection) && $inspection->reviewer == '3') ? 'selected' : '' }}>Mike Wilson</option>
                                <option value="4" {{ (isset($inspection) && $inspection->reviewer == '4') ? 'selected' : '' }}>Emma Davis</option>
                            @endif
                        </select>
                        <div class="form-text">Person responsible for reviewing this inspection</div>
                    </div>
                </div>

                {{-- Important Dates --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="started_at" class="form-label">
                            <i class="fas fa-play me-1"></i>Started At
                        </label>
                        <input type="datetime-local" class="form-control" id="started_at" name="started_at" 
                               value="{{ isset($inspection->started_at) ? date('Y-m-d\TH:i', strtotime($inspection->started_at)) : '' }}">
                        <div class="form-text">When the inspection was started</div>
                    </div>

                    <div class="col-md-4">
                        <label for="completed_at" class="form-label">
                            <i class="fas fa-check me-1"></i>Completed At
                        </label>
                        <input type="datetime-local" class="form-control" id="completed_at" name="completed_at" 
                               value="{{ isset($inspection->completed_at) ? date('Y-m-d\TH:i', strtotime($inspection->completed_at)) : '' }}">
                        <div class="form-text">When the inspection was completed</div>
                    </div>

                    <div class="col-md-4">
                        <label for="reviewed_at" class="form-label">
                            <i class="fas fa-search me-1"></i>Reviewed At
                        </label>
                        <input type="datetime-local" class="form-control" id="reviewed_at" name="reviewed_at" 
                               value="{{ isset($inspection->reviewed_at) ? date('Y-m-d\TH:i', strtotime($inspection->reviewed_at)) : '' }}">
                        <div class="form-text">When the inspection was reviewed</div>
                    </div>
                </div>

                {{-- Quality Control --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-shield-alt me-1"></i>Quality Control
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="qc_data_complete" name="qc_checks[]" value="data_complete"
                                       {{ (isset($inspection) && str_contains($inspection->qc_checks ?? '', 'data_complete')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="qc_data_complete">
                                    All required data is complete
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="qc_calculations_verified" name="qc_checks[]" value="calculations_verified"
                                       {{ (isset($inspection) && str_contains($inspection->qc_checks ?? '', 'calculations_verified')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="qc_calculations_verified">
                                    Calculations verified
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="qc_standards_compliance" name="qc_checks[]" value="standards_compliance"
                                       {{ (isset($inspection) && str_contains($inspection->qc_checks ?? '', 'standards_compliance')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="qc_standards_compliance">
                                    Standards compliance verified
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="qc_documentation_complete" name="qc_checks[]" value="documentation_complete"
                                       {{ (isset($inspection) && str_contains($inspection->qc_checks ?? '', 'documentation_complete')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="qc_documentation_complete">
                                    Documentation complete
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="qc_images_attached" name="qc_checks[]" value="images_attached"
                                       {{ (isset($inspection) && str_contains($inspection->qc_checks ?? '', 'images_attached')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="qc_images_attached">
                                    Required images attached
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="qc_peer_reviewed" name="qc_checks[]" value="peer_reviewed"
                                       {{ (isset($inspection) && str_contains($inspection->qc_checks ?? '', 'peer_reviewed')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="qc_peer_reviewed">
                                    Peer reviewed
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Review Comments --}}
                <div class="mb-4">
                    <label for="review_comments" class="form-label">
                        <i class="fas fa-comments me-1"></i>Review Comments
                    </label>
                    <textarea class="form-control" id="review_comments" name="review_comments" rows="3" 
                              placeholder="Comments from reviewer about the inspection quality, completeness, or required changes...">{{ $inspection->review_comments ?? '' }}</textarea>
                    <div class="form-text">Comments from the reviewer</div>
                </div>

                {{-- Approval Section --}}
                <div class="mb-4" id="approvalSection" style="display: none;">
                    <h6 class="mb-3">
                        <i class="fas fa-stamp me-1"></i>Approval
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="approved_by" class="form-label">
                                <i class="fas fa-user-shield me-1"></i>Approved By
                            </label>
                            <input type="text" class="form-control" id="approved_by" name="approved_by" 
                                   value="{{ $inspection->approved_by ?? '' }}" 
                                   placeholder="Approver name">
                            <div class="form-text">Name of the person approving this inspection</div>
                        </div>

                        <div class="col-md-6">
                            <label for="approved_at" class="form-label">
                                <i class="fas fa-calendar-check me-1"></i>Approved At
                            </label>
                            <input type="datetime-local" class="form-control" id="approved_at" name="approved_at" 
                                   value="{{ isset($inspection->approved_at) ? date('Y-m-d\TH:i', strtotime($inspection->approved_at)) : '' }}">
                            <div class="form-text">Date and time of approval</div>
                        </div>
                    </div>
                </div>

                {{-- Status History --}}
                @if(isset($inspection) && $inspection->id)
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-history me-1"></i>Status History
                    </h6>
                    <div class="timeline">
                        {{-- This would typically come from a status_history table --}}
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Inspection Created</h6>
                                <p class="timeline-text">Initial inspection record created</p>
                                <small class="text-muted">{{ $inspection->created_at ?? 'N/A' }}</small>
                            </div>
                        </div>
                        @if($inspection->started_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Inspection Started</h6>
                                <p class="timeline-text">Inspection work began</p>
                                <small class="text-muted">{{ $inspection->started_at }}</small>
                            </div>
                        </div>
                        @endif
                        @if($inspection->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Inspection Completed</h6>
                                <p class="timeline-text">Inspection work finished</p>
                                <small class="text-muted">{{ $inspection->completed_at }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Status Management --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('inspection_status');
    const completionInput = document.getElementById('completion_percentage');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const statusChangeReason = document.getElementById('statusChangeReason');
    const approvalSection = document.getElementById('approvalSection');
    
    let originalStatus = statusSelect.value;
    
    // Status change handler
    statusSelect.addEventListener('change', function() {
        const newStatus = this.value;
        
        // Show/hide status change reason
        if (newStatus !== originalStatus && originalStatus) {
            statusChangeReason.style.display = 'block';
        } else {
            statusChangeReason.style.display = 'none';
        }
        
        // Show/hide approval section
        if (newStatus === 'approved' || newStatus === 'rejected') {
            approvalSection.style.display = 'block';
        } else {
            approvalSection.style.display = 'none';
        }
        
        // Auto-update completion percentage based on status
        updateCompletionByStatus(newStatus);
        
        // Auto-fill timestamps
        updateTimestamps(newStatus);
    });
    
    // Completion percentage change handler
    completionInput.addEventListener('input', function() {
        updateProgressBar(this.value);
    });
    
    // Initialize progress bar
    updateProgressBar(completionInput.value);
    
    // Initialize sections based on current status
    if (statusSelect.value === 'approved' || statusSelect.value === 'rejected') {
        approvalSection.style.display = 'block';
    }
    
    function updateCompletionByStatus(status) {
        const statusPercentages = {
            'draft': 0,
            'in_progress': 25,
            'on_hold': completionInput.value, // Keep current value
            'completed': 100,
            'under_review': 90,
            'approved': 100,
            'rejected': completionInput.value, // Keep current value
            'cancelled': 0
        };
        
        if (statusPercentages[status] !== undefined && status !== 'on_hold' && status !== 'rejected') {
            completionInput.value = statusPercentages[status];
            updateProgressBar(statusPercentages[status]);
        }
    }
    
    function updateProgressBar(percentage) {
        const value = Math.max(0, Math.min(100, parseInt(percentage) || 0));
        progressBar.style.width = value + '%';
        progressBar.setAttribute('aria-valuenow', value);
        progressText.textContent = value + '%';
        
        // Update progress bar color based on percentage
        progressBar.className = 'progress-bar';
        if (value < 25) {
            progressBar.classList.add('bg-danger');
        } else if (value < 50) {
            progressBar.classList.add('bg-warning');
        } else if (value < 75) {
            progressBar.classList.add('bg-info');
        } else {
            progressBar.classList.add('bg-success');
        }
    }
    
    function updateTimestamps(status) {
        const now = new Date().toISOString().slice(0, 16);
        
        switch(status) {
            case 'in_progress':
                if (!document.getElementById('started_at').value) {
                    document.getElementById('started_at').value = now;
                }
                break;
            case 'completed':
                if (!document.getElementById('completed_at').value) {
                    document.getElementById('completed_at').value = now;
                }
                break;
            case 'under_review':
                if (!document.getElementById('reviewed_at').value) {
                    document.getElementById('reviewed_at').value = now;
                }
                break;
            case 'approved':
                if (!document.getElementById('approved_at').value) {
                    document.getElementById('approved_at').value = now;
                }
                break;
        }
    }
    
    // QC checks handler
    const qcCheckboxes = document.querySelectorAll('input[name="qc_checks[]"]');
    qcCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('input[name="qc_checks[]"]:checked').length;
            const totalCount = qcCheckboxes.length;
            const qcPercentage = Math.round((checkedCount / totalCount) * 100);
            
            // Update completion percentage if QC is being tracked
            if (statusSelect.value === 'under_review' || statusSelect.value === 'completed') {
                const basePercentage = statusSelect.value === 'completed' ? 85 : 75;
                const adjustedPercentage = basePercentage + (qcPercentage * 0.15);
                completionInput.value = Math.min(100, Math.round(adjustedPercentage));
                updateProgressBar(completionInput.value);
            }
        });
    });
});

// Validate status changes
function validateStatusChange() {
    const status = document.getElementById('inspection_status').value;
    const qcChecks = document.querySelectorAll('input[name="qc_checks[]"]:checked');
    
    if (status === 'approved') {
        if (qcChecks.length === 0) {
            alert('Please complete quality control checks before approving the inspection.');
            return false;
        }
        
        if (!document.getElementById('approved_by').value) {
            alert('Please specify who is approving this inspection.');
            return false;
        }
    }
    
    if (status === 'rejected' && !document.getElementById('review_comments').value.trim()) {
        alert('Please provide review comments explaining why the inspection is being rejected.');
        return false;
    }
    
    return true;
}
</script>

{{-- Timeline CSS --}}
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0 0 5px 0;
    font-size: 13px;
    color: #6c757d;
}
</style>