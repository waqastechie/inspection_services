<!-- Lowering Test Form Section -->
<div class="form-section" id="section-lowering-test">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="section-title">
                    <i class="fas fa-arrow-circle-down me-3 text-info"></i>
                    Lowering Test
                </h3>
                <p class="section-description">
                    Configure lowering test parameters and record results
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
                <label for="lowering_test_load" class="form-label fw-bold">
                    Test Load <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="lowering_test_load" 
                           name="lowering_test_load" 
                           value="{{ old('lowering_test_load', $inspection?->otherTest?->lowering_test_load ?? '2t') }}"
                           placeholder="Enter test load">
                    <span class="input-group-text">t</span>
                </div>
                <div class="form-text">Specify the test load weight</div>
            </div>

            <!-- Impact Speed -->
            <div class="col-md-6">
                <label for="lowering_impact_speed" class="form-label fw-bold">
                    Impact Speed <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="lowering_impact_speed" 
                           name="lowering_impact_speed" 
                           value="{{ old('lowering_impact_speed', $inspection?->otherTest?->lowering_impact_speed ?? '1.5m/sec') }}"
                           placeholder="Enter impact speed">
                    <span class="input-group-text">m/sec</span>
                </div>
                <div class="form-text">Speed during lowering operation</div>
            </div>

            <!-- Result -->
            <div class="col-md-6">
                <label for="lowering_result" class="form-label fw-bold">
                    Result <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="lowering_result" name="lowering_result">
                    <option value="">Select result</option>
                    <option value="Pass/Fail" {{ old('lowering_result', $inspection?->otherTest?->lowering_result ?? '') == 'Pass/Fail' ? 'selected' : '' }}>Pass/Fail</option>
                    <option value="Pass" {{ old('lowering_result', $inspection?->otherTest?->lowering_result ?? '') == 'Pass' ? 'selected' : '' }}>Pass</option>
                    <option value="Fail" {{ old('lowering_result', $inspection?->otherTest?->lowering_result ?? '') == 'Fail' ? 'selected' : '' }}>Fail</option>
                    <option value="Pending" {{ old('lowering_result', $inspection?->otherTest?->lowering_result ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <div class="form-text">Test result outcome</div>
            </div>

            <!-- Lowering Test Details -->
            <div class="col-md-6">
                <label for="lowering_method" class="form-label fw-bold">
                    Lowering Method
                </label>
                <select class="form-select" id="lowering_method" name="lowering_method">
                    <option value="">Select method</option>
                    <option value="Controlled" {{ old('lowering_method', $inspection?->otherTest?->lowering_method ?? '') == 'Controlled' ? 'selected' : '' }}>Controlled</option>
                    <option value="Free Fall" {{ old('lowering_method', $inspection?->otherTest?->lowering_method ?? '') == 'Free Fall' ? 'selected' : '' }}>Free Fall</option>
                    <option value="Gradual" {{ old('lowering_method', $inspection?->otherTest?->lowering_method ?? '') == 'Gradual' ? 'selected' : '' }}>Gradual</option>
                    <option value="Stepped" {{ old('lowering_method', $inspection?->otherTest?->lowering_method ?? '') == 'Stepped' ? 'selected' : '' }}>Stepped</option>
                </select>
                <div class="form-text">Method used for lowering</div>
            </div>

            <!-- Additional Parameters -->
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="lowering_distance" class="form-label fw-bold">
                            Lowering Distance
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="lowering_distance" 
                                   name="lowering_distance" 
                                   value="{{ old('lowering_distance', $inspection?->otherTest?->lowering_distance ?? '') }}"
                                   placeholder="Enter distance">
                            <span class="input-group-text">m</span>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="lowering_duration" class="form-label fw-bold">
                            Test Duration
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="lowering_duration" 
                                   name="lowering_duration" 
                                   value="{{ old('lowering_duration', $inspection?->otherTest?->lowering_duration ?? '') }}"
                                   placeholder="Enter duration">
                            <span class="input-group-text">sec</span>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="lowering_cycles" class="form-label fw-bold">
                            Number of Cycles
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="lowering_cycles" 
                               name="lowering_cycles" 
                               value="{{ old('lowering_cycles', $inspection?->otherTest?->lowering_cycles ?? '') }}"
                               placeholder="Enter cycles"
                               min="1">
                    </div>
                </div>
            </div>

            <!-- Safety Parameters -->
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="brake_efficiency" class="form-label fw-bold">
                            Brake Efficiency
                        </label>
                        <select class="form-select" id="brake_efficiency" name="brake_efficiency">
                            <option value="">Select efficiency</option>
                            <option value="Excellent" {{ old('brake_efficiency', $inspection?->otherTest?->brake_efficiency ?? '') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="Good" {{ old('brake_efficiency', $inspection?->otherTest?->brake_efficiency ?? '') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('brake_efficiency', $inspection?->otherTest?->brake_efficiency ?? '') == 'Fair' ? 'selected' : '' }}>Fair</option>
                            <option value="Poor" {{ old('brake_efficiency', $inspection?->otherTest?->brake_efficiency ?? '') == 'Poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="control_response" class="form-label fw-bold">
                            Control Response
                        </label>
                        <select class="form-select" id="control_response" name="control_response">
                            <option value="">Select response</option>
                            <option value="Immediate" {{ old('control_response', $inspection?->otherTest?->control_response ?? '') == 'Immediate' ? 'selected' : '' }}>Immediate</option>
                            <option value="Delayed" {{ old('control_response', $inspection?->otherTest?->control_response ?? '') == 'Delayed' ? 'selected' : '' }}>Delayed</option>
                            <option value="Sluggish" {{ old('control_response', $inspection?->otherTest?->control_response ?? '') == 'Sluggish' ? 'selected' : '' }}>Sluggish</option>
                            <option value="Unresponsive" {{ old('control_response', $inspection?->otherTest?->control_response ?? '') == 'Unresponsive' ? 'selected' : '' }}>Unresponsive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="col-12">
                <label for="lowering_notes" class="form-label fw-bold">
                    Additional Notes
                </label>
                <textarea class="form-control" 
                          id="lowering_notes" 
                          name="lowering_notes" 
                          rows="3" 
                          placeholder="Enter any additional observations or notes about the lowering test...">{{ old('lowering_notes', $inspection?->otherTest?->lowering_notes ?? '') }}</textarea>
                <div class="form-text">Optional notes about the test procedure or observations</div>
            </div>
        </div>

        <!-- Test Status Indicator -->
        <div class="mt-4">
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                <div>
                    <strong>Lowering Test Configuration:</strong> 
                    Verify all safety systems are operational before conducting the lowering test.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#section-lowering-test .form-control:focus,
#section-lowering-test .form-select:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

#section-lowering-test .input-group-text {
    background-color: #e1f5fe;
    border-color: #b3e5fc;
    color: #0277bd;
    font-weight: 500;
}

#section-lowering-test .section-title {
    color: #17a2b8;
    font-size: 1.25rem;
    font-weight: 600;
}

#section-lowering-test .alert-info {
    background-color: #e1f5fe;
    border-color: #b3e5fc;
    color: #0277bd;
}
</style>