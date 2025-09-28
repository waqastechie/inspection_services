<!-- MPI Service Section -->
<section class="form-section" id="section-mpi-service">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-search me-3"></i>
                    MPI Service
                </h2>
                <p class="section-description">
                    Magnetic Particle Inspection service input form
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
            <select class="form-control" name="mpi_service_inspector" required>
                <option value="">Select Inspector</option>
                @if(isset($personnel))
                    @foreach($personnel as $person)
                        <option value="{{ $person->id }}" 
                                {{ old('mpi_service_inspector', $inspection?->mpi_service_inspector ?? '') == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }} - {{ $person->job_title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="row g-4">
            <!-- First Row -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Contrast Paint Application Method <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="contrast_paint_method" data-mpi-required="true">
                    <option value="">Select Method...</option>
                    <option value="spray">Spray Application</option>
                    <option value="brush">Brush Application</option>
                    <option value="roller">Roller Application</option>
                    <option value="dip">Dip Application</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 1 Application Method <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="ink_powder_1_method" data-mpi-required="true">
                    <option value="">Select Method...</option>
                    <option value="wet_continuous">Wet Continuous</option>
                    <option value="wet_residual">Wet Residual</option>
                    <option value="dry_continuous">Dry Continuous</option>
                    <option value="dry_residual">Dry Residual</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Magnetic Particle Concentration
                </label>
                <input type="text" class="form-control" name="magnetic_particle_concentration" 
                       placeholder="Enter concentration">
            </div>

            <!-- Second Row -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Current Flow <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="magnetic_flow" data-mpi-required="true">
                    <option value="">Select Current Flow...</option>
                    <option value="ac">AC (Alternating Current)</option>
                    <option value="dc">DC (Direct Current)</option>
                    <option value="pulsed_dc">Pulsed DC</option>
                    <option value="three_phase">Three Phase AC</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 1 Carrier Type <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="ink_powder_1_carrier" data-mpi-required="true">
                    <option value="">Select Carrier Type...</option>
                    <option value="water">Water</option>
                    <option value="oil">Oil</option>
                    <option value="kerosene">Kerosene</option>
                    <option value="conditioner">Conditioner</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Contact Spacing
                </label>
                <input type="text" class="form-control" name="contact_spacing" 
                       placeholder="Enter spacing">
            </div>

            <!-- Third Row -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Magnetic Flow <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="current_flow" data-mpi-required="true">
                    <option value="">Select Magnetic Flow...</option>
                    <option value="longitudinal">Longitudinal</option>
                    <option value="circular">Circular</option>
                    <option value="multidirectional">Multidirectional</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 2 Application Method
                </label>
                <select class="form-select" name="ink_powder_2_method">
                    <option value="">Select Method...</option>
                    <option value="wet_continuous">Wet Continuous</option>
                    <option value="wet_residual">Wet Residual</option>
                    <option value="dry_continuous">Dry Continuous</option>
                    <option value="dry_residual">Dry Residual</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 2 Carrier Type
                </label>
                <select class="form-select" name="ink_powder_2_carrier">
                    <option value="">Select Carrier Type...</option>
                    <option value="water">Water</option>
                    <option value="oil">Oil</option>
                    <option value="kerosene">Kerosene</option>
                    <option value="conditioner">Conditioner</option>
                </select>
            </div>

            <!-- Fourth Row -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Test Temperature
                </label>
                <select class="form-select" name="test_temperature">
                    <option value="">Select...</option>
                    <option value="ambient">Ambient</option>
                    <option value="elevated">Elevated</option>
                    <option value="controlled">Controlled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Pull Test
                </label>
                <select class="form-select" name="pull_test">
                    <option value="">Select...</option>
                    <option value="performed">Performed</option>
                    <option value="not_required">Not Required</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Post Test Cleaning
                </label>
                <select class="form-select" name="post_test_cleaning">
                    <option value="">Select...</option>
                    <option value="water_rinse">Water Rinse</option>
                    <option value="solvent_clean">Solvent Clean</option>
                    <option value="mechanical">Mechanical</option>
                    <option value="not_required">Not Required</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Initial Demagnetisation
                </label>
                <select class="form-select" name="initial_demagnetisation">
                    <option value="">Select...</option>
                    <option value="performed">Performed</option>
                    <option value="not_required">Not Required</option>
                    <option value="partial">Partial</option>
                </select>
            </div>

            <!-- Final Demagnetisation -->
            <div class="col-12">
                <label class="form-label fw-semibold">
                    Final Demagnetisation
                </label>
                <select class="form-select" name="final_demagnetisation">
                    <option value="">Select...</option>
                    <option value="performed">Performed</option>
                    <option value="not_required">Not Required</option>
                    <option value="partial">Partial</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <!-- MPI Results -->
            <div class="col-12">
                <label class="form-label fw-semibold">
                    MPI Test Results
                </label>
                <textarea class="form-control" name="mpi_results" rows="4" 
                          placeholder="Enter MPI test results and observations..."></textarea>
                <small class="form-text text-muted">
                    Document any indications found, their location, size, and significance
                </small>
            </div>
        </div>
    </div>
</section>
