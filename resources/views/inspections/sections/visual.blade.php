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
                    Visual inspection procedures and observations
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
            <select class="form-control" name="visual_inspector" required>
                <option value="">Select Inspector</option>
                @if(isset($personnel))
                    @foreach($personnel as $person)
                        <option value="{{ $person->id }}" 
                                {{ old('visual_inspector', $inspection?->visual_inspector ?? '') == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }} - {{ $person->job_title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Visual Examination Method <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="visual_method">
                    <option value="">Select Method...</option>
                    <option value="unaided_eye">Unaided Eye</option>
                    <option value="magnifying_glass">Magnifying Glass</option>
                    <option value="borescope">Borescope</option>
                    <option value="remote_viewing">Remote Viewing System</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Lighting Conditions
                </label>
                <select class="form-select" name="lighting_conditions">
                    <option value="">Select Lighting...</option>
                    <option value="natural">Natural Light</option>
                    <option value="artificial">Artificial Light</option>
                    <option value="supplemented">Supplemented Lighting</option>
                    <option value="inadequate">Inadequate</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Surface Condition
                </label>
                <select class="form-select" name="surface_condition">
                    <option value="">Select Condition...</option>
                    <option value="clean">Clean</option>
                    <option value="painted">Painted</option>
                    <option value="corroded">Corroded</option>
                    <option value="contaminated">Contaminated</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Visual Examination Result <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="visual_result">
                    <option value="">Select Result...</option>
                    <option value="acceptable">Acceptable</option>
                    <option value="defects_noted">Defects Noted</option>
                    <option value="reject">Reject</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">
                    Visual Observations
                </label>
                <textarea class="form-control" name="visual_observations" rows="4" 
                          placeholder="Document visual observations, defects, wear patterns, damage, etc..."></textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">
                    Defects Identified
                </label>
                <textarea class="form-control" name="visual_defects" rows="3" 
                          placeholder="List any defects identified during visual examination..."></textarea>
            </div>
        </div>
    </div>
</section>
