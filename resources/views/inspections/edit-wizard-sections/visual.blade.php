<!-- Visual Examination Service Section -->
<section class="form-section" id="section-visual">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-binoculars me-3"></i>
                    Visual Examination
                </h2>
                <p class="section-description">
                    Visual inspection comments and notes
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
            <textarea class="form-control" name="visual_comments" rows="5" 
                      placeholder="Add comments about the visual examination...">{{ old('visual_comments', (isset($inspection) && $inspection?->visualExamination) ? $inspection->visualExamination->visual_comments : '') }}</textarea>
            <div class="form-text">
                <i class="fas fa-info-circle me-1"></i>
                Add any comments or notes about the visual examination.
            </div>
        </div>
    </div>
</section>