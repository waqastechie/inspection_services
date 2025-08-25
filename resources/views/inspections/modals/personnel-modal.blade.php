<!-- Personnel Assignment Modal -->
<div class="modal fade" id="personnelModal" tabindex="-1" aria-labelledby="personnelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="personnelModalLabel">
                    <i class="fas fa-user-plus me-2"></i>
                    Add Personnel Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="personnelForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="personnelName" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="personnelName" required 
                                   placeholder="Enter full name">
                        </div>
                        <div class="col-md-6">
                            <label for="personnelRoleModal" class="form-label">Role *</label>
                            <select class="form-select" id="personnelRoleModal" required>
                                <option value="">Select role</option>
                                <option value="Crane Operator">Crane Operator</option>
                                <option value="Signal Person">Signal Person</option>
                                <option value="Rigger">Rigger</option>
                                <option value="Lift Supervisor">Lift Supervisor</option>
                                <option value="Safety Officer">Safety Officer</option>
                                <option value="Banksman">Banksman</option>
                                <option value="Slinger">Slinger</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="personnelCertification" class="form-label">Certification Type</label>
                            <input type="text" class="form-control" id="personnelCertification" 
                                   placeholder="e.g., CPCS, CITB, NCCCO">
                        </div>
                        <div class="col-md-6">
                            <label for="personnelCertNumber" class="form-label">Certificate Number</label>
                            <input type="text" class="form-control" id="personnelCertNumber" 
                                   placeholder="Certification number">
                        </div>
                        <div class="col-md-6">
                            <label for="personnelCertExpiry" class="form-label">Certificate Expiry</label>
                            <input type="date" class="form-control" id="personnelCertExpiry">
                        </div>
                        <div class="col-md-6">
                            <label for="personnelExperience" class="form-label">Years of Experience</label>
                            <input type="number" class="form-control" id="personnelExperience" 
                                   min="0" max="50" placeholder="Years">
                        </div>
                        <div class="col-12">
                            <label for="personnelNotes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="personnelNotesModal" rows="2" 
                                      placeholder="Any additional information about this person"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addPersonnel()">
                    <i class="fas fa-plus me-2"></i>Add Personnel
                </button>
            </div>
        </div>
    </div>
</div>
