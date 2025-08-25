<!-- Lifting Operation Details Section -->
<section class="form-section" id="section-lifting">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-arrows-alt-v"></i>
            Lifting Operation Details
        </h2>
        <p class="section-description">
            Specify the type and parameters of the lifting operation being inspected.
        </p>
    </div>

    <div class="section-content">
        <div class="row g-4">
            <!-- Type of Lifting Operation -->
            <div class="col-md-6">
                <label for="liftingOperationType" class="form-label">
                    <i class="fas fa-cog me-2"></i>Type of Lifting Operation *
                </label>
                <select class="form-select @error('lifting_operation_type') is-invalid @enderror" 
                        id="liftingOperationType" name="lifting_operation_type" required>
                    <option value="">Select operation type</option>
                    <option value="Crane Lifting" {{ old('lifting_operation_type') == 'Crane Lifting' ? 'selected' : '' }}>Crane Lifting</option>
                    <option value="Mobile Crane" {{ old('lifting_operation_type') == 'Mobile Crane' ? 'selected' : '' }}>Mobile Crane</option>
                    <option value="Tower Crane" {{ old('lifting_operation_type') == 'Tower Crane' ? 'selected' : '' }}>Tower Crane</option>
                    <option value="Overhead Crane" {{ old('lifting_operation_type') == 'Overhead Crane' ? 'selected' : '' }}>Overhead Crane</option>
                    <option value="Jib Crane" {{ old('lifting_operation_type') == 'Jib Crane' ? 'selected' : '' }}>Jib Crane</option>
                    <option value="Gantry Crane" {{ old('lifting_operation_type') == 'Gantry Crane' ? 'selected' : '' }}>Gantry Crane</option>
                    <option value="Hoist/Winch" {{ old('lifting_operation_type') == 'Hoist/Winch' ? 'selected' : '' }}>Hoist/Winch</option>
                    <option value="Forklift" {{ old('lifting_operation_type') == 'Forklift' ? 'selected' : '' }}>Forklift</option>
                    <option value="Manual Lifting" {{ old('lifting_operation_type') == 'Manual Lifting' ? 'selected' : '' }}>Manual Lifting</option>
                    <option value="Rigging Operation" {{ old('lifting_operation_type') == 'Rigging Operation' ? 'selected' : '' }}>Rigging Operation</option>
                    <option value="Other" {{ old('lifting_operation_type') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('lifting_operation_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Primary type of lifting equipment being used</small>
            </div>

            <!-- Load Weight -->
            <div class="col-md-6">
                <label for="loadWeight" class="form-label">
                    <i class="fas fa-weight-hanging me-2"></i>Load Weight (kg)
                </label>
                <input type="number" class="form-control @error('load_weight') is-invalid @enderror" 
                       id="loadWeight" name="load_weight" 
                       value="{{ old('load_weight') }}" 
                       placeholder="Enter load weight" step="0.1" min="0">
                @error('load_weight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Total weight of the load being lifted</small>
            </div>

            <!-- Lifting Height -->
            <div class="col-md-6">
                <label for="liftingHeight" class="form-label">
                    <i class="fas fa-ruler-vertical me-2"></i>Lifting Height (m)
                </label>
                <input type="number" class="form-control @error('lifting_height') is-invalid @enderror" 
                       id="liftingHeight" name="lifting_height" 
                       value="{{ old('lifting_height') }}" 
                       placeholder="Enter lifting height" step="0.1" min="0">
                @error('lifting_height')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Maximum height the load will be lifted</small>
            </div>

            <!-- Radius (for crane operations) -->
            <div class="col-md-6">
                <label for="radius" class="form-label">
                    <i class="fas fa-arrows-alt-h me-2"></i>Radius (m)
                </label>
                <input type="number" class="form-control @error('radius') is-invalid @enderror" 
                       id="radius" name="radius" 
                       value="{{ old('radius') }}" 
                       placeholder="Enter radius" step="0.1" min="0">
                @error('radius')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Horizontal distance from crane center to load (if applicable)</small>
            </div>

            <!-- Lift Plan Reference -->
            <div class="col-md-6">
                <label for="liftPlanReference" class="form-label">
                    <i class="fas fa-file-alt me-2"></i>Lift Plan Reference
                </label>
                <input type="text" class="form-control" id="liftPlanReference" name="lift_plan_reference" 
                       value="{{ old('lift_plan_reference') }}" 
                       placeholder="Enter lift plan document reference">
                <small class="form-text text-muted">Reference number or ID of the approved lift plan</small>
            </div>

            <!-- Critical Lift -->
            <div class="col-md-6">
                <label for="criticalLift" class="form-label">
                    <i class="fas fa-exclamation-triangle me-2"></i>Critical Lift Classification
                </label>
                <select class="form-select" id="criticalLift" name="critical_lift">
                    <option value="">Select classification</option>
                    <option value="Non-Critical" {{ old('critical_lift') == 'Non-Critical' ? 'selected' : '' }}>Non-Critical</option>
                    <option value="Critical" {{ old('critical_lift') == 'Critical' ? 'selected' : '' }}>Critical</option>
                    <option value="Super Critical" {{ old('critical_lift') == 'Super Critical' ? 'selected' : '' }}>Super Critical</option>
                </select>
                <small class="form-text text-muted">Classification based on risk assessment</small>
            </div>

            <!-- Safe Working Load (SWL) -->
            <div class="col-md-6">
                <label for="safeWorkingLoad" class="form-label">
                    <i class="fas fa-shield-alt me-2"></i>Safe Working Load (SWL) (kg)
                </label>
                <input type="number" class="form-control" id="safeWorkingLoad" name="safe_working_load" 
                       value="{{ old('safe_working_load') }}" 
                       placeholder="Enter SWL" step="0.1" min="0">
                <small class="form-text text-muted">Maximum safe working load for the equipment</small>
            </div>

            <!-- Working Load Limit (WLL) -->
            <div class="col-md-6">
                <label for="workingLoadLimit" class="form-label">
                    <i class="fas fa-balance-scale me-2"></i>Working Load Limit (WLL) (kg)
                </label>
                <input type="number" class="form-control" id="workingLoadLimit" name="working_load_limit" 
                       value="{{ old('working_load_limit') }}" 
                       placeholder="Enter WLL" step="0.1" min="0">
                <small class="form-text text-muted">Working load limit for rigging equipment</small>
            </div>

            <!-- Load Description -->
            <div class="col-12">
                <label for="loadDescription" class="form-label">
                    <i class="fas fa-boxes me-2"></i>Load Description
                </label>
                <textarea class="form-control" id="loadDescription" name="load_description" 
                          rows="3" placeholder="Describe the load being lifted">{{ old('load_description') }}</textarea>
                <small class="form-text text-muted">Detailed description of the load, dimensions, and characteristics</small>
            </div>

            <!-- Special Considerations -->
            <div class="col-12">
                <label for="specialConsiderations" class="form-label">
                    <i class="fas fa-lightbulb me-2"></i>Special Considerations
                </label>
                <textarea class="form-control" id="specialConsiderations" name="special_considerations" 
                          rows="3" placeholder="Any special considerations for this lifting operation">{{ old('special_considerations') }}</textarea>
                <small class="form-text text-muted">Hazards, restrictions, or special procedures for this operation</small>
            </div>
        </div>
    </div>
</section>
