<!-- Lifting Examination Section -->
<section class="form-section" id="section-lifting-examination">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-weight-hanging me-3"></i>
                    Lifting
                </h2>
                <p class="section-description">
                    Lifting equipment examination and safety assessment
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

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-check me-2"></i>Lifting Equipment Examination
                </h5>
            </div>
            <div class="card-body">
                <!-- Question A -->
                <div class="inspection-question mb-4">
                    <div class="card border-primary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-primary fw-bold">
                                A. Is this the first examination after installation or assembly at a new site or location?
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="first_examination" id="firstExamYes" value="yes" onchange="handleFirstExamination(this)" {{ old('first_examination', $inspection?->liftingExamination?->first_examination ?? '') === 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-success" for="firstExamYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="first_examination" id="firstExamNo" value="no" onchange="handleFirstExamination(this)" {{ old('first_examination', $inspection?->liftingExamination?->first_examination ?? '') === 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-danger" for="firstExamNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="first_examination_notes" rows="2" placeholder="Additional notes or comments...">{{ old('first_examination_notes', $inspection?->liftingExamination?->first_examination_notes ?? '') }}</textarea>
                                </div>
                            </div>

                            <!-- Conditional Questions for A -->
                            <div id="firstExamFollowUp" class="mt-4" style="{{ old('first_examination', $inspection?->liftingExamination?->first_examination ?? '') === 'yes' ? '' : 'display: none;' }}">
                                <div class="row">
                                    <!-- If Yes Section -->
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded bg-success bg-opacity-10">
                                            <h6 class="text-success fw-bold mb-3">If the answer to the above is Yes:</h6>
                                            <label class="form-label fw-semibold">Has the equipment been installed correctly?</label>
                                            <div class="d-flex gap-3 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="equipment_installed_correctly" value="yes" {{ old('equipment_installed_correctly', $inspection?->liftingExamination?->equipment_installed_correctly ?? '') === 'yes' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="equipment_installed_correctly" value="no" {{ old('equipment_installed_correctly', $inspection?->liftingExamination?->equipment_installed_correctly ?? '') === 'no' ? 'checked' : '' }}>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                            <textarea class="form-control" name="equipment_installation_details" rows="2" placeholder="Installation details...">{{ old('equipment_installation_details', $inspection?->liftingExamination?->equipment_installation_details ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <!-- If No Section -->
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded bg-danger bg-opacity-10">
                                            <h6 class="text-danger fw-bold mb-3">If No:</h6>
                                            <label class="form-label fw-semibold">Was the examination carried out:</label>
                                            <textarea class="form-control" name="examination_carried_out" rows="3" placeholder="Details of examination carried out...">{{ old('examination_carried_out', $inspection?->liftingExamination?->examination_carried_out ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question B -->
                <div class="inspection-question mb-4">
                    <div class="card border-warning">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-warning fw-bold">
                                B. Is it the case that the equipment would be safe to operate?
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="safe_to_operate" value="yes" {{ old('safe_to_operate', $inspection?->liftingExamination?->safe_to_operate ?? '') === 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-success">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="safe_to_operate" value="no" {{ old('safe_to_operate', $inspection?->liftingExamination?->safe_to_operate ?? '') === 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-danger">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="safe_to_operate_notes" rows="2" placeholder="Safety assessment details and notes...">{{ old('safe_to_operate_notes', $inspection?->liftingExamination?->safe_to_operate_notes ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question C -->
                <div class="inspection-question mb-4">
                    <div class="card border-danger">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-danger fw-bold">
                                C. Does any part of the equipment have a defect which is or could become a danger to persons?
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="equipment_defect" id="defectYes" value="yes" onchange="handleEquipmentDefect(this)" {{ old('equipment_defect', $inspection?->liftingExamination?->equipment_defect ?? '') === 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-danger" for="defectYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="equipment_defect" id="defectNo" value="no" onchange="handleEquipmentDefect(this)" {{ old('equipment_defect', $inspection?->liftingExamination?->equipment_defect ?? '') === 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-success" for="defectNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="equipment_defect_notes" rows="2" placeholder="Defect assessment notes...">{{ old('equipment_defect_notes', $inspection?->liftingExamination?->equipment_defect_notes ?? '') }}</textarea>
                                </div>
                            </div>

                            <!-- Conditional Defect Details (always visible; applies if 'Yes') -->
                            <div id="defectDetails" class="mt-4">
                                <div class="alert alert-danger">
                                    <h6 class="text-danger fw-bold mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>If Yes: Defect Details Required
                                    </h6>
                                    
                                    <!-- Defect Identification -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect:
                                        </label>
                                        <textarea class="form-control" name="defect_description" rows="3" placeholder="Identify the part and describe the defect in detail...">{{ old('defect_description', $inspection?->liftingExamination?->defect_description ?? '') }}</textarea>
                                    </div>

                                    <div class="row">
                                        <!-- Existing/Imminent Danger -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-danger">
                                                Is the above an existing or imminent danger to persons?
                                            </label>
                                            <textarea class="form-control" name="existing_danger" rows="3" placeholder="Describe existing or imminent danger...">{{ old('existing_danger', $inspection?->liftingExamination?->existing_danger ?? '') }}</textarea>
                                        </div>
                                        
                                        <!-- Potential Future Danger -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-warning">
                                                Is the above a defect which is not yet, but could become a danger to persons?
                                            </label>
                                            <textarea class="form-control mb-2" name="potential_danger" rows="2" placeholder="Describe potential future danger...">{{ old('potential_danger', $inspection?->liftingExamination?->potential_danger ?? '') }}</textarea>
                                            <input type="text" class="form-control" name="defect_timeline" placeholder="If yes, state when..." value="{{ old('defect_timeline', $inspection?->liftingExamination?->defect_timeline ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- Repair Requirements -->
                                    <div class="mt-3">
                                        <label class="form-label fw-semibold">
                                            Particulars of any repair, renewal or alteration required to remedy the defect identified above:
                                        </label>
                                        <textarea class="form-control" name="repair_details" rows="4" placeholder="Detail the repairs, renewals, or alterations required to remedy the defect...">{{ old('repair_details', $inspection?->liftingExamination?->repair_details ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question D -->
                <div class="inspection-question mb-4">
                    <div class="card border-info">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-info fw-bold">
                                D. Details of any tests carried out as part of the examination:
                            </h6>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="test_details" rows="6" placeholder="Inspected in accordance with DNV 2.7-1&#10;Tilt test (empty & full) @ 30°&#10;Tare weight verification&#10;4 x lifting points load test : 2160 x 2.5=5400 Kg&#10;2 x lifting points load test : 2160 x 1.5=3240 Kg&#10;Drop test height: 50mm">{{ old('test_details', $inspection?->liftingExamination?->test_details ?? '') }}</textarea>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>

<script>
console.log('🏗️ Lifting examination section loaded');

// Make functions global to ensure they're accessible
window.handleFirstExamination = function(element) {
    console.log('🔄 Question A handler called:', element.value, element.checked);
    const followUpSection = document.getElementById('firstExamFollowUp');
    console.log('📍 Found followUpSection:', followUpSection);
    
    if (element.value === 'yes' && element.checked) {
        if (followUpSection) {
            followUpSection.style.display = 'block';
            followUpSection.style.visibility = 'visible';
            followUpSection.style.opacity = '1';
            followUpSection.classList.add('fade-in');
            console.log('✅ Showing Question A follow-up');
        } else {
            console.error('❌ firstExamFollowUp element not found!');
        }
    } else {
        if (followUpSection) {
            followUpSection.style.display = 'none';
            followUpSection.classList.remove('fade-in');
            console.log('❌ Hiding Question A follow-up');
        }
    }
};

// Handle Question C conditional display with enhanced debugging
window.handleEquipmentDefect = function(element) {
    console.log('🔄 Question C handler called:', element?.value, element?.checked);
    const defectDetailsSection = document.getElementById('defectDetails');
    console.log('📍 Found defectDetails:', defectDetailsSection);
    // Always show the follow-up section; it applies when the answer is Yes
    if (defectDetailsSection) {
        defectDetailsSection.style.display = 'block';
        defectDetailsSection.style.visibility = 'visible';
        defectDetailsSection.style.opacity = '1';
        defectDetailsSection.removeAttribute('hidden');
        defectDetailsSection.classList.add('fade-in');
        defectDetailsSection.classList.remove('d-none');
        defectDetailsSection.offsetHeight; // force layout recalculation
        console.log('✅ Question C follow-up is set to always visible');
    } else {
        console.error('❌ defectDetails element not found!');
    }
};

// Initialize on page load with enhanced debugging
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Initializing lifting examination handlers');
    
    // Debug: Check if elements exist
    const defectRadios = document.querySelectorAll('input[name="equipment_defect"]');
    const defectSection = document.getElementById('defectDetails');
    const firstExamRadios = document.querySelectorAll('input[name="first_examination"]');
    const firstExamSection = document.getElementById('firstExamFollowUp');
    
    console.log('🔍 Debug info:', {
        defectRadios: defectRadios.length,
        defectSection: !!defectSection,
        firstExamRadios: firstExamRadios.length,
        firstExamSection: !!firstExamSection
    });
    
    // Add event listeners as backup
    defectRadios.forEach(radio => {
        const handler = function() {
            console.log('📻 Radio event:', this.value, this.checked);
            window.handleEquipmentDefect(this);
        };
        radio.addEventListener('change', handler);
        radio.addEventListener('click', handler);
    });

    // Event delegation fallback for robustness
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'equipment_defect') {
            window.handleEquipmentDefect(e.target);
        }
    });
    
    firstExamRadios.forEach(radio => {
        const handler = function() {
            console.log('📻 Radio event:', this.value, this.checked);
            window.handleFirstExamination(this);
        };
        radio.addEventListener('change', handler);
        radio.addEventListener('click', handler);
    });
    
    // Check for pre-selected values
    const selectedFirstExam = document.querySelector('input[name="first_examination"]:checked');
    if (selectedFirstExam && selectedFirstExam.value === 'yes') {
        console.log('🚀 Initializing Question A as Yes');
        window.handleFirstExamination(selectedFirstExam);
    }
    
    const selectedDefect = document.querySelector('input[name="equipment_defect"]:checked');
    console.log('🚀 Initializing Question C; follow-up always visible. Current selection:', selectedDefect?.value);
    window.handleEquipmentDefect(selectedDefect);
});

// Test functions for manual debugging
window.testDefectYes = function() {
    const yesRadio = document.getElementById('defectYes');
    if (yesRadio) {
        yesRadio.checked = true;
        window.handleEquipmentDefect(yesRadio);
        console.log('🧪 Test: Set defect to Yes and called handler');
    } else {
        console.error('❌ defectYes radio not found');
    }
};

window.testDefectNo = function() {
    const noRadio = document.getElementById('defectNo');
    if (noRadio) {
        noRadio.checked = true;
        window.handleEquipmentDefect(noRadio);
        console.log('🧪 Test: Set defect to No and called handler');
    } else {
        console.error('❌ defectNo radio not found');
    }
};

console.log('💡 Available test functions: testDefectYes(), testDefectNo()');
</script>

<style>
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.inspection-question .card {
    transition: all 0.2s ease;
}

.inspection-question .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.alert {
    border-left: 4px solid;
}

.alert-danger {
    border-left-color: #dc3545;
}
</style>
