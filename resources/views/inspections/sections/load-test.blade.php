<!-- Load Test Service Section -->
<section class="form-section" id="section-load-test">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-weight-hanging me-3"></i>
                    Load Test
                </h2>
                <p class="section-description">
                    Load testing procedures and results
                </p>
            </div>
            <div class="section-indicator">
                <i class="fas fa-circle text-muted"></i>
            </div>
        </div>
    </div>

    <div class="section-content">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Test Load Applied <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="test_load_applied" 
                       placeholder="Enter test load (e.g., 125% SWL)">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Test Duration
                </label>
                <input type="text" class="form-control" name="test_duration" 
                       placeholder="Enter test duration">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Load Test Result <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="load_test_result">
                    <option value="">Select Result...</option>
                    <option value="pass">Pass</option>
                    <option value="fail">Fail</option>
                    <option value="conditional">Conditional Pass</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Test Certificate Reference
                </label>
                <input type="text" class="form-control" name="test_certificate_ref" 
                       placeholder="Enter certificate reference">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">
                    Load Test Comments
                </label>
                <textarea class="form-control" name="load_test_comments" rows="4" 
                          placeholder="Enter any observations, measurements, or comments about the load test..."></textarea>
            </div>
        </div>
    </div>
</section>
