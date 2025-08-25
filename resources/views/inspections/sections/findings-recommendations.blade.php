<!-- Findings and Recommendations Section -->
<section class="form-section" id="section-findings">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-search"></i>
            Findings and Recommendations
        </h2>
        <p class="section-description">
            Document inspection findings, defects identified, and recommendations for corrective actions.
        </p>
    </div>

    <div class="section-content">
        <div class="row g-4">
            <!-- Inspection Result -->
            <div class="col-12">
                <label for="inspectionFindings" class="form-label">
                    <i class="fas fa-clipboard-check me-2"></i>Inspection Findings *
                </label>
                <textarea class="form-control @error('inspection_findings') is-invalid @enderror" 
                          id="inspectionFindings" name="inspection_findings" 
                          rows="6" required placeholder="Provide detailed findings from the inspection...">{{ old('inspection_findings', 'Following a thorough inspection of the lifting equipment and associated rigging gear, the following observations were made:

POSITIVE FINDINGS:
- All equipment appears to be in good working condition
- Safety systems are functioning correctly
- Documentation is current and complete
- Personnel are appropriately qualified

AREAS OF CONCERN:
None identified during this inspection.

OVERALL ASSESSMENT:
No defect was observed during inspection.') }}</textarea>
                @error('inspection_findings')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Provide comprehensive details of all inspection findings</small>
            </div>

            <!-- Defects Identified -->
            <div class="col-md-6">
                <label for="defectsIdentified" class="form-label">
                    <i class="fas fa-exclamation-triangle me-2"></i>Defects Identified
                </label>
                <select class="form-select" id="defectsIdentified" name="defects_identified">
                    <option value="None" {{ old('defects_identified', 'None') == 'None' ? 'selected' : '' }}>None</option>
                    <option value="Minor" {{ old('defects_identified') == 'Minor' ? 'selected' : '' }}>Minor Defects</option>
                    <option value="Major" {{ old('defects_identified') == 'Major' ? 'selected' : '' }}>Major Defects</option>
                    <option value="Critical" {{ old('defects_identified') == 'Critical' ? 'selected' : '' }}>Critical Defects</option>
                </select>
                <small class="form-text text-muted">Severity level of any defects found</small>
            </div>

            <!-- Immediate Actions Required -->
            <div class="col-md-6">
                <label for="immediateActions" class="form-label">
                    <i class="fas fa-bolt me-2"></i>Immediate Actions Required
                </label>
                <select class="form-select" id="immediateActions" name="immediate_actions">
                    <option value="None" {{ old('immediate_actions', 'None') == 'None' ? 'selected' : '' }}>None Required</option>
                    <option value="Minor Repair" {{ old('immediate_actions') == 'Minor Repair' ? 'selected' : '' }}>Minor Repair</option>
                    <option value="Major Repair" {{ old('immediate_actions') == 'Major Repair' ? 'selected' : '' }}>Major Repair</option>
                    <option value="Remove from Service" {{ old('immediate_actions') == 'Remove from Service' ? 'selected' : '' }}>Remove from Service</option>
                    <option value="Re-inspection Required" {{ old('immediate_actions') == 'Re-inspection Required' ? 'selected' : '' }}>Re-inspection Required</option>
                </select>
                <small class="form-text text-muted">Any immediate actions that must be taken</small>
            </div>

            <!-- Recommendations -->
            <div class="col-12">
                <label for="recommendations" class="form-label">
                    <i class="fas fa-lightbulb me-2"></i>Recommendations
                </label>
                <textarea class="form-control" id="recommendations" name="recommendations" 
                          rows="4" placeholder="Provide recommendations for maintenance, repairs, or improvements...">{{ old('recommendations', '1. Continue regular maintenance schedule as per manufacturer recommendations
2. Conduct next thorough examination within required timeframe
3. Ensure all personnel maintain current certifications
4. Monitor equipment condition during regular pre-use inspections
5. Maintain all documentation in accessible location') }}</textarea>
                <small class="form-text text-muted">Recommendations for ongoing maintenance and operation</small>
            </div>

            <!-- Follow-up Actions -->
            <div class="col-12">
                <label for="followUpActions" class="form-label">
                    <i class="fas fa-tasks me-2"></i>Follow-up Actions Required
                </label>
                <textarea class="form-control" id="followUpActions" name="follow_up_actions" 
                          rows="3" placeholder="Specify any follow-up actions, timelines, and responsible parties...">{{ old('follow_up_actions', 'No immediate follow-up actions required. Continue with scheduled maintenance program.') }}</textarea>
                <small class="form-text text-muted">Any follow-up actions required with timelines</small>
            </div>

            <!-- Risk Assessment -->
            <div class="col-md-6">
                <label for="riskLevel" class="form-label">
                    <i class="fas fa-exclamation-circle me-2"></i>Overall Risk Level
                </label>
                <select class="form-select" id="riskLevel" name="risk_level">
                    <option value="Low" {{ old('risk_level', 'Low') == 'Low' ? 'selected' : '' }}>Low Risk</option>
                    <option value="Medium" {{ old('risk_level') == 'Medium' ? 'selected' : '' }}>Medium Risk</option>
                    <option value="High" {{ old('risk_level') == 'High' ? 'selected' : '' }}>High Risk</option>
                    <option value="Critical" {{ old('risk_level') == 'Critical' ? 'selected' : '' }}>Critical Risk</option>
                </select>
                <small class="form-text text-muted">Overall risk assessment for continued operation</small>
            </div>

            <!-- Approval for Use -->
            <div class="col-md-6">
                <label for="approvalStatus" class="form-label">
                    <i class="fas fa-check-circle me-2"></i>Approval Status
                </label>
                <select class="form-select" id="approvalStatus" name="approval_status">
                    <option value="Approved for Use" {{ old('approval_status', 'Approved for Use') == 'Approved for Use' ? 'selected' : '' }}>Approved for Use</option>
                    <option value="Conditional Approval" {{ old('approval_status') == 'Conditional Approval' ? 'selected' : '' }}>Conditional Approval</option>
                    <option value="Not Approved" {{ old('approval_status') == 'Not Approved' ? 'selected' : '' }}>Not Approved</option>
                    <option value="Pending Further Review" {{ old('approval_status') == 'Pending Further Review' ? 'selected' : '' }}>Pending Further Review</option>
                </select>
                <small class="form-text text-muted">Final approval status for equipment use</small>
            </div>

            <!-- Inspector Signature -->
            <div class="col-md-6">
                <label for="inspectorSignature" class="form-label">
                    <i class="fas fa-signature me-2"></i>Inspector Digital Signature
                </label>
                <select class="form-select" id="inspectorSignature" name="inspector_signature">
                    <option value="">Select Inspector</option>
                    <option value="John Smith - Certified Inspector #12345">John Smith - Certified Inspector #12345</option>
                    <option value="Sarah Johnson - Senior Inspector #67890">Sarah Johnson - Senior Inspector #67890</option>
                    <option value="Michael Brown - Lead Inspector #54321">Michael Brown - Lead Inspector #54321</option>
                    <option value="Custom">Enter Custom Signature</option>
                </select>
                <small class="form-text text-muted">Digital signature acknowledging inspection completion</small>
            </div>

            <!-- Date of Signature -->
            <div class="col-md-6">
                <label for="signatureDate" class="form-label">
                    <i class="fas fa-calendar-check me-2"></i>Signature Date
                </label>
                <input type="date" class="form-control" id="signatureDate" name="signature_date" 
                       value="{{ old('signature_date', date('Y-m-d')) }}">
                <small class="form-text text-muted">Date when inspection was signed off</small>
            </div>

            <!-- Additional Comments -->
            <div class="col-12">
                <label for="additionalComments" class="form-label">
                    <i class="fas fa-comment me-2"></i>Additional Comments
                </label>
                <textarea class="form-control @error('additional_comments') is-invalid @enderror" 
                          id="additionalComments" name="additional_comments" 
                          rows="4" placeholder="Any additional observations, conditions, or recommendations...">{{ old('additional_comments', 'No defect was observed during inspection.') }}</textarea>
                @error('additional_comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Describe any additional findings, conditions, or recommendations.</small>
            </div>

            <!-- Certification Statement -->
            <div class="col-12">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-certificate me-2"></i>Certification Statement
                    </h6>
                    <p class="mb-2">
                        I certify that this inspection has been carried out in accordance with the relevant standards and regulations,
                        and that the equipment has been thoroughly examined to the extent necessary to determine its condition and suitability for continued use.
                    </p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="certificationAccepted" name="certification_accepted" value="1" required>
                        <label class="form-check-label" for="certificationAccepted">
                            <strong>I accept responsibility for this inspection and certification</strong>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
