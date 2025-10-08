<!-- Testing Methods Section -->
<section class="form-section" id="section-testing-methods">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-vials me-3"></i>
                    Testing Methods & Equipment
                </h2>
                <p class="section-description">
                    Configure testing methods, equipment, and procedures
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
            <select class="form-control" name="other_test_inspector">
                <option value="">Select Inspector</option>
                @if(isset($personnel))
                    @foreach($personnel as $person)
                        <option value="{{ $person->id }}" 
                                {{ old('other_test_inspector', $inspection?->otherTest?->other_test_inspector ?? '') == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }} - {{ $person->job_title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Testing Method Selection -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-flask me-2"></i>Primary Testing Method
            </label>
            <select class="form-control" name="other_test_method">
                <option value="">Select Testing Method</option>
                <option value="mpi" {{ old('other_test_method', $inspection?->otherTest?->other_test_method ?? '') == 'mpi' ? 'selected' : '' }}>
                    MPI (Magnetic Particle Inspection)
                </option>
                <option value="dpi" {{ old('other_test_method', $inspection?->otherTest?->other_test_method ?? '') == 'dpi' ? 'selected' : '' }}>
                    DPI (Dye Penetrant Inspection)
                </option>
                <option value="load_testing" {{ old('other_test_method', $inspection?->otherTest?->other_test_method ?? '') == 'load_testing' ? 'selected' : '' }}>
                    Load Testing
                </option>
                <option value="visual_inspection" {{ old('other_test_method', $inspection?->otherTest?->other_test_method ?? '') == 'visual_inspection' ? 'selected' : '' }}>
                    Visual Inspection
                </option>
                <option value="dimensional_check" {{ old('other_test_method', $inspection?->otherTest?->other_test_method ?? '') == 'dimensional_check' ? 'selected' : '' }}>
                    Dimensional Check
                </option>
                <option value="function_test" {{ old('other_test_method', $inspection?->otherTest?->other_test_method ?? '') == 'function_test' ? 'selected' : '' }}>
                    Function Test
                </option>
                <option value="other" {{ old('other_test_method', $inspection?->otherTest?->other_test_method ?? '') == 'other' ? 'selected' : '' }}>
                    Other Method
                </option>
            </select>
        </div>

        <!-- Testing Equipment -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-tools me-2"></i>Testing Equipment Used
            </label>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Primary Equipment</label>
                    <select class="form-control" name="other_test_equipment">
                        <option value="">Select Equipment</option>
                        <option value="ac_yoke" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'ac_yoke' ? 'selected' : '' }}>
                            AC Yoke
                        </option>
                        <option value="dc_yoke" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'dc_yoke' ? 'selected' : '' }}>
                            DC Yoke
                        </option>
                        <option value="permanent_magnet" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'permanent_magnet' ? 'selected' : '' }}>
                            Permanent Magnet
                        </option>
                        <option value="test_blocks" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'test_blocks' ? 'selected' : '' }}>
                            Test Blocks
                        </option>
                        <option value="comparator_block" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'comparator_block' ? 'selected' : '' }}>
                            Comparator Block
                        </option>
                        <option value="load_cell" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'load_cell' ? 'selected' : '' }}>
                            Load Cell
                        </option>
                        <option value="water_bags" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'water_bags' ? 'selected' : '' }}>
                            Water Bags
                        </option>
                        <option value="dead_weight_blocks" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'dead_weight_blocks' ? 'selected' : '' }}>
                            Dead Weight Blocks
                        </option>
                        <option value="digital_calipers" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'digital_calipers' ? 'selected' : '' }}>
                            Digital Calipers
                        </option>
                        <option value="magnification_equipment" {{ old('other_test_equipment', $inspection?->otherTest?->other_test_equipment ?? '') == 'magnification_equipment' ? 'selected' : '' }}>
                            Magnification Equipment
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Equipment Serial/ID</label>
                    <input type="text" class="form-control" name="equipment_serial" 
                           placeholder="Enter equipment serial number"
                           value="{{ old('equipment_serial', $inspection?->otherTest?->equipment_serial ?? '') }}">
                </div>
            </div>
        </div>

        <!-- Testing Materials -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-tint me-2"></i>Testing Materials & Consumables
            </label>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Contrast Paint</label>
                    <select class="form-control" name="contrast_paint">
                        <option value="">Select Paint Type</option>
                        <option value="white_contrast_paint" {{ old('contrast_paint') == 'white_contrast_paint' ? 'selected' : '' }}>
                            White Contrast Paint
                        </option>
                        <option value="black_contrast_paint" {{ old('contrast_paint') == 'black_contrast_paint' ? 'selected' : '' }}>
                            Black Contrast Paint
                        </option>
                        <option value="fluorescent_paint" {{ old('contrast_paint') == 'fluorescent_paint' ? 'selected' : '' }}>
                            Fluorescent Paint
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cleaning Agent</label>
                    <select class="form-control" name="cleaning_agent">
                        <option value="">Select Cleaner</option>
                        <option value="jac2_cleaner" {{ old('cleaning_agent') == 'jac2_cleaner' ? 'selected' : '' }}>
                            Jac-2 Cleaner
                        </option>
                        <option value="solvent_cleaner" {{ old('cleaning_agent') == 'solvent_cleaner' ? 'selected' : '' }}>
                            Solvent Cleaner
                        </option>
                        <option value="water_rinse" {{ old('cleaning_agent') == 'water_rinse' ? 'selected' : '' }}>
                            Water Rinse
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Inspection Medium</label>
                    <select class="form-control" name="inspection_medium">
                        <option value="">Select Medium</option>
                        <option value="fluorescent_ink" {{ old('inspection_medium') == 'fluorescent_ink' ? 'selected' : '' }}>
                            Fluorescent Ink
                        </option>
                        <option value="penetrant" {{ old('inspection_medium') == 'penetrant' ? 'selected' : '' }}>
                            Penetrant
                        </option>
                        <option value="developer" {{ old('inspection_medium') == 'developer' ? 'selected' : '' }}>
                            Developer
                        </option>
                        <option value="magnetic_particles" {{ old('inspection_medium') == 'magnetic_particles' ? 'selected' : '' }}>
                            Magnetic Particles
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Test Conditions -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-thermometer-half me-2"></i>Test Conditions
            </label>
            <textarea class="form-control" name="other_test_conditions" rows="3"
                      placeholder="Describe environmental conditions, temperature, lighting, etc.">{{ old('other_test_conditions', $inspection?->otherTest?->other_test_conditions ?? '') }}</textarea>
        </div>

        <!-- Load Test Parameters (if applicable) -->
        <div class="inspection-question mb-4" id="load-test-parameters" style="display: none;">
            <label class="form-label fw-bold">
                <i class="fas fa-weight-hanging me-2"></i>Load Test Parameters
            </label>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Test Load Percentage (%)</label>
                    <input type="number" class="form-control" name="test_load_percentage" 
                           placeholder="e.g., 125" min="0" max="200" step="0.1"
                           value="{{ old('test_load_percentage') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Test Duration (minutes)</label>
                    <input type="number" class="form-control" name="test_duration" 
                           placeholder="e.g., 10" min="0" step="0.1"
                           value="{{ old('test_duration') }}">
                </div>
            </div>
        </div>

        <!-- Test Results -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-clipboard-list me-2"></i>Test Results & Observations
            </label>
            <textarea class="form-control" name="other_test_results" rows="4"
                      placeholder="Record test results, observations, defects found, measurements, etc.">{{ old('other_test_results', $inspection?->otherTest?->other_test_results ?? '') }}</textarea>
        </div>

        <!-- Additional Comments -->
        <div class="inspection-question mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-comment me-2"></i>Additional Comments
            </label>
            <textarea class="form-control" name="other_test_comments" rows="3"
                      placeholder="Any additional notes, recommendations, or observations...">{{ old('other_test_comments', $inspection?->otherTest?->other_test_comments ?? '') }}</textarea>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide load test parameters based on testing method
    const testMethodSelect = document.querySelector('select[name="other_test_method"]');
    const loadTestParams = document.getElementById('load-test-parameters');
    
    if (testMethodSelect && loadTestParams) {
        testMethodSelect.addEventListener('change', function() {
            if (this.value === 'load_testing') {
                loadTestParams.style.display = 'block';
            } else {
                loadTestParams.style.display = 'none';
            }
        });
        
        // Check initial state
        if (testMethodSelect.value === 'load_testing') {
            loadTestParams.style.display = 'block';
        }
    }
});
</script>