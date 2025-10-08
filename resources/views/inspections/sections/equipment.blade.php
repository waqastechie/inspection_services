<!-- Equipment Details Section (Step 3) -->
<section class="form-section" id="section-equipment-details">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-tools"></i>
            Equipment Details
        </h2>
        <p class="section-description">
            Document detailed information about the equipment being inspected including specifications, certification, and condition.
        </p>
    </div>

    <div class="section-content">
        <div class="row g-3">
            <!-- Equipment Identification -->
            <div class="col-12">
                <h5 class="mb-3"><i class="fas fa-tag me-2"></i>Equipment Identification</h5>
            </div>
            
            <div class="col-md-6">
                <label for="equipment_type" class="form-label">Equipment Type *</label>
                <input type="text" class="form-control" id="equipment_type" name="equipment_type" 
                       value="{{ old('equipment_type', $inspection->equipment_type ?? '') }}" required>
            </div>
            
            <div class="col-md-6">
                <label for="serial_number" class="form-label">Serial Number *</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" 
                       value="{{ old('serial_number', $inspection->serial_number ?? '') }}" required>
            </div>

            <div class="col-md-6">
                <label for="manufacturer" class="form-label">Manufacturer</label>
                <input type="text" class="form-control" id="manufacturer" name="manufacturer" 
                       value="{{ old('manufacturer', $inspection->manufacturer ?? '') }}">
            </div>

            <div class="col-md-6">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" 
                       value="{{ old('model', $inspection->model ?? '') }}">
            </div>

            <div class="col-md-6">
                <label for="asset_tag" class="form-label">Asset Tag</label>
                <input type="text" class="form-control" id="asset_tag" name="asset_tag" 
                       value="{{ old('asset_tag', $inspection->asset_tag ?? '') }}">
            </div>

            <div class="col-md-6">
                <label for="manufacture_year" class="form-label">Year of Manufacture</label>
                <input type="number" class="form-control" id="manufacture_year" name="manufacture_year" 
                       min="1900" max="{{ date('Y') }}" 
                       value="{{ old('manufacture_year', $inspection->manufacture_year ?? '') }}">
            </div>

            <!-- Equipment Specifications -->
            <div class="col-12 mt-4">
                <h5 class="mb-3"><i class="fas fa-cogs me-2"></i>Equipment Specifications</h5>
            </div>

            <div class="col-md-8">
                <label for="equipment_description" class="form-label">Equipment Description</label>
                <textarea class="form-control" id="equipment_description" name="equipment_description" 
                          rows="3" placeholder="Detailed description of the equipment...">{{ old('equipment_description', $inspection->equipment_description ?? '') }}</textarea>
            </div>

            <div class="col-md-4">
                <label for="capacity" class="form-label">Capacity</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="capacity" name="capacity" 
                           step="0.01" value="{{ old('capacity', $inspection->capacity ?? '') }}">
                    <select class="form-select" id="capacity_unit" name="capacity_unit" style="max-width: 100px;">
                        <option value="kg" {{ old('capacity_unit', $inspection->capacity_unit ?? '') == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="tonnes" {{ old('capacity_unit', $inspection->capacity_unit ?? '') == 'tonnes' ? 'selected' : '' }}>tonnes</option>
                        <option value="lbs" {{ old('capacity_unit', $inspection->capacity_unit ?? '') == 'lbs' ? 'selected' : '' }}>lbs</option>
                        <option value="kN" {{ old('capacity_unit', $inspection->capacity_unit ?? '') == 'kN' ? 'selected' : '' }}>kN</option>
                    </select>
                </div>
            </div>

            <!-- Certification & Standards -->
            <div class="col-12 mt-4">
                <h5 class="mb-3"><i class="fas fa-certificate me-2"></i>Certification & Standards</h5>
            </div>

            <div class="col-md-6">
                <label for="applicable_standard" class="form-label">Applicable Standard</label>
                <input type="text" class="form-control" id="applicable_standard" name="applicable_standard" 
                       value="{{ old('applicable_standard', $inspection->applicable_standard ?? '') }}"
                       placeholder="e.g., AS 1418.1, BS 7121, ASME B30.9">
            </div>

            <div class="col-md-6">
                <label for="inspection_class" class="form-label">Inspection Class</label>
                <select class="form-select" id="inspection_class" name="inspection_class">
                    <option value="">Select Inspection Class</option>
                    <option value="Class 1" {{ old('inspection_class', $inspection->inspection_class ?? '') == 'Class 1' ? 'selected' : '' }}>Class 1</option>
                    <option value="Class 2" {{ old('inspection_class', $inspection->inspection_class ?? '') == 'Class 2' ? 'selected' : '' }}>Class 2</option>
                    <option value="Class 3" {{ old('inspection_class', $inspection->inspection_class ?? '') == 'Class 3' ? 'selected' : '' }}>Class 3</option>
                    <option value="Class 4" {{ old('inspection_class', $inspection->inspection_class ?? '') == 'Class 4' ? 'selected' : '' }}>Class 4</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="certification_body" class="form-label">Certification Body</label>
                <input type="text" class="form-control" id="certification_body" name="certification_body" 
                       value="{{ old('certification_body', $inspection->certification_body ?? '') }}"
                       placeholder="e.g., SAI Global, Bureau Veritas">
            </div>

            <div class="col-md-6">
                <label for="previous_certificate_number" class="form-label">Previous Certificate Number</label>
                <input type="text" class="form-control" id="previous_certificate_number" name="previous_certificate_number" 
                       value="{{ old('previous_certificate_number', $inspection->previous_certificate_number ?? '') }}">
            </div>

            <!-- Inspection History -->
            <div class="col-12 mt-4">
                <h5 class="mb-3"><i class="fas fa-history me-2"></i>Inspection History</h5>
            </div>

            <div class="col-md-4">
                <label for="last_inspection_date" class="form-label">Last Inspection Date</label>
                <input type="date" class="form-control" id="last_inspection_date" name="last_inspection_date" 
                       value="{{ old('last_inspection_date', $inspection->last_inspection_date ?? '') }}">
            </div>

            <div class="col-md-4">
                <label for="next_inspection_due" class="form-label">Next Inspection Due</label>
                <input type="date" class="form-control" id="next_inspection_due" name="next_inspection_due" 
                       value="{{ old('next_inspection_due', $inspection->next_inspection_due ?? '') }}">
            </div>

            <div class="col-md-4">
                <label for="next_inspection_date" class="form-label">Next Inspection Date</label>
                <input type="date" class="form-control" id="next_inspection_date" name="next_inspection_date" 
                       value="{{ old('next_inspection_date', $inspection->next_inspection_date ?? '') }}">
            </div>

            <!-- Equipment Condition -->
            <div class="col-12 mt-4">
                <h5 class="mb-3"><i class="fas fa-clipboard-check me-2"></i>Equipment Condition</h5>
            </div>

            <div class="col-md-6">
                <label for="surface_condition" class="form-label">Surface Condition</label>
                <select class="form-select" id="surface_condition" name="surface_condition">
                    <option value="">Select Surface Condition</option>
                    <option value="Excellent" {{ old('surface_condition', $inspection->surface_condition ?? '') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                    <option value="Good" {{ old('surface_condition', $inspection->surface_condition ?? '') == 'Good' ? 'selected' : '' }}>Good</option>
                    <option value="Fair" {{ old('surface_condition', $inspection->surface_condition ?? '') == 'Fair' ? 'selected' : '' }}>Fair</option>
                    <option value="Poor" {{ old('surface_condition', $inspection->surface_condition ?? '') == 'Poor' ? 'selected' : '' }}>Poor</option>
                    <option value="Requires Cleaning" {{ old('surface_condition', $inspection->surface_condition ?? '') == 'Requires Cleaning' ? 'selected' : '' }}>Requires Cleaning</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="overall_result" class="form-label">Overall Equipment Status</label>
                <select class="form-select" id="overall_result" name="overall_result">
                    <option value="">Select Status</option>
                    <option value="Pass" {{ old('overall_result', $inspection->overall_result ?? '') == 'Pass' ? 'selected' : '' }}>Pass</option>
                    <option value="Conditional Pass" {{ old('overall_result', $inspection->overall_result ?? '') == 'Conditional Pass' ? 'selected' : '' }}>Conditional Pass</option>
                    <option value="Fail" {{ old('overall_result', $inspection->overall_result ?? '') == 'Fail' ? 'selected' : '' }}>Fail</option>
                    <option value="Requires Further Investigation" {{ old('overall_result', $inspection->overall_result ?? '') == 'Requires Further Investigation' ? 'selected' : '' }}>Requires Further Investigation</option>
                </select>
            </div>

            <!-- Defects and Recommendations -->
            <div class="col-12 mt-4">
                <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Defects & Recommendations</h5>
            </div>

            <div class="col-md-6">
                <label for="defects_found" class="form-label">Defects Found</label>
                <textarea class="form-control" id="defects_found" name="defects_found" 
                          rows="4" placeholder="Describe any defects or issues found...">{{ old('defects_found', $inspection->defects_found ?? '') }}</textarea>
            </div>

            <div class="col-md-6">
                <label for="recommendations" class="form-label">Recommendations</label>
                <textarea class="form-control" id="recommendations" name="recommendations" 
                          rows="4" placeholder="Recommendations for maintenance, repair, or further action...">{{ old('recommendations', $inspection->recommendations ?? '') }}</textarea>
            </div>

            <div class="col-12">
                <label for="limitations" class="form-label">Limitations</label>
                <textarea class="form-control" id="limitations" name="limitations" 
                          rows="3" placeholder="Any limitations encountered during the inspection...">{{ old('limitations', $inspection->limitations ?? '') }}</textarea>
            </div>

            <!-- Additional Notes -->
            <div class="col-12 mt-4">
                <h5 class="mb-3"><i class="fas fa-sticky-note me-2"></i>Additional Notes</h5>
            </div>

            <div class="col-12">
                <label for="general_notes" class="form-label">General Notes</label>
                <textarea class="form-control" id="general_notes" name="general_notes" 
                          rows="4" placeholder="Any additional notes or observations...">{{ old('general_notes', $inspection->general_notes ?? '') }}</textarea>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate next inspection date based on inspection class
    const inspectionClassSelect = document.getElementById('inspection_class');
    const nextInspectionDueField = document.getElementById('next_inspection_due');
    const inspectionDateField = document.getElementById('inspection_date');
    
    if (inspectionClassSelect && nextInspectionDueField) {
        inspectionClassSelect.addEventListener('change', function() {
            const inspectionDate = inspectionDateField ? inspectionDateField.value : new Date().toISOString().split('T')[0];
            
            if (inspectionDate && this.value) {
                const date = new Date(inspectionDate);
                let monthsToAdd = 12; // Default 12 months
                
                // Adjust based on inspection class
                switch(this.value) {
                    case 'Class 1':
                        monthsToAdd = 6;
                        break;
                    case 'Class 2':
                        monthsToAdd = 12;
                        break;
                    case 'Class 3':
                        monthsToAdd = 24;
                        break;
                    case 'Class 4':
                        monthsToAdd = 36;
                        break;
                }
                
                date.setMonth(date.getMonth() + monthsToAdd);
                nextInspectionDueField.value = date.toISOString().split('T')[0];
            }
        });
    }
    
    // Validate capacity input
    const capacityField = document.getElementById('capacity');
    if (capacityField) {
        capacityField.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    }
    
    // Auto-format serial number (uppercase)
    const serialNumberField = document.getElementById('serial_number');
    if (serialNumberField) {
        serialNumberField.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
});
</script>