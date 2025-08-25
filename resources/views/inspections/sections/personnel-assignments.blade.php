<!-- Personnel Assignments Section -->
<section class="form-section" id="section-personnel">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-users"></i>
            Personnel Assignments
        </h2>
        <p class="section-description">
            Document all personnel involved in the lifting operation and their qualifications.
        </p>
    </div>

    <div class="section-content">
        <!-- Personnel Table -->
        <div class="table-responsive mb-4">
            <table class="table table-hover" id="personnelTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Certification</th>
                        <th>Cert. Number</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="personnelTableBody">
                    <!-- Dynamic personnel entries will be added here -->
                </tbody>
            </table>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#personnelModal">
                <i class="fas fa-user-plus me-2"></i>Add Personnel
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="clearPersonnelTable()">
                <i class="fas fa-trash me-2"></i>Clear All
            </button>
        </div>

        <!-- Key Personnel Details -->
        <div class="row g-4">
            <div class="col-md-6">
                <label for="craneOperator" class="form-label">
                    <i class="fas fa-user-cog me-2"></i>Crane Operator
                </label>
                <input type="text" class="form-control" id="craneOperator" name="crane_operator" 
                       value="{{ old('crane_operator') }}" 
                       placeholder="Name of crane operator">
                <small class="form-text text-muted">Primary crane operator for this operation</small>
            </div>

            <div class="col-md-6">
                <label for="operatorCertification" class="form-label">
                    <i class="fas fa-id-card me-2"></i>Operator Certification
                </label>
                <input type="text" class="form-control" id="operatorCertification" name="operator_certification" 
                       value="{{ old('operator_certification') }}" 
                       placeholder="Certification details">
                <small class="form-text text-muted">Operator's certification type and number</small>
            </div>

            <div class="col-md-6">
                <label for="signalPerson" class="form-label">
                    <i class="fas fa-hand-paper me-2"></i>Signal Person
                </label>
                <input type="text" class="form-control" id="signalPerson" name="signal_person" 
                       value="{{ old('signal_person') }}" 
                       placeholder="Name of signal person">
                <small class="form-text text-muted">Designated signal person for the operation</small>
            </div>

            <div class="col-md-6">
                <label for="rigger" class="form-label">
                    <i class="fas fa-hard-hat me-2"></i>Rigger
                </label>
                <input type="text" class="form-control" id="rigger" name="rigger" 
                       value="{{ old('rigger') }}" 
                       placeholder="Name of rigger">
                <small class="form-text text-muted">Qualified rigger responsible for rigging</small>
            </div>

            <div class="col-md-6">
                <label for="liftSupervisor" class="form-label">
                    <i class="fas fa-user-tie me-2"></i>Lift Supervisor
                </label>
                <input type="text" class="form-control" id="liftSupervisor" name="lift_supervisor" 
                       value="{{ old('lift_supervisor') }}" 
                       placeholder="Name of lift supervisor">
                <small class="form-text text-muted">Person supervising the lifting operation</small>
            </div>

            <div class="col-md-6">
                <label for="safetyOfficer" class="form-label">
                    <i class="fas fa-shield-alt me-2"></i>Safety Officer
                </label>
                <input type="text" class="form-control" id="safetyOfficer" name="safety_officer" 
                       value="{{ old('safety_officer') }}" 
                       placeholder="Name of safety officer">
                <small class="form-text text-muted">Safety officer overseeing the operation</small>
            </div>

            <!-- Pre-Operation Briefing -->
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="briefingCompleted" name="briefing_completed" 
                           value="1" {{ old('briefing_completed') ? 'checked' : '' }}>
                    <label class="form-check-label" for="briefingCompleted">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Pre-operation safety briefing completed with all personnel
                    </label>
                </div>
            </div>

            <!-- Personnel Notes -->
            <div class="col-12">
                <label for="personnelNotes" class="form-label">
                    <i class="fas fa-sticky-note me-2"></i>Personnel Notes
                </label>
                <textarea class="form-control" id="personnelNotes" name="personnel_notes" 
                          rows="3" placeholder="Additional notes about personnel assignments or qualifications">{{ old('personnel_notes') }}</textarea>
                <small class="form-text text-muted">Any additional information about personnel or their qualifications</small>
            </div>
        </div>
    </div>
</section>
