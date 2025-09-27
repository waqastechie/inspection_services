{{-- Step 7: Review & Submit --}}
<div class="step-content">
    <div class="alert alert-info">
        <h5><i class="fas fa-info-circle me-2"></i>Review Your Inspection</h5>
        <p>Please review all the information below before submitting for QA review.</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h6>Final Comments & Recommendations</h6>
            @include('inspections.sections.comments-recommendations')
        </div>
        <div class="col-md-6">
            <h6>Environmental Conditions</h6>
            @include('inspections.sections.environmental-conditions')
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Summary</h6>
                    <p class="text-muted">Once you submit this inspection, it will be sent to QA for review and approval.</p>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmSubmission" required>
                        <label class="form-check-label" for="confirmSubmission">
                            I confirm that all information entered is accurate and complete
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>