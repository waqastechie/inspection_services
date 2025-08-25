<!-- Equipment Details Section -->
<section class="form-section" id="section-equipment">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-tools"></i>
            Equipment Details
        </h2>
        <p class="section-description">
            Document all lifting equipment involved in the operation including certification details.
        </p>
    </div>

    <div class="section-content">
        <!-- Equipment Table -->
        <div class="table-responsive mb-4">
            <table class="table table-hover" id="equipmentTable">
                <thead>
                    <tr>
                        <th>Equipment Type</th>
                        <th>Model/Serial</th>
                        <th>Capacity</th>
                        <th>Cert. Date</th>
                        <th>Next Due</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="equipmentTableBody">
                    <!-- Dynamic equipment entries will be added here -->
                </tbody>
            </table>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#equipmentModal">
                <i class="fas fa-plus me-2"></i>Add Equipment
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="clearEquipmentTable()">
                <i class="fas fa-trash me-2"></i>Clear All
            </button>
        </div>

        <!-- Primary Equipment Details -->
        <div class="row g-4">
            <div class="col-md-6">
                <label for="equipment_type" class="form-label">
                    <i class="fas fa-cog me-2"></i>Equipment Type <span class="text-danger">*</span>
                </label>
                <select class="form-control" id="equipment_type" name="equipment_type" required>
                    <option value="">Select Equipment Type</option>
                    <option value="Crane">Crane</option>
                    <option value="Hoist">Hoist</option>
                    <option value="Lifting Beam">Lifting Beam</option>
                    <option value="Spreader Bar">Spreader Bar</option>
                    <option value="Shackle">Shackle</option>
                    <option value="Wire Rope">Wire Rope</option>
                    <option value="Chain">Chain</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="equipment_description" class="form-label">
                    <i class="fas fa-clipboard-list me-2"></i>Equipment Description
                </label>
                <input type="text" class="form-control" id="equipment_description" name="equipment_description" 
                       value="{{ old('equipment_description') }}" 
                       placeholder="Detailed description of equipment">
                <small class="form-text text-muted">Detailed description of the equipment being inspected</small>
            </div>
            <div class="col-md-6">
                <label for="primaryEquipment" class="form-label">
                    <i class="fas fa-star me-2"></i>Primary Equipment
                </label>
                <input type="text" class="form-control" id="primaryEquipment" name="primary_equipment" 
                       value="{{ old('primary_equipment') }}" 
                       placeholder="Main lifting equipment being inspected">
                <small class="form-text text-muted">The main piece of lifting equipment</small>
            </div>

            <div class="col-md-6">
                <label for="equipmentManufacturer" class="form-label">
                    <i class="fas fa-industry me-2"></i>Manufacturer
                </label>
                <input type="text" class="form-control" id="equipmentManufacturer" name="equipment_manufacturer" 
                       value="{{ old('equipment_manufacturer') }}" 
                       placeholder="Equipment manufacturer">
                <small class="form-text text-muted">Manufacturer of the primary equipment</small>
            </div>

            <div class="col-md-6">
                <label for="equipmentModel" class="form-label">
                    <i class="fas fa-tag me-2"></i>Model Number
                </label>
                <input type="text" class="form-control" id="equipmentModel" name="equipment_model" 
                       value="{{ old('equipment_model') }}" 
                       placeholder="Model number">
                <small class="form-text text-muted">Model number of the equipment</small>
            </div>

            <div class="col-md-6">
                <label for="equipmentSerial" class="form-label">
                    <i class="fas fa-barcode me-2"></i>Serial Number
                </label>
                <input type="text" class="form-control" id="equipmentSerial" name="equipment_serial" 
                       value="{{ old('equipment_serial') }}" 
                       placeholder="Serial number">
                <small class="form-text text-muted">Serial number of the equipment</small>
            </div>

            <div class="col-md-6">
                <label for="equipmentCapacity" class="form-label">
                    <i class="fas fa-weight me-2"></i>Equipment Capacity
                </label>
                <input type="text" class="form-control" id="equipmentCapacity" name="equipment_capacity" 
                       value="{{ old('equipment_capacity') }}" 
                       placeholder="e.g., 50 tonnes">
                <small class="form-text text-muted">Maximum capacity of the equipment</small>
            </div>

            <div class="col-md-6">
                <label for="equipmentYear" class="form-label">
                    <i class="fas fa-calendar me-2"></i>Year of Manufacture
                </label>
                <input type="number" class="form-control" id="equipmentYear" name="equipment_year" 
                       value="{{ old('equipment_year') }}" 
                       placeholder="Year" min="1900" max="{{ date('Y') + 1 }}">
                <small class="form-text text-muted">Year the equipment was manufactured</small>
            </div>

            <div class="col-md-6">
                <label for="lastInspectionDate" class="form-label">
                    <i class="fas fa-calendar-check me-2"></i>Last Inspection Date
                </label>
                <input type="date" class="form-control" id="lastInspectionDate" name="last_inspection_date" 
                       value="{{ old('last_inspection_date') }}">
                <small class="form-text text-muted">Date of the last thorough examination</small>
            </div>

            <div class="col-md-6">
                <label for="nextInspectionDue" class="form-label">
                    <i class="fas fa-calendar-times me-2"></i>Next Inspection Due
                </label>
                <input type="date" class="form-control" id="nextInspectionDue" name="next_inspection_due" 
                       value="{{ old('next_inspection_due') }}">
                <small class="form-text text-muted">Date when next inspection is due</small>
            </div>

            <div class="col-md-6">
                <label for="certificateNumber" class="form-label">
                    <i class="fas fa-certificate me-2"></i>Certificate Number
                </label>
                <input type="text" class="form-control" id="certificateNumber" name="certificate_number" 
                       value="{{ old('certificate_number') }}" 
                       placeholder="Certification number">
                <small class="form-text text-muted">Current valid certificate number</small>
            </div>

            <div class="col-md-6">
                <label for="certifyingBody" class="form-label">
                    <i class="fas fa-building me-2"></i>Certifying Body
                </label>
                <input type="text" class="form-control" id="certifyingBody" name="certifying_body" 
                       value="{{ old('certifying_body') }}" 
                       placeholder="Name of certifying organization">
                <small class="form-text text-muted">Organization that issued the certificate</small>
            </div>

            <!-- Additional Equipment Notes -->
            <div class="col-12">
                <label for="equipmentNotes" class="form-label">
                    <i class="fas fa-sticky-note me-2"></i>Equipment Notes
                </label>
                <textarea class="form-control" id="equipmentNotes" name="equipment_notes" 
                          rows="3" placeholder="Additional notes about the equipment">{{ old('equipment_notes') }}</textarea>
                <small class="form-text text-muted">Any additional information about the equipment condition or specifications</small>
            </div>
        </div>
    </div>
</section>
