<!-- Other Tests Service Section -->
<section class="form-section" id="section-thorough-examination">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-vials me-3"></i>
                    Other Tests
                </h2>
                <p class="section-description">
                    Other tests comments and notes
                </p>
            </div>
            <div class="section-indicator">
                <i class="fas fa-circle text-muted"></i>
            </div>
        </div>
    </div>

    <div class="section-content">
        <div class="col-12">
            <label class="form-label fw-semibold">
                Comments
            </label>
            <textarea class="form-control" name="thorough_examination_comments" rows="5" 
                      placeholder="Add comments about other tests...">{{ old('thorough_examination_comments', (isset($inspection) && $inspection?->otherTest) ? $inspection->otherTest->thorough_examination_comments : '') }}</textarea>
            <div class="form-text">
                <i class="fas fa-info-circle me-1"></i>
                Add any comments or notes about other tests.
            </div>
        </div>
    </div>
</section>