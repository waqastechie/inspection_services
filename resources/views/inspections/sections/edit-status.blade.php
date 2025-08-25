<!-- Status Section -->
<div class="section-container mb-4">
    <div class="card shadow-lg border-0" style="border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-header text-white border-0" style="padding: 2rem; background: transparent;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="mb-2 fw-bold" style="font-size: 1.75rem;">
                        <i class="fas fa-tasks me-3" style="font-size: 1.5rem;"></i>Inspection Status
                    </h2>
                    <p class="mb-0 opacity-90" style="font-size: 1.1rem;">Update the status of this inspection report</p>
                </div>
                <div class="text-white opacity-50">
                    <i class="fas fa-clipboard-list" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm border-0 mt-n4" style="border-radius: 16px; margin-left: 1rem; margin-right: 1rem; z-index: 10; position: relative;">
        <div class="card-body" style="padding: 2rem; background: #f8fafc;">
            <div class="row g-4">
                <div class="col-12">
                    <div class="status-selection-container">
                        <label for="status" class="form-label fw-bold text-dark mb-3" style="font-size: 1.1rem;">
                            <i class="fas fa-flag me-2 text-primary"></i>Current Status
                        </label>
                        
                        <div class="status-options">
                            <!-- Draft Option -->
                            <div class="status-option" data-status="draft">
                                <input type="radio" 
                                       id="status_draft" 
                                       name="status" 
                                       value="draft" 
                                       class="status-radio d-none"
                                       {{ old('status', $inspection->status ?? '') == 'draft' ? 'checked' : '' }}>
                                <label for="status_draft" class="status-card">
                                    <div class="status-card-content">
                                        <div class="status-icon draft">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                        <div class="status-info">
                                            <h6 class="status-title">Draft</h6>
                                            <p class="status-desc">Report is being prepared</p>
                                        </div>
                                        <div class="status-check">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- In Progress Option -->
                            <div class="status-option" data-status="in_progress">
                                <input type="radio" 
                                       id="status_in_progress" 
                                       name="status" 
                                       value="in_progress" 
                                       class="status-radio d-none"
                                       {{ old('status', $inspection->status ?? '') == 'in_progress' ? 'checked' : '' }}>
                                <label for="status_in_progress" class="status-card">
                                    <div class="status-card-content">
                                        <div class="status-icon in-progress">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </div>
                                        <div class="status-info">
                                            <h6 class="status-title">In Progress</h6>
                                            <p class="status-desc">Inspection is currently being conducted</p>
                                        </div>
                                        <div class="status-check">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Completed Option -->
                            <div class="status-option" data-status="completed">
                                <input type="radio" 
                                       id="status_completed" 
                                       name="status" 
                                       value="completed" 
                                       class="status-radio d-none"
                                       {{ old('status', $inspection->status ?? '') == 'completed' ? 'checked' : '' }}>
                                <label for="status_completed" class="status-card">
                                    <div class="status-card-content">
                                        <div class="status-icon completed">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="status-info">
                                            <h6 class="status-title">Completed</h6>
                                            <p class="status-desc">Inspection finished and report finalized</p>
                                        </div>
                                        <div class="status-check">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Cancelled Option -->
                            <div class="status-option" data-status="cancelled">
                                <input type="radio" 
                                       id="status_cancelled" 
                                       name="status" 
                                       value="cancelled" 
                                       class="status-radio d-none"
                                       {{ old('status', $inspection->status ?? '') == 'cancelled' ? 'checked' : '' }}>
                                <label for="status_cancelled" class="status-card">
                                    <div class="status-card-content">
                                        <div class="status-icon cancelled">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="status-info">
                                            <h6 class="status-title">Cancelled</h6>
                                            <p class="status-desc">Inspection was cancelled</p>
                                        </div>
                                        <div class="status-check">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @error('status')
                            <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="notes-section">
                        <label for="status_notes" class="form-label fw-bold text-dark mb-3" style="font-size: 1.1rem;">
                            <i class="fas fa-sticky-note me-2 text-warning"></i>Status Change Notes
                        </label>
                        <div class="notes-container">
                            <textarea class="form-control notes-textarea" 
                                      id="status_notes" 
                                      name="status_notes" 
                                      rows="4" 
                                      placeholder="Add notes about this status change (optional)..."
                                      style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; font-size: 1rem; transition: all 0.3s ease;">{{ old('status_notes') }}</textarea>
                            <div class="form-text mt-2" style="color: #6b7280; font-size: 0.9rem;">
                                <i class="fas fa-lightbulb me-1 text-warning"></i>
                                Optional notes about why the status was changed
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Status Selection Styles */
.status-options {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    max-width: 800px;
    margin: 0 auto;
}

.status-card {
    display: block;
    padding: 1.25rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.status-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    transform: translateY(-2px);
}

.status-card-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.status-icon.draft { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
.status-icon.in-progress { background: linear-gradient(135deg, #3b82f6, #06b6d4); }
.status-icon.completed { background: linear-gradient(135deg, #10b981, #059669); }
.status-icon.cancelled { background: linear-gradient(135deg, #ef4444, #dc2626); }

.status-info {
    flex-grow: 1;
}

.status-title {
    margin: 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.status-desc {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
}

.status-check {
    font-size: 1.25rem;
    color: #d1d5db;
    transition: all 0.3s ease;
}

/* Selected state */
.status-radio:checked + .status-card {
    border-color: #3b82f6;
    background: #eff6ff;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
}

.status-radio:checked + .status-card .status-check {
    color: #10b981;
}

/* Notes Section */
.notes-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
}

.notes-textarea:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .status-card-content {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .status-info-cards {
        gap: 0.5rem;
    }
    
    .info-card {
        padding: 0.5rem;
    }
}

/* Animation for spinner */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 2s linear infinite;
}
</style>
