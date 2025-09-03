<!-- Thorough Examination Service Section -->
<section class="form-section" id="section-thorough-examination">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-eye me-3"></i>
                    Thorough Examination
                </h2>
                <p class="section-description">
                    Detailed thorough examination procedures
                </p>
            </div>
            <div class="section-indicator">
                <i class="fas fa-circle text-muted"></i>
            </div>
        </div>
    </div>

    <div class="section-content">
        <!-- Inspector Assignment -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-user-check me-2"></i>Assigned Inspector
            </label>
            <select class="form-control" name="thorough_examination_inspector" required>
                <option value="">Select Inspector</option>
                @if(isset($personnel))
                    @foreach($personnel as $person)
                        <option value="{{ $person->id }}" 
                                {{ old('thorough_examination_inspector', $inspection?->thorough_examination_inspector ?? '') == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }} - {{ $person->job_title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Examination Type <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="examination_type">
                    <option value="">Select Type...</option>
                    <option value="initial">Initial Thorough Examination</option>
                    <option value="periodic">Periodic Thorough Examination</option>
                    <option value="after_installation">After Installation</option>
                    <option value="after_repair">After Repair/Modification</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Examination Standard
                </label>
                <input type="text" class="form-control" name="examination_standard" 
                       placeholder="e.g., LOLER 1998, BS EN 14492">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Examination Result <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="examination_result">
                    <option value="">Select Result...</option>
                    <option value="satisfactory">Satisfactory</option>
                    <option value="defects_noted">Defects Noted</option>
                    <option value="dangerous_defects">Dangerous Defects</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Next Examination Due
                </label>
                <input type="date" class="form-control" name="next_examination_due">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">
                    Examination Findings
                </label>
                <textarea class="form-control" name="examination_findings" rows="4" 
                          placeholder="Document examination findings, defects found, and recommendations..."></textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">
                    Remedial Action Required
                </label>
                <textarea class="form-control" name="remedial_action" rows="3" 
                          placeholder="Specify any remedial action required before next use..."></textarea>
            </div>
        </div>
    </div>
</section>
