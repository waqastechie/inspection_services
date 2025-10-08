<!-- Drop Test Form Section - Edit Wizard Version -->
<div class="form-section" id="section-drop-test">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="section-title">
                    <i class="fas fa-arrow-down me-3 text-danger"></i>
                    Drop Test
                </h3>
                <p class="section-description">
                    Configure drop test parameters and record results
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
                <label for="drop_test_load" class="form-label fw-bold">
                    Test Load <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="drop_test_load" 
                           name="drop_test_load" 
                           value="{{ isset($inspection->otherTest) ? $inspection->otherTest->drop_test_load : '5t' }}"
                           placeholder="Enter test load">
                    <span class="input-group-text">t</span>
                </div>
                <div class="form-text">Specify the test load weight</div>
            </div>

            <!-- Drop -->
            <div class="col-md-6">
                <label for="drop_type" class="form-label fw-bold">
                    Drop <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="drop_type" name="drop_type">
                    <option value="">Select drop type</option>
                    <option value="freely" {{ (isset($inspection->otherTest) && $inspection->otherTest->drop_type == 'freely') ? 'selected' : ((!isset($inspection->otherTest) || !$inspection->otherTest->drop_type) ? 'selected' : '') }}>Drop freely</option>
                    <option value="controlled" {{ (isset($inspection->otherTest) && $inspection->otherTest->drop_type == 'controlled') ? 'selected' : '' }}>Controlled drop</option>
                    <option value="guided" {{ (isset($inspection->otherTest) && $inspection->otherTest->drop_type == 'guided') ? 'selected' : '' }}>Guided drop</option>
                </select>
                <div class="form-text">Select the type of drop test</div>
            </div>

            <!-- Drop Distance -->
            <div class="col-md-6">
                <label for="drop_distance" class="form-label fw-bold">
                    Drop Distance <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="drop_distance" 
                           name="drop_distance" 
                           value="{{ isset($inspection->otherTest) ? $inspection->otherTest->drop_distance : '50mm' }}"
                           placeholder="Enter drop distance">
                    <span class="input-group-text">mm</span>
                </div>
                <div class="form-text">Distance of the drop test</div>
            </div>

            <!-- Suspended -->
            <div class="col-md-6">
                <label for="drop_suspended" class="form-label fw-bold">
                    Suspended <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       class="form-control" 
                       id="drop_suspended" 
                       name="drop_suspended" 
                       value="{{ isset($inspection->otherTest) ? $inspection->otherTest->drop_suspended : '' }}"
                       placeholder="Enter suspension details">
                <div class="form-text">Enter suspension details or status</div>
            </div>

            <!-- Impact Speed -->
            <div class="col-md-6">
                <label for="drop_impact_speed" class="form-label fw-bold">
                    Impact Speed <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="drop_impact_speed" 
                           name="drop_impact_speed" 
                           value="{{ isset($inspection->otherTest) ? $inspection->otherTest->drop_impact_speed : '1m/sec' }}"
                           placeholder="Enter impact speed">
                    <span class="input-group-text">m/sec</span>
                </div>
                <div class="form-text">Speed at which impact occurs</div>
            </div>

            <!-- Result -->
            <div class="col-12">
                <label for="drop_result" class="form-label fw-bold">
                    Result <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="drop_result" name="drop_result">
                    <option value="">Select result</option>
                    <option value="Pass" {{ (isset($inspection->otherTest) && $inspection->otherTest->drop_result == 'Pass') ? 'selected' : ((!isset($inspection->otherTest) || !$inspection->otherTest->drop_result) ? 'selected' : '') }}>Pass</option>
                    <option value="Fail" {{ (isset($inspection->otherTest) && $inspection->otherTest->drop_result == 'Fail') ? 'selected' : '' }}>Fail</option>
                </select>
                <div class="form-text">Test result outcome</div>
            </div>

            <!-- Additional Notes -->
            <div class="col-12">
                <label for="drop_notes" class="form-label fw-bold">
                    Additional Notes
                </label>
                <textarea class="form-control" 
                          id="drop_notes" 
                          name="drop_notes" 
                          rows="3" 
                          placeholder="Enter any additional observations or notes about the drop test...">{{ isset($inspection->otherTest) ? $inspection->otherTest->drop_notes : '' }}</textarea>
                <div class="form-text">Optional notes about the test procedure or observations</div>
            </div>
        </div>

        <!-- Test Status Indicator -->
        <div class="mt-4">
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                <div>
                    <strong>Drop Test Configuration:</strong> 
                    Ensure all parameters are properly set before conducting the test.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#section-drop-test .form-control:focus,
#section-drop-test .form-select:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

#section-drop-test .input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
    font-weight: 500;
}

#section-drop-test .section-title {
    color: #dc3545;
    font-size: 1.25rem;
    font-weight: 600;
}

#section-drop-test .alert-info {
    background-color: #e7f3ff;
    border-color: #b8daff;
    color: #004085;
}
</style>