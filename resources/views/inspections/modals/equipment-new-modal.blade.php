<!-- Equipment Modal -->
<div class="modal fade" id="equipmentNewModal" tabindex="-1" aria-labelledby="equipmentNewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="equipmentNewModalLabel">
                    <i class="fas fa-tools me-2"></i>Add Equipment Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="equipmentForm">
                    <div class="row g-3">
                        <!-- Equipment Selection Dropdown -->
                        <div class="col-12">
                            <label for="equipment_id" class="form-label fw-bold">Select Equipment <span class="text-muted">(Optional)</span></label>
                            <select class="form-select searchable-dropdown" id="equipment_id" name="equipment_id">
                                <option value="">Choose from database or enter manually below...</option>
                            </select>
                            <div class="form-text">Search and select from existing equipment, or leave blank to enter manually</div>
                        </div>

                        <!-- Equipment Information (Auto-populate or Manual Entry) -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-secondary text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Equipment Information</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="equipment_name" class="form-label">Equipment Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="equipment_name" name="equipment_name" required placeholder="Enter or auto-populated from selection">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_type" class="form-label">Equipment Type</label>
                                            <select class="form-select" id="equipment_type" name="equipment_type">
                                                <option value="">Select Type</option>
                                                <option value="UT Flaw Detector">UT Flaw Detector</option>
                                                <option value="MT Yoke">MT Yoke</option>
                                                <option value="PT Kit">PT Kit</option>
                                                <option value="Thickness Gauge">Thickness Gauge</option>
                                                <option value="Hardness Tester">Hardness Tester</option>
                                                <option value="Borescope">Borescope</option>
                                                <option value="Crane Scale">Crane Scale</option>
                                                <option value="Load Cell">Load Cell</option>
                                                <option value="Wire Rope Tester">Wire Rope Tester</option>
                                                <option value="Multimeter">Multimeter</option>
                                                <option value="Torque Wrench">Torque Wrench</option>
                                                <option value="Pressure Gauge">Pressure Gauge</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_model" class="form-label">Brand/Model</label>
                                            <input type="text" class="form-control" id="equipment_model" name="equipment_model" placeholder="Enter or auto-populated from selection">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_serial" class="form-label">Serial Number</label>
                                            <input type="text" class="form-control" id="equipment_serial" name="equipment_serial" placeholder="Enter or auto-populated from selection">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_calibration_date" class="form-label">Last Calibration Date</label>
                                            <input type="date" class="form-control" id="equipment_calibration_date" name="equipment_calibration_date">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_calibration_due" class="form-label">Calibration Due Date</label>
                                            <input type="date" class="form-control" id="equipment_calibration_due" name="equipment_calibration_due">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_calibration_cert" class="form-label">Calibration Certificate</label>
                                            <input type="text" class="form-control" id="equipment_calibration_cert" name="equipment_calibration_cert" placeholder="Certificate number or reference">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_condition" class="form-label">Equipment Condition</label>
                                            <select class="form-select" id="equipment_condition" name="equipment_condition">
                                                <option value="excellent">Excellent</option>
                                                <option value="good" selected>Good</option>
                                                <option value="fair">Fair</option>
                                                <option value="poor">Poor - Needs Attention</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Assignment -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Service Assignment</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="equipment_assigned_services" class="form-label">Assigned Services <span class="text-danger">*</span></label>
                                            <select class="form-select" id="equipment_assigned_services" name="equipment_assigned_services[]" multiple size="6" required>
                                                <option value="mpi">MPI (Magnetic Particle Inspection)</option>
                                                <option value="load_test">Load Test</option>
                                                <option value="visual_inspection">Visual Inspection</option>
                                                <option value="measurement">Measurement</option>
                                                <option value="calibration">Calibration</option>
                                                <option value="lifting_examination">Lifting Examination</option>
                                                <option value="ultrasonic_testing">Ultrasonic Testing</option>
                                                <option value="penetrant_testing">Penetrant Testing</option>
                                                <option value="radiographic_testing">Radiographic Testing</option>
                                                <option value="hardness_testing">Hardness Testing</option>
                                                <option value="thickness_measurement">Thickness Measurement</option>
                                                <option value="torque_testing">Torque Testing</option>
                                            </select>
                                            <div class="form-text">Hold Ctrl/Cmd to select multiple services. At least one service is required.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usage Information -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-warning text-dark py-2">
                                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Usage Information</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="equipment_usage_hours" class="form-label">Usage Hours</label>
                                            <input type="number" class="form-control" id="equipment_usage_hours" name="equipment_usage_hours" min="0" step="0.1" placeholder="Hours of use for this inspection">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="equipment_operator" class="form-label">Operator</label>
                                            <input type="text" class="form-control" id="equipment_operator" name="equipment_operator" placeholder="Who will operate this equipment">
                                        </div>
                                        <div class="col-12">
                                            <label for="equipment_notes" class="form-label">Maintenance Notes</label>
                                            <textarea class="form-control" id="equipment_notes" name="equipment_notes" rows="3" placeholder="Any maintenance notes, limitations, or special requirements for this equipment"></textarea>
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
                <button type="button" class="btn btn-primary" id="addEquipmentBtn">
                    <i class="fas fa-plus me-2"></i>Add Equipment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize equipment dropdown with searchability if Select2 is available
    if (typeof $.fn.select2 !== 'undefined') {
        $('#equipment_id').select2({
            dropdownParent: $('#equipmentNewModal'),
            placeholder: 'Search equipment...',
            allowClear: true,
            ajax: {
                url: '/inspections/api/equipment',
                dataType: 'json',
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    console.log('Equipment API response:', data); // Debug log
                    return {
                        results: data.map(equipment => ({
                            id: equipment.id,
                            text: `${equipment.equipment_name} - ${equipment.brand_model || 'N/A'} (${equipment.serial_number || 'No S/N'})`,
                            equipment: equipment
                        }))
                    };
                },
                cache: true
            }
        });

        // Handle equipment selection
        $('#equipment_id').on('select2:select', function (e) {
            const equipment = e.params.data.equipment;
            if (equipment) {
                // Auto-populate equipment fields
                $('#equipment_name').val(equipment.equipment_name || '');
                $('#equipment_type').val(equipment.equipment_type || '');
                $('#equipment_model').val(equipment.brand_model || '');
                $('#equipment_serial').val(equipment.serial_number || '');
                $('#equipment_calibration_date').val(equipment.calibration_date || '');
                $('#equipment_calibration_due').val(equipment.calibration_due || '');
                $('#equipment_calibration_cert').val(equipment.calibration_certificate || '');
                $('#equipment_condition').val(equipment.condition || 'good');
                $('#equipment_notes').val(equipment.maintenance_notes || '');
            }
        });

        // Clear fields when selection is cleared
        $('#equipment_id').on('select2:clear', function (e) {
            $('#equipment_name').val('');
            $('#equipment_type').val('');
            $('#equipment_model').val('');
            $('#equipment_serial').val('');
            $('#equipment_calibration_date').val('');
            $('#equipment_calibration_due').val('');
            $('#equipment_calibration_cert').val('');
            $('#equipment_condition').val('good');
            $('#equipment_notes').val('');
        });
    }

    // Add Equipment functionality
    const addEquipmentBtn = document.getElementById('addEquipmentBtn');
    const equipmentModal = new bootstrap.Modal(document.getElementById('equipmentNewModal'));

    addEquipmentBtn.addEventListener('click', function() {
        const form = document.getElementById('equipmentForm');
        const formData = new FormData(form);

        // Validation
        const equipmentName = formData.get('equipment_name');
        const assignedServices = formData.getAll('equipment_assigned_services[]');

        if (!equipmentName.trim()) {
            showAlert('Equipment name is required', 'danger');
            return;
        }

        if (assignedServices.length === 0) {
            showAlert('At least one service must be assigned', 'danger');
            return;
        }

        // Create equipment object
        const equipment = {
            name: equipmentName,
            type: formData.get('equipment_type'),
            model: formData.get('equipment_model'),
            serial: formData.get('equipment_serial'),
            cal_date: formData.get('equipment_calibration_date'),
            cal_due: formData.get('equipment_calibration_due'),
            cal_cert: formData.get('equipment_calibration_cert'),
            condition: formData.get('equipment_condition'),
            services: assignedServices,
            hours: formData.get('equipment_usage_hours'),
            operator: formData.get('equipment_operator'),
            notes: formData.get('equipment_notes')
        };

        // Add to equipment list (this function should be defined in the main inspection form JS)
        if (typeof addEquipmentToList === 'function') {
            addEquipmentToList(equipment);
        } else {
            console.warn('addEquipmentToList function not found');
        }

        // Reset form and close modal
        form.reset();
        if (typeof $.fn.select2 !== 'undefined') {
            $('#equipment_id').val(null).trigger('change');
        }
        equipmentModal.hide();

        showAlert('Equipment added successfully', 'success');
    });
});

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const modalBody = document.querySelector('#equipmentNewModal .modal-body');
    modalBody.insertBefore(alertDiv, modalBody.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
