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
                                {{ old('mpi_service_inspector', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->mpi_service_inspector : '') == $person->id ? 'selected' : '' }}>
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
                    <option value="spray" {{ old('contrast_paint_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->contrast_paint_method : '') == 'spray' ? 'selected' : '' }}>Spray Application</option>
                    <option value="brush" {{ old('contrast_paint_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->contrast_paint_method : '') == 'brush' ? 'selected' : '' }}>Brush Application</option>
                    <option value="roller" {{ old('contrast_paint_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->contrast_paint_method : '') == 'roller' ? 'selected' : '' }}>Roller Application</option>
                    <option value="dip" {{ old('contrast_paint_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->contrast_paint_method : '') == 'dip' ? 'selected' : '' }}>Dip Application</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 1 Application Method <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="ink_powder_1_method" data-mpi-required="true">
                    <option value="">Select Method...</option>
                    <option value="wet_continuous" {{ old('ink_powder_1_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_method : '') == 'wet_continuous' ? 'selected' : '' }}>Wet Continuous</option>
                    <option value="wet_residual" {{ old('ink_powder_1_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_method : '') == 'wet_residual' ? 'selected' : '' }}>Wet Residual</option>
                    <option value="dry_continuous" {{ old('ink_powder_1_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_method : '') == 'dry_continuous' ? 'selected' : '' }}>Dry Continuous</option>
                    <option value="dry_residual" {{ old('ink_powder_1_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_method : '') == 'dry_residual' ? 'selected' : '' }}>Dry Residual</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Magnetic Particle Concentration
                </label>
                <input type="text" class="form-control" name="magnetic_particle_concentration" 
                       placeholder="Enter concentration" value="{{ old('magnetic_particle_concentration', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->magnetic_particle_concentration : '') }}">
            </div>

            <!-- Second Row -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Current Flow <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="magnetic_flow" data-mpi-required="true">
                    <option value="">Select Current Flow...</option>
                    <option value="ac" {{ old('magnetic_flow', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->magnetic_flow : '') == 'ac' ? 'selected' : '' }}>AC (Alternating Current)</option>
                    <option value="dc" {{ old('magnetic_flow', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->magnetic_flow : '') == 'dc' ? 'selected' : '' }}>DC (Direct Current)</option>
                    <option value="pulsed_dc" {{ old('magnetic_flow', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->magnetic_flow : '') == 'pulsed_dc' ? 'selected' : '' }}>Pulsed DC</option>
                    <option value="three_phase" {{ old('magnetic_flow', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->magnetic_flow : '') == 'three_phase' ? 'selected' : '' }}>Three Phase AC</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 1 Carrier Type <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="ink_powder_1_carrier" data-mpi-required="true">
                    <option value="">Select Carrier Type...</option>
                    <option value="water" {{ old('ink_powder_1_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_carrier : '') == 'water' ? 'selected' : '' }}>Water</option>
                    <option value="oil" {{ old('ink_powder_1_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_carrier : '') == 'oil' ? 'selected' : '' }}>Oil</option>
                    <option value="kerosene" {{ old('ink_powder_1_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_carrier : '') == 'kerosene' ? 'selected' : '' }}>Kerosene</option>
                    <option value="conditioner" {{ old('ink_powder_1_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_1_carrier : '') == 'conditioner' ? 'selected' : '' }}>Conditioner</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Contact Spacing
                </label>
                <input type="text" class="form-control" name="contact_spacing" 
                       placeholder="Enter spacing" value="{{ old('contact_spacing', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->contact_spacing : '') }}">
            </div>

            <!-- Third Row -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Magnetic Flow <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="current_flow" data-mpi-required="true">
                    <option value="">Select Magnetic Flow...</option>
                    <option value="longitudinal" {{ old('current_flow', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->current_flow : '') == 'longitudinal' ? 'selected' : '' }}>Longitudinal</option>
                    <option value="circular" {{ old('current_flow', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->current_flow : '') == 'circular' ? 'selected' : '' }}>Circular</option>
                    <option value="multidirectional" {{ old('current_flow', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->current_flow : '') == 'multidirectional' ? 'selected' : '' }}>Multidirectional</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 2 Application Method
                </label>
                <select class="form-select" name="ink_powder_2_method">
                    <option value="">Select Method...</option>
                    <option value="wet_continuous" {{ old('ink_powder_2_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_method : '') == 'wet_continuous' ? 'selected' : '' }}>Wet Continuous</option>
                    <option value="wet_residual" {{ old('ink_powder_2_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_method : '') == 'wet_residual' ? 'selected' : '' }}>Wet Residual</option>
                    <option value="dry_continuous" {{ old('ink_powder_2_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_method : '') == 'dry_continuous' ? 'selected' : '' }}>Dry Continuous</option>
                    <option value="dry_residual" {{ old('ink_powder_2_method', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_method : '') == 'dry_residual' ? 'selected' : '' }}>Dry Residual</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Ink/Powder 2 Carrier Type
                </label>
                <select class="form-select" name="ink_powder_2_carrier">
                    <option value="">Select Carrier Type...</option>
                    <option value="water" {{ old('ink_powder_2_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_carrier : '') == 'water' ? 'selected' : '' }}>Water</option>
                    <option value="oil" {{ old('ink_powder_2_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_carrier : '') == 'oil' ? 'selected' : '' }}>Oil</option>
                    <option value="kerosene" {{ old('ink_powder_2_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_carrier : '') == 'kerosene' ? 'selected' : '' }}>Kerosene</option>
                    <option value="conditioner" {{ old('ink_powder_2_carrier', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->ink_powder_2_carrier : '') == 'conditioner' ? 'selected' : '' }}>Conditioner</option>
                </select>
            </div>

            <!-- Fourth Row -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Test Temperature
                </label>
                <select class="form-select" name="test_temperature">
                    <option value="">Select...</option>
                    <option value="ambient" {{ old('test_temperature', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->test_temperature : '') == 'ambient' ? 'selected' : '' }}>Ambient</option>
                    <option value="elevated" {{ old('test_temperature', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->test_temperature : '') == 'elevated' ? 'selected' : '' }}>Elevated</option>
                    <option value="controlled" {{ old('test_temperature', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->test_temperature : '') == 'controlled' ? 'selected' : '' }}>Controlled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Pull Test
                </label>
                <select class="form-select" name="pull_test">
                    <option value="">Select...</option>
                    <option value="performed" {{ old('pull_test', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->pull_test : '') == 'performed' ? 'selected' : '' }}>Performed</option>
                    <option value="not_required" {{ old('pull_test', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->pull_test : '') == 'not_required' ? 'selected' : '' }}>Not Required</option>
                    <option value="failed" {{ old('pull_test', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->pull_test : '') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Post Test Cleaning
                </label>
                <select class="form-select" name="post_test_cleaning">
                    <option value="">Select...</option>
                    <option value="water_rinse" {{ old('post_test_cleaning', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->post_test_cleaning : '') == 'water_rinse' ? 'selected' : '' }}>Water Rinse</option>
                    <option value="solvent_clean" {{ old('post_test_cleaning', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->post_test_cleaning : '') == 'solvent_clean' ? 'selected' : '' }}>Solvent Clean</option>
                    <option value="mechanical" {{ old('post_test_cleaning', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->post_test_cleaning : '') == 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                    <option value="not_required" {{ old('post_test_cleaning', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->post_test_cleaning : '') == 'not_required' ? 'selected' : '' }}>Not Required</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Initial Demagnetisation
                </label>
                <select class="form-select" name="initial_demagnetisation">
                    <option value="">Select...</option>
                    <option value="performed" {{ old('initial_demagnetisation', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->initial_demagnetisation : '') == 'performed' ? 'selected' : '' }}>Performed</option>
                    <option value="not_required" {{ old('initial_demagnetisation', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->initial_demagnetisation : '') == 'not_required' ? 'selected' : '' }}>Not Required</option>
                    <option value="partial" {{ old('initial_demagnetisation', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->initial_demagnetisation : '') == 'partial' ? 'selected' : '' }}>Partial</option>
                </select>
            </div>

            <!-- Final Demagnetisation -->
            <div class="col-12">
                <label class="form-label fw-semibold">
                    Final Demagnetisation
                </label>
                <select class="form-select" name="final_demagnetisation">
                    <option value="">Select...</option>
                    <option value="performed" {{ old('final_demagnetisation', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->final_demagnetisation : '') == 'performed' ? 'selected' : '' }}>Performed</option>
                    <option value="not_required" {{ old('final_demagnetisation', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->final_demagnetisation : '') == 'not_required' ? 'selected' : '' }}>Not Required</option>
                    <option value="partial" {{ old('final_demagnetisation', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->final_demagnetisation : '') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="failed" {{ old('final_demagnetisation', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->final_demagnetisation : '') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- MPI Results -->
            <div class="col-12">
                <label class="form-label fw-semibold">
                    MPI Test Results
                </label>
                <textarea class="form-control" name="mpi_results" rows="4" 
                          placeholder="Enter MPI test results and observations...">{{ old('mpi_results', (isset($inspection) && $inspection?->mpiInspection) ? $inspection->mpiInspection->mpi_results : '') }}</textarea>
                <small class="form-text text-muted">
                    Document any indications found, their location, size, and significance
                </small>
            </div>
        </div>
    </div>
</section>