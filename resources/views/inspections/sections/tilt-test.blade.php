<!-- Tilt Test Form Section -->
<div class="form-section" id="section-tilt-test">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="section-title">
                    <i class="fas fa-balance-scale me-3 text-warning"></i>
                    Tilt Test
                </h3>
                <p class="section-description">
                    Configure tilt test parameters and record results
                </p>
            </div>
            <div class="section-indicator">
                <i class="fas fa-circle text-muted"></i>
            </div>
        </div>
    </div>

    <div class="section-content">
        <div class="row g-3">
            <!-- Test Load -->
            <div class="col-md-6">
                <label for="tilt_test_load" class="form-label fw-bold">
                    Test Load <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="tilt_test_load" 
                           name="tilt_test_load" 
                           value="2t"
                           placeholder="Enter test load">
                    <span class="input-group-text">t</span>
                </div>
                <div class="form-text">Specify the test load weight</div>
            </div>

            <!-- Loaded Tilt @ -->
            <div class="col-md-6">
                <label for="loaded_tilt" class="form-label fw-bold">
                    Loaded Tilt @ <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="loaded_tilt" 
                           name="loaded_tilt" 
                           value="30Degree"
                           placeholder="Enter loaded tilt angle">
                    <span class="input-group-text">°</span>
                </div>
                <div class="form-text">Tilt angle when loaded</div>
            </div>

            <!-- Empty Tilt @ -->
            <div class="col-md-6">
                <label for="empty_tilt" class="form-label fw-bold">
                    Empty Tilt @ <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="empty_tilt" 
                           name="empty_tilt" 
                           value="30Degree"
                           placeholder="Enter empty tilt angle">
                    <span class="input-group-text">°</span>
                </div>
                <div class="form-text">Tilt angle when empty</div>
            </div>

            <!-- Results -->
            <div class="col-md-6">
                <label for="tilt_results" class="form-label fw-bold">
                    Results <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="tilt_results" name="tilt_results">
                    <option value="">Select result</option>
                    <option value="Pass/Fail" selected>Pass/Fail</option>
                    <option value="Pass">Pass</option>
                    <option value="Fail">Fail</option>
                    <option value="Pending">Pending</option>
                </select>
                <div class="form-text">Test result outcome</div>
            </div>

            <!-- Tilt Test Details -->
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="tilt_stability" class="form-label fw-bold">
                            Stability Check
                        </label>
                        <select class="form-select" id="tilt_stability" name="tilt_stability">
                            <option value="">Select stability</option>
                            <option value="Stable">Stable</option>
                            <option value="Unstable">Unstable</option>
                            <option value="Marginal">Marginal</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="tilt_direction" class="form-label fw-bold">
                            Tilt Direction
                        </label>
                        <select class="form-select" id="tilt_direction" name="tilt_direction">
                            <option value="">Select direction</option>
                            <option value="Forward">Forward</option>
                            <option value="Backward">Backward</option>
                            <option value="Left">Left</option>
                            <option value="Right">Right</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="tilt_duration" class="form-label fw-bold">
                            Test Duration
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="tilt_duration" 
                                   name="tilt_duration" 
                                   placeholder="Enter duration">
                            <span class="input-group-text">sec</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="col-12">
                <label for="tilt_notes" class="form-label fw-bold">
                    Additional Notes
                </label>
                <textarea class="form-control" 
                          id="tilt_notes" 
                          name="tilt_notes" 
                          rows="3" 
                          placeholder="Enter any additional observations or notes about the tilt test..."></textarea>
                <div class="form-text">Optional notes about the test procedure or observations</div>
            </div>
        </div>

        <!-- Test Status Indicator -->
        <div class="mt-4">
            <div class="alert alert-warning d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Tilt Test Configuration:</strong> 
                    Ensure proper safety measures are in place before conducting the tilt test.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#section-tilt-test .form-control:focus,
#section-tilt-test .form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

#section-tilt-test .input-group-text {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
    font-weight: 500;
}

#section-tilt-test .section-title {
    color: #ffc107;
    font-size: 1.25rem;
    font-weight: 600;
}

#section-tilt-test .alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}
</style>