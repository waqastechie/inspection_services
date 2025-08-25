<!-- Personnel Modal -->
<div class="modal fade" id="personnelModal" tabindex="-1" aria-labelledby="personnelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="personnelModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add Personnel Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="personnelForm">
                    <div class="row g-3">
                        <!-- Personnel Selection Dropdown -->
                        <div class="col-12">
                            <label for="personnel_id" class="form-label fw-bold">Select Personnel <span class="text-muted">(Optional)</span></label>
                            <select class="form-select searchable-dropdown" id="personnel_id" name="personnel_id">
                                <option value="">Choose from database or enter manually below...</option>
                            </select>
                            <div class="form-text">Search and select from existing personnel, or leave blank to enter manually</div>
                        </div>

                        <!-- Personnel Information (Auto-populate or Manual Entry) -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-secondary text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Personnel Information</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="personnel_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="personnel_name" name="personnel_name" required placeholder="Enter or auto-populated from selection">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="personnel_position" class="form-label">Position</label>
                                            <input type="text" class="form-control" id="personnel_position" name="personnel_position" placeholder="Enter or auto-populated from selection">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="personnel_department" class="form-label">Department</label>
                                            <input type="text" class="form-control" id="personnel_department" name="personnel_department" placeholder="Enter or auto-populated from selection">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="personnel_contact" class="form-label">Contact Information</label>
                                            <input type="text" class="form-control" id="personnel_contact" name="personnel_contact" placeholder="Enter or auto-populated from selection">
                                        </div>
                                        <div class="col-12">
                                            <label for="personnel_qualification" class="form-label">Qualifications</label>
                                            <textarea class="form-control" id="personnel_qualification" name="personnel_qualification" rows="2" placeholder="Enter or auto-populated from selection"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="personnel_certifications" class="form-label">Certifications</label>
                                            <textarea class="form-control" id="personnel_certifications" name="personnel_certifications" rows="2" placeholder="Enter or auto-populated from selection"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Details (User Input) -->
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Assignment Details</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="personnel_role" class="form-label fw-bold">Role for this Inspection <span class="text-danger">*</span></label>
                                            <select class="form-select" id="personnel_role" name="personnel_role" required>
                                                <option value="">Select role...</option>
                                                <option value="lead_inspector">Lead Inspector</option>
                                                <option value="inspector">Inspector</option>
                                                <option value="assistant_inspector">Assistant Inspector</option>
                                                <option value="supervisor">Supervisor</option>
                                                <option value="client_representative">Client Representative</option>
                                                <option value="safety_officer">Safety Officer</option>
                                                <option value="technician">Technician</option>
                                                <option value="witness">Witness</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="personnel_cert_expiry" class="form-label">Certificate Expiry (if relevant)</label>
                                            <input type="date" class="form-control" id="personnel_cert_expiry" name="personnel_cert_expiry">
                                        </div>
                                        <div class="col-12">
                                            <label for="personnel_responsibilities" class="form-label">Specific Responsibilities</label>
                                            <textarea class="form-control" id="personnel_responsibilities" name="personnel_responsibilities" rows="2" placeholder="Describe specific responsibilities for this inspection..."></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="personnel_notes" class="form-label">Additional Notes</label>
                                            <textarea class="form-control" id="personnel_notes" name="personnel_notes" rows="2" placeholder="Any additional notes about this assignment..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="savePersonnelBtn">
                    <i class="fas fa-plus me-2"></i>Add Personnel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Personnel Modal -->
<div class="modal fade" id="editPersonnelModal" tabindex="-1" aria-labelledby="editPersonnelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonnelModalLabel">Edit Personnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPersonnelForm">
                    <input type="hidden" id="edit_personnel_index" name="edit_personnel_index">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_personnel_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_personnel_name" name="personnel_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_personnel_company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="edit_personnel_company" name="personnel_company">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_personnel_qualification" class="form-label">Qualification/Certification</label>
                            <input type="text" class="form-control" id="edit_personnel_qualification" name="personnel_qualification" placeholder="e.g., Level 2 NDT, LEEA Inspector">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_personnel_cert_number" class="form-label">Certificate Number</label>
                            <input type="text" class="form-control" id="edit_personnel_cert_number" name="personnel_cert_number">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_personnel_cert_expiry" class="form-label">Certificate Expiry</label>
                            <input type="date" class="form-control" id="edit_personnel_cert_expiry" name="personnel_cert_expiry">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_personnel_role" class="form-label">Role</label>
                            <select class="form-select" id="edit_personnel_role" name="personnel_role">
                                <option value="">Select role...</option>
                                <option value="lead_inspector">Lead Inspector</option>
                                <option value="inspector">Inspector</option>
                                <option value="assistant">Assistant</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="client_representative">Client Representative</option>
                                <option value="safety_officer">Safety Officer</option>
                                <option value="technician">Technician</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_personnel_responsibilities" class="form-label">Responsibilities</label>
                            <textarea class="form-control" id="edit_personnel_responsibilities" name="personnel_responsibilities" rows="2" placeholder="Specific responsibilities during the inspection..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_personnel_contact" class="form-label">Contact Information</label>
                            <input type="text" class="form-control" id="edit_personnel_contact" name="personnel_contact" placeholder="Phone or email">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_personnel_rate" class="form-label">Hourly Rate (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="edit_personnel_rate" name="personnel_rate" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updatePersonnel()">Update Personnel</button>
            </div>
        </div>
    </div>
</div>
