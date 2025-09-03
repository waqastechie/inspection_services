<!-- Export Section -->
<section class="form-section" id="section-export">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-save"></i>
            Save Report
        </h2>
        <p class="section-description">
            Your data is automatically saved as you type. Submit the report to finalize it.
        </p>
    </div>

    <div class="section-content">
        <!-- Hidden inputs for JSON data -->
        <input type="hidden" name="selected_services" id="selectedServicesInput">
        <input type="hidden" name="personnel_assignments" id="personnelAssignmentsInput">
        <input type="hidden" name="equipment_assignments" id="equipmentAssignmentsInput">
        <input type="hidden" name="consumable_assignments" id="consumableAssignmentsInput">
        
        <!-- Current Draft Status -->
        <div class="draft-status-container mb-4" id="draftStatusContainer" style="display: none;">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-save fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">Auto-Save Active</h6>
                        <p class="mb-0">Inspection Number: <strong id="draftInspectionNumber">-</strong></p>
                        <small class="text-muted">Last saved <span id="lastSaveTime">just now</span></small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Completion Status -->
        <div class="completion-status-container mb-4">
            <div class="alert alert-success" id="exportSectionStatus">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">Ready to Submit</h6>
                        <p class="mb-0">Your data is being automatically saved. Click "Submit Report" to finalize the inspection.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row g-4">
            <div class="col-md-12">
                <div class="export-option-card">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="export-icon mb-3">
                                <i class="fas fa-check fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Submit Report</h5>
                            <p class="card-text">
                                Finalize and submit the inspection report.
                            </p>
                            <button type="submit" class="btn btn-success btn-lg" id="saveReportBtn">
                                <i class="fas fa-check me-2"></i>
                                <span class="btn-text">Submit Report</span>
                            </button>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Data is automatically saved as you type
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Completion Progress</span>
                <span class="text-muted" id="completionPercentage">Ready</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" 
                     aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" id="completionProgressBar"></div>
            </div>
        </div>

        <!-- Additional Actions -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>
                    Print Preview
                </button>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-warning" onclick="clearDraft()">
                    <i class="fas fa-trash me-2"></i>
                    Clear Form
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="validateForm()">
                    <i class="fas fa-check me-2"></i>
                    Validate Form
                </button>
            </div>
        </div>
    </div>
</section>