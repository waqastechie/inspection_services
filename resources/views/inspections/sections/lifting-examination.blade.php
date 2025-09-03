<!-- Lifting Examination Section -->
<section class="form-section" id="section-lifting-examination">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-clipboard-check me-3"></i>
                    Lifting
                </h2>
                <p class="section-description">
                    Lifting equipment examination questions
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
            <select class="form-control" name="lifting_examination_inspector" required>
                <option value="">Select Inspector</option>
                @if(isset($personnel))
                    @foreach($personnel as $person)
                        <option value="{{ $person->id }}" 
                                {{ old('lifting_examination_inspector', $inspection?->lifting_examination_inspector ?? '') == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }} - {{ $person->job_title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Question A -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold text-danger">
                A. Is this the first through examination of lifting equipment after installation or after assembly at a new site or in a new location?
            </label>
            
            <div class="d-flex gap-4 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="first_examination" id="firstExamYes" value="yes">
                    <label class="form-check-label" for="firstExamYes">
                        <i class="fas fa-check-circle text-success me-2"></i>Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="first_examination" id="firstExamNo" value="no">
                    <label class="form-check-label" for="firstExamNo">
                        <i class="fas fa-times-circle text-danger me-2"></i>No
                    </label>
                </div>
            </div>

            <!-- Conditional follow-up question -->
            <div id="equipmentInstallationQuestion" class="conditional-question" style="display: none;">
                <div class="alert alert-light border-start border-primary border-4 ps-4">
                    <label class="form-label fw-semibold">
                        If yes, has the equipment been installed correctly?
                    </label>
                    <textarea class="form-control mt-2" name="equipment_installation_details" 
                              rows="3" placeholder="Yes"></textarea>
                </div>
            </div>
        </div>

        <!-- Question B -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold text-danger">
                B. Is it the case that the equipment would be safe to operate?
            </label>
            
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="safe_to_operate" id="safeYes" value="yes">
                    <label class="form-check-label" for="safeYes">
                        <i class="fas fa-check-circle text-success me-2"></i>Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="safe_to_operate" id="safeNo" value="no">
                    <label class="form-check-label" for="safeNo">
                        <i class="fas fa-times-circle text-danger me-2"></i>No
                    </label>
                </div>
            </div>
        </div>

        <!-- Question C -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold text-danger">
                C. Does any part of the equipment have a defect which is or could become a danger to persons?
            </label>
            
            <div class="d-flex gap-4 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="equipment_defect" id="defectYes" value="yes">
                    <label class="form-check-label" for="defectYes">
                        <i class="fas fa-check-circle text-success me-2"></i>Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="equipment_defect" id="defectNo" value="no">
                    <label class="form-check-label" for="defectNo">
                        <i class="fas fa-times-circle text-danger me-2"></i>No
                    </label>
                </div>
            </div>

            <!-- Conditional defect details -->
            <div id="defectDetailsSection" class="conditional-question" style="display: none;">
                <div class="alert alert-light border-start border-warning border-4 ps-4">
                    <label class="form-label fw-semibold mb-3">
                        Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect:
                    </label>
                    <textarea class="form-control mb-3" name="defect_description" 
                              rows="4" placeholder="Identify and describe the defect..."></textarea>

                    <label class="form-label fw-semibold">
                        Is the above an existing or imminent danger to persons?
                    </label>
                    <textarea class="form-control mb-3" name="existing_danger" 
                              rows="2" placeholder="Yes"></textarea>

                    <label class="form-label fw-semibold">
                        Is the above a defect which is not yet, but could become a danger to persons?
                    </label>
                    <div class="input-group mb-3">
                        <textarea class="form-control" name="potential_danger" 
                                  rows="2" placeholder="Yes"></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <label class="form-label fw-semibold text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            If yes, state when:
                        </label>
                        <input type="date" class="form-control" name="defect_timeline">
                    </div>

                    <label class="form-label fw-semibold">
                        Particulars of any repair, renewal or alteration required to remedy the defect identified above:
                    </label>
                    <textarea class="form-control" name="repair_details" 
                              rows="4" placeholder="Details of required repairs..."></textarea>
                </div>
            </div>
        </div>

        <!-- Question D -->
        <div class="inspection-question">
            <label class="form-label fw-bold text-danger">
                D. Details of any tests carried out as part of the examination:
            </label>
            <textarea class="form-control mt-2" name="test_details" 
                      rows="6" placeholder="Enter details of any tests carried out as part of the examination..."></textarea>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle conditional questions for first examination
    const firstExamRadios = document.querySelectorAll('input[name="first_examination"]');
    const equipmentQuestion = document.getElementById('equipmentInstallationQuestion');

    firstExamRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'yes' && this.checked) {
                equipmentQuestion.style.display = 'block';
            } else {
                equipmentQuestion.style.display = 'none';
            }
        });
    });

    // Handle conditional questions for defects
    const defectRadios = document.querySelectorAll('input[name="equipment_defect"]');
    const defectSection = document.getElementById('defectDetailsSection');

    defectRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'yes' && this.checked) {
                defectSection.style.display = 'block';
            } else {
                defectSection.style.display = 'none';
            }
        });
    });
});
</script>

<style>
.inspection-question {
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.conditional-question {
    margin-top: 1rem;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.form-check-label {
    font-weight: 500;
}

.alert.border-start {
    border-left-width: 4px !important;
}
</style>
