{{-- Comments Section --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-comments me-2"></i>Comments & Observations
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <p class="text-muted mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Record detailed observations, findings, and any additional comments about the inspection. 
                    Include any deviations from standards, special conditions, or noteworthy observations.
                </p>

                {{-- General Comments --}}
                <div class="mb-4">
                    <label for="general_comments" class="form-label">
                        <i class="fas fa-comment me-1"></i>General Comments
                    </label>
                    <textarea class="form-control" id="general_comments" name="general_comments" rows="4" 
                              placeholder="Enter general observations, findings, and comments about the inspection...">{{ $inspection->general_comments ?? '' }}</textarea>
                    <div class="form-text">Overall observations and general comments about the inspection</div>
                </div>

                {{-- Technical Observations --}}
                <div class="mb-4">
                    <label for="technical_observations" class="form-label">
                        <i class="fas fa-cogs me-1"></i>Technical Observations
                    </label>
                    <textarea class="form-control" id="technical_observations" name="technical_observations" rows="4" 
                              placeholder="Record technical findings, measurements, test results, and specific observations...">{{ $inspection->technical_observations ?? '' }}</textarea>
                    <div class="form-text">Detailed technical findings and specific observations</div>
                </div>

                {{-- Safety Concerns --}}
                <div class="mb-4">
                    <label for="safety_concerns" class="form-label">
                        <i class="fas fa-exclamation-triangle me-1"></i>Safety Concerns
                    </label>
                    <textarea class="form-control" id="safety_concerns" name="safety_concerns" rows="3" 
                              placeholder="Document any safety concerns, hazards, or risks identified during inspection...">{{ $inspection->safety_concerns ?? '' }}</textarea>
                    <div class="form-text">Any safety-related concerns or hazards identified</div>
                </div>

                {{-- Recommendations --}}
                <div class="mb-4">
                    <label for="recommendations" class="form-label">
                        <i class="fas fa-lightbulb me-1"></i>Recommendations
                    </label>
                    <textarea class="form-control" id="recommendations" name="recommendations" rows="4" 
                              placeholder="Provide recommendations for maintenance, repairs, further inspections, or improvements...">{{ $inspection->recommendations ?? '' }}</textarea>
                    <div class="form-text">Recommendations for maintenance, repairs, or improvements</div>
                </div>

                {{-- Limitations --}}
                <div class="mb-4">
                    <label for="inspection_limitations" class="form-label">
                        <i class="fas fa-ban me-1"></i>Inspection Limitations
                    </label>
                    <textarea class="form-control" id="inspection_limitations" name="inspection_limitations" rows="3" 
                              placeholder="Document any limitations that affected the inspection scope or methodology...">{{ $inspection->inspection_limitations ?? '' }}</textarea>
                    <div class="form-text">Any limitations that affected the inspection</div>
                </div>

                {{-- Follow-up Actions --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-tasks me-1"></i>Follow-up Actions Required
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="followup_immediate_repair" name="followup_actions[]" value="immediate_repair"
                                       {{ (isset($inspection) && str_contains($inspection->followup_actions ?? '', 'immediate_repair')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="followup_immediate_repair">
                                    <i class="fas fa-tools me-1"></i>Immediate Repair Required
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="followup_reinspection" name="followup_actions[]" value="reinspection"
                                       {{ (isset($inspection) && str_contains($inspection->followup_actions ?? '', 'reinspection')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="followup_reinspection">
                                    <i class="fas fa-redo me-1"></i>Re-inspection Required
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="followup_maintenance" name="followup_actions[]" value="maintenance"
                                       {{ (isset($inspection) && str_contains($inspection->followup_actions ?? '', 'maintenance')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="followup_maintenance">
                                    <i class="fas fa-wrench me-1"></i>Scheduled Maintenance
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="followup_monitoring" name="followup_actions[]" value="monitoring"
                                       {{ (isset($inspection) && str_contains($inspection->followup_actions ?? '', 'monitoring')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="followup_monitoring">
                                    <i class="fas fa-eye me-1"></i>Ongoing Monitoring
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="followup_replacement" name="followup_actions[]" value="replacement"
                                       {{ (isset($inspection) && str_contains($inspection->followup_actions ?? '', 'replacement')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="followup_replacement">
                                    <i class="fas fa-exchange-alt me-1"></i>Component Replacement
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="followup_documentation" name="followup_actions[]" value="documentation"
                                       {{ (isset($inspection) && str_contains($inspection->followup_actions ?? '', 'documentation')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="followup_documentation">
                                    <i class="fas fa-file-alt me-1"></i>Additional Documentation
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Follow-up Details --}}
                <div class="mb-4">
                    <label for="followup_details" class="form-label">
                        <i class="fas fa-clipboard-list me-1"></i>Follow-up Details
                    </label>
                    <textarea class="form-control" id="followup_details" name="followup_details" rows="3" 
                              placeholder="Provide specific details about required follow-up actions, timelines, and responsibilities...">{{ $inspection->followup_details ?? '' }}</textarea>
                    <div class="form-text">Specific details about follow-up actions and timelines</div>
                </div>

                {{-- Priority Level --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="priority_level" class="form-label">
                            <i class="fas fa-flag me-1"></i>Priority Level
                        </label>
                        <select class="form-select" id="priority_level" name="priority_level">
                            <option value="">Select Priority</option>
                            <option value="low" {{ (isset($inspection) && $inspection->priority_level == 'low') ? 'selected' : '' }}>
                                <i class="fas fa-flag text-success"></i> Low Priority
                            </option>
                            <option value="medium" {{ (isset($inspection) && $inspection->priority_level == 'medium') ? 'selected' : '' }}>
                                <i class="fas fa-flag text-warning"></i> Medium Priority
                            </option>
                            <option value="high" {{ (isset($inspection) && $inspection->priority_level == 'high') ? 'selected' : '' }}>
                                <i class="fas fa-flag text-danger"></i> High Priority
                            </option>
                            <option value="critical" {{ (isset($inspection) && $inspection->priority_level == 'critical') ? 'selected' : '' }}>
                                <i class="fas fa-flag text-danger"></i> Critical
                            </option>
                        </select>
                        <div class="form-text">Priority level for any required actions</div>
                    </div>

                    <div class="col-md-6">
                        <label for="target_completion_date" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>Target Completion Date
                        </label>
                        <input type="date" class="form-control" id="target_completion_date" name="target_completion_date" 
                               value="{{ $inspection->target_completion_date ?? '' }}">
                        <div class="form-text">Target date for completing follow-up actions</div>
                    </div>
                </div>

                {{-- Additional Notes --}}
                <div class="mb-4">
                    <label for="additional_notes" class="form-label">
                        <i class="fas fa-sticky-note me-1"></i>Additional Notes
                    </label>
                    <textarea class="form-control" id="additional_notes" name="additional_notes" rows="3" 
                              placeholder="Any additional notes, observations, or information not covered above...">{{ $inspection->additional_notes ?? '' }}</textarea>
                    <div class="form-text">Any other relevant information or observations</div>
                </div>

                {{-- Inspector Signature Section --}}
                <div class="row">
                    <div class="col-md-6">
                        <label for="inspector_signature" class="form-label">
                            <i class="fas fa-signature me-1"></i>Inspector Comments Verification
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inspector_signature" name="inspector_signature" value="1"
                                   {{ (isset($inspection) && $inspection->inspector_signature) ? 'checked' : '' }}>
                            <label class="form-check-label" for="inspector_signature">
                                I confirm that all comments and observations recorded above are accurate and complete
                            </label>
                        </div>
                        <div class="form-text">Inspector verification of comments accuracy</div>
                    </div>

                    <div class="col-md-6">
                        <label for="comments_date" class="form-label">
                            <i class="fas fa-calendar me-1"></i>Comments Date
                        </label>
                        <input type="datetime-local" class="form-control" id="comments_date" name="comments_date" 
                               value="{{ isset($inspection->comments_date) ? date('Y-m-d\TH:i', strtotime($inspection->comments_date)) : date('Y-m-d\TH:i') }}">
                        <div class="form-text">Date and time when comments were recorded</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Comments Management --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save functionality for comments (optional)
    const commentFields = [
        'general_comments',
        'technical_observations', 
        'safety_concerns',
        'recommendations',
        'inspection_limitations',
        'followup_details',
        'additional_notes'
    ];
    
    // Add character counters to text areas
    commentFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            addCharacterCounter(field);
        }
    });
    
    // Priority level change handler
    document.getElementById('priority_level').addEventListener('change', function() {
        const targetDateField = document.getElementById('target_completion_date');
        const today = new Date();
        
        // Auto-suggest target dates based on priority
        switch(this.value) {
            case 'critical':
                // Suggest 1 day for critical
                today.setDate(today.getDate() + 1);
                break;
            case 'high':
                // Suggest 1 week for high priority
                today.setDate(today.getDate() + 7);
                break;
            case 'medium':
                // Suggest 1 month for medium priority
                today.setMonth(today.getMonth() + 1);
                break;
            case 'low':
                // Suggest 3 months for low priority
                today.setMonth(today.getMonth() + 3);
                break;
            default:
                return;
        }
        
        if (!targetDateField.value) {
            targetDateField.value = today.toISOString().split('T')[0];
        }
    });
    
    // Follow-up actions change handler
    const followupCheckboxes = document.querySelectorAll('input[name="followup_actions[]"]');
    followupCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const followupDetails = document.getElementById('followup_details');
            const priorityLevel = document.getElementById('priority_level');
            
            // Auto-suggest priority based on selected actions
            if (this.checked) {
                if (this.value === 'immediate_repair' && !priorityLevel.value) {
                    priorityLevel.value = 'high';
                    priorityLevel.dispatchEvent(new Event('change'));
                }
                
                // Add template text to follow-up details if empty
                if (!followupDetails.value.trim()) {
                    const actionTemplates = {
                        'immediate_repair': 'Immediate repair required for safety compliance.',
                        'reinspection': 'Re-inspection required after corrective actions.',
                        'maintenance': 'Schedule routine maintenance as per manufacturer recommendations.',
                        'monitoring': 'Implement ongoing monitoring program.',
                        'replacement': 'Component replacement required due to wear/damage.',
                        'documentation': 'Additional documentation required for compliance.'
                    };
                    
                    if (actionTemplates[this.value]) {
                        followupDetails.value = actionTemplates[this.value];
                    }
                }
            }
        });
    });
});

// Add character counter to text areas
function addCharacterCounter(textarea) {
    const maxLength = 2000; // Set reasonable limit
    const counter = document.createElement('div');
    counter.className = 'form-text text-end';
    counter.style.fontSize = '0.8em';
    
    function updateCounter() {
        const remaining = maxLength - textarea.value.length;
        counter.textContent = `${textarea.value.length}/${maxLength} characters`;
        
        if (remaining < 100) {
            counter.className = 'form-text text-end text-warning';
        } else if (remaining < 0) {
            counter.className = 'form-text text-end text-danger';
        } else {
            counter.className = 'form-text text-end text-muted';
        }
    }
    
    textarea.addEventListener('input', updateCounter);
    textarea.parentNode.appendChild(counter);
    updateCounter();
}

// Validate form before submission
function validateComments() {
    const requiredActions = document.querySelectorAll('input[name="followup_actions[]"]:checked');
    const followupDetails = document.getElementById('followup_details');
    const priorityLevel = document.getElementById('priority_level');
    
    if (requiredActions.length > 0) {
        if (!followupDetails.value.trim()) {
            alert('Please provide details for the selected follow-up actions.');
            followupDetails.focus();
            return false;
        }
        
        if (!priorityLevel.value) {
            alert('Please select a priority level for the follow-up actions.');
            priorityLevel.focus();
            return false;
        }
    }
    
    return true;
}

// Auto-expand text areas as user types
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>