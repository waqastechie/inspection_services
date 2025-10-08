{{-- Personnel Assignment Section --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Personnel Assignment
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Assign qualified personnel to this inspection. Ensure all assigned personnel have the required certifications and competencies.
                </p>

                {{-- Primary Inspector --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="primary_inspector" class="form-label">
                            <i class="fas fa-user-tie me-1"></i>Primary Inspector <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="primary_inspector" name="primary_inspector" required>
                            <option value="">Select Primary Inspector</option>
                            @if(isset($inspectors))
                                @foreach($inspectors as $inspector)
                                    <option value="{{ $inspector->id }}" 
                                            {{ (isset($inspection) && $inspection->inspector_id == $inspector->id) ? 'selected' : '' }}>
                                        {{ $inspector->name }} - {{ $inspector->certification_level ?? 'Level 2' }}
                                    </option>
                                @endforeach
                            @else
                                <option value="1">John Smith - Level 3</option>
                                <option value="2">Sarah Johnson - Level 2</option>
                                <option value="3">Mike Wilson - Level 2</option>
                                <option value="4">Emma Davis - Level 3</option>
                            @endif
                        </select>
                        <div class="form-text">Lead inspector responsible for the inspection</div>
                    </div>

                    <div class="col-md-6">
                        <label for="inspector_certification" class="form-label">
                            <i class="fas fa-certificate me-1"></i>Certification Number
                        </label>
                        <input type="text" class="form-control" id="inspector_certification" name="inspector_certification" 
                               value="{{ $inspection->inspector_certification ?? '' }}" 
                               placeholder="e.g., PCN-12345" readonly>
                        <div class="form-text">Auto-filled based on selected inspector</div>
                    </div>
                </div>

                {{-- Secondary Personnel --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-user-friends me-1"></i>Additional Personnel
                        </h6>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPersonnel()">
                            <i class="fas fa-plus me-1"></i>Add Personnel
                        </button>
                    </div>

                    <div id="personnelList">
                        {{-- Personnel rows will be populated by JavaScript --}}
                    </div>

                    {{-- No Additional Personnel Message --}}
                    <div id="noPersonnelMessage" class="alert alert-info text-center">
                        <i class="fas fa-user-plus fa-2x mb-2"></i>
                        <p class="mb-0">No additional personnel assigned.</p>
                        <small class="text-muted">Click "Add Personnel" to assign additional team members.</small>
                    </div>
                </div>

                {{-- Supervision Requirements --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="supervision_required" class="form-label">
                            <i class="fas fa-eye me-1"></i>Supervision Required
                        </label>
                        <select class="form-select" id="supervision_required" name="supervision_required">
                            <option value="no" {{ (isset($inspection) && $inspection->supervision_required == 'no') ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ (isset($inspection) && $inspection->supervision_required == 'yes') ? 'selected' : '' }}>Yes</option>
                            <option value="partial" {{ (isset($inspection) && $inspection->supervision_required == 'partial') ? 'selected' : '' }}>Partial</option>
                        </select>
                        <div class="form-text">Level of supervision required for this inspection</div>
                    </div>

                    <div class="col-md-6">
                        <label for="supervisor_name" class="form-label">
                            <i class="fas fa-user-shield me-1"></i>Supervisor Name
                        </label>
                        <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" 
                               value="{{ $inspection->supervisor_name ?? '' }}" 
                               placeholder="Enter supervisor name">
                        <div class="form-text">Name of supervising personnel (if applicable)</div>
                    </div>
                </div>

                {{-- Competency Requirements --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-clipboard-check me-1"></i>Competency Requirements
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="req_lifting_equipment" name="competency_requirements[]" value="lifting_equipment"
                                       {{ (isset($inspection) && str_contains($inspection->competency_requirements ?? '', 'lifting_equipment')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_lifting_equipment">
                                    Lifting Equipment Inspection
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="req_pressure_systems" name="competency_requirements[]" value="pressure_systems"
                                       {{ (isset($inspection) && str_contains($inspection->competency_requirements ?? '', 'pressure_systems')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_pressure_systems">
                                    Pressure Systems
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="req_ndt_methods" name="competency_requirements[]" value="ndt_methods"
                                       {{ (isset($inspection) && str_contains($inspection->competency_requirements ?? '', 'ndt_methods')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_ndt_methods">
                                    NDT Methods
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="req_electrical_systems" name="competency_requirements[]" value="electrical_systems"
                                       {{ (isset($inspection) && str_contains($inspection->competency_requirements ?? '', 'electrical_systems')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_electrical_systems">
                                    Electrical Systems
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="req_confined_spaces" name="competency_requirements[]" value="confined_spaces"
                                       {{ (isset($inspection) && str_contains($inspection->competency_requirements ?? '', 'confined_spaces')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_confined_spaces">
                                    Confined Spaces
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="req_working_at_height" name="competency_requirements[]" value="working_at_height"
                                       {{ (isset($inspection) && str_contains($inspection->competency_requirements ?? '', 'working_at_height')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_working_at_height">
                                    Working at Height
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Personnel Notes --}}
                <div class="mb-3">
                    <label for="personnel_notes" class="form-label">
                        <i class="fas fa-sticky-note me-1"></i>Personnel Notes
                    </label>
                    <textarea class="form-control" id="personnel_notes" name="personnel_notes" rows="3" 
                              placeholder="Additional notes about personnel assignments, special requirements, or qualifications...">{{ $inspection->personnel_notes ?? '' }}</textarea>
                    <div class="form-text">Any additional information about personnel assignments</div>
                </div>

                {{-- Hidden inputs for additional personnel --}}
                <div id="personnelInputsContainer">
                    {{-- Hidden inputs will be populated by JavaScript --}}
                </div>

                {{-- Existing Additional Personnel (for edit mode) --}}
                @if(isset($inspection) && $inspection->additional_personnel)
                    @php
                        $additionalPersonnel = is_string($inspection->additional_personnel) 
                            ? json_decode($inspection->additional_personnel, true) 
                            : $inspection->additional_personnel;
                    @endphp
                    @if(is_array($additionalPersonnel))
                        @foreach($additionalPersonnel as $index => $person)
                            <input type="hidden" name="additional_personnel[{{ $index }}][name]" value="{{ $person['name'] ?? '' }}">
                            <input type="hidden" name="additional_personnel[{{ $index }}][role]" value="{{ $person['role'] ?? '' }}">
                            <input type="hidden" name="additional_personnel[{{ $index }}][certification]" value="{{ $person['certification'] ?? '' }}">
                            <input type="hidden" name="additional_personnel[{{ $index }}][level]" value="{{ $person['level'] ?? '' }}">
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Personnel Management --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize personnel management
    if (typeof additionalPersonnel === 'undefined') {
        window.additionalPersonnel = [];
    }
    
    // Load existing additional personnel for edit mode
    @if(isset($inspection) && $inspection->additional_personnel)
        @php
            $additionalPersonnel = is_string($inspection->additional_personnel) 
                ? json_decode($inspection->additional_personnel, true) 
                : $inspection->additional_personnel;
        @endphp
        @if(is_array($additionalPersonnel))
            @foreach($additionalPersonnel as $person)
                additionalPersonnel.push({
                    id: Date.now() + Math.random(),
                    name: '{{ $person['name'] ?? '' }}',
                    role: '{{ $person['role'] ?? '' }}',
                    certification: '{{ $person['certification'] ?? '' }}',
                    level: '{{ $person['level'] ?? '' }}'
                });
            @endforeach
            updatePersonnelDisplay();
        @endif
    @endif
    
    // Primary inspector change handler
    document.getElementById('primary_inspector').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const certificationField = document.getElementById('inspector_certification');
        
        // Mock certification data - in real app, this would come from database
        const certifications = {
            '1': 'PCN-12345',
            '2': 'PCN-23456',
            '3': 'PCN-34567',
            '4': 'PCN-45678'
        };
        
        certificationField.value = certifications[this.value] || '';
    });
    
    // Supervision required change handler
    document.getElementById('supervision_required').addEventListener('change', function() {
        const supervisorField = document.getElementById('supervisor_name');
        if (this.value === 'no') {
            supervisorField.value = '';
            supervisorField.disabled = true;
        } else {
            supervisorField.disabled = false;
        }
    });
    
    // Initialize supervision field state
    const supervisionSelect = document.getElementById('supervision_required');
    if (supervisionSelect.value === 'no') {
        document.getElementById('supervisor_name').disabled = true;
    }
});

// Add personnel function
function addPersonnel() {
    const name = prompt('Enter personnel name:');
    if (!name) return;
    
    const role = prompt('Enter role (e.g., Assistant Inspector, Trainee):');
    if (!role) return;
    
    const certification = prompt('Enter certification number (optional):') || '';
    const level = prompt('Enter certification level (e.g., Level 1, Level 2):') || '';
    
    const person = {
        id: Date.now(),
        name: name.trim(),
        role: role.trim(),
        certification: certification.trim(),
        level: level.trim()
    };
    
    additionalPersonnel.push(person);
    updatePersonnelDisplay();
    
    if (typeof showAlert === 'function') {
        showAlert('Personnel added successfully!', 'success');
    }
}

// Update personnel display function
function updatePersonnelDisplay() {
    const noPersonnelMessage = document.getElementById('noPersonnelMessage');
    const personnelList = document.getElementById('personnelList');
    const personnelInputsContainer = document.getElementById('personnelInputsContainer');
    
    if (additionalPersonnel.length === 0) {
        if (noPersonnelMessage) noPersonnelMessage.style.display = 'block';
        if (personnelList) personnelList.innerHTML = '';
        if (personnelInputsContainer) personnelInputsContainer.innerHTML = '';
        return;
    }
    
    if (noPersonnelMessage) noPersonnelMessage.style.display = 'none';
    
    // Update personnel list display
    if (personnelList) {
        personnelList.innerHTML = additionalPersonnel.map((person, index) => `
            <div class="card mb-2">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong>${person.name}</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="badge bg-primary">${person.role}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">
                                ${person.certification ? person.certification : 'No certification'}
                                ${person.level ? ' (' + person.level + ')' : ''}
                            </small>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePersonnel(${index})" title="Remove">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // Update hidden inputs
    if (personnelInputsContainer) {
        personnelInputsContainer.innerHTML = additionalPersonnel.map((person, index) => `
            <input type="hidden" name="additional_personnel[${index}][name]" value="${person.name}">
            <input type="hidden" name="additional_personnel[${index}][role]" value="${person.role}">
            <input type="hidden" name="additional_personnel[${index}][certification]" value="${person.certification}">
            <input type="hidden" name="additional_personnel[${index}][level]" value="${person.level}">
        `).join('');
    }
}

// Remove personnel function
function removePersonnel(index) {
    if (confirm('Are you sure you want to remove this personnel assignment?')) {
        additionalPersonnel.splice(index, 1);
        updatePersonnelDisplay();
        if (typeof showAlert === 'function') {
            showAlert('Personnel removed successfully', 'info');
        }
    }
}
</script>