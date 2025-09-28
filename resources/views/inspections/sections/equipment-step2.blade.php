<!-- Equipment Details Section (Step 2) -->
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
        <!-- Equipment Information Form -->
        <div class="row g-3">
            <div class="col-md-6">
                <label for="equipment_type" class="form-label">Equipment Type *</label>
                <select class="form-select" id="equipment_type" name="equipment_type" required>
                    <option value="">Select Equipment Type</option>
                    @if(isset($equipment) && $equipment->count() > 0)
                        @foreach($equipment as $equip)
                            <option value="{{ $equip->id }}" data-serial="{{ $equip->serial_number }}" data-model="{{ $equip->brand_model }}">
                                {{ $equip->name }} ({{ $equip->type }}) - Serial: {{ $equip->serial_number }}
                            </option>
                        @endforeach
                    @else
                        <option value="Mobile Crane">Mobile Crane</option>
                        <option value="Tower Crane">Tower Crane</option>
                        <option value="Overhead Crane">Overhead Crane</option>
                        <option value="Jib Crane">Jib Crane</option>
                        <option value="Gantry Crane">Gantry Crane</option>
                        <option value="Hoist">Hoist</option>
                        <option value="Winch">Winch</option>
                        <option value="Forklift">Forklift</option>
                        <option value="Lifting Sling">Lifting Sling</option>
                        <option value="Chain Block">Chain Block</option>
                        <option value="Wire Rope">Wire Rope</option>
                        <option value="Shackle">Shackle</option>
                        <option value="Hook">Hook</option>
                        <option value="Other">Other</option>
                    @endif
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="equipment_serial" class="form-label">Serial Number *</label>
                <input type="text" class="form-control" id="equipment_serial" name="equipment_serial" required>
            </div>

            <div class="col-md-6">
                <label for="equipment_manufacturer" class="form-label">Manufacturer</label>
                <input type="text" class="form-control" id="equipment_manufacturer" name="equipment_manufacturer">
            </div>

            <div class="col-md-6">
                <label for="equipment_model" class="form-label">Model</label>
                <input type="text" class="form-control" id="equipment_model" name="equipment_model">
            </div>

            <div class="col-md-6">
                <label for="equipment_cert_date" class="form-label">Last Calibration Date</label>
                <input type="date" class="form-control" id="equipment_cert_date" name="equipment_cert_date">
            </div>

            <div class="col-md-6">
                <label for="equipment_next_due" class="form-label">Next Calibration Due</label>
                <input type="date" class="form-control" id="equipment_next_due" name="equipment_next_due">
            </div>

            <div class="col-md-6">
                <label for="equipment_status" class="form-label">Equipment Status</label>
                <select class="form-select" id="equipment_status" name="equipment_status">
                    <option value="">Select Status</option>
                    <option value="Good">Good</option>
                    <option value="Satisfactory">Satisfactory</option>
                    <option value="Needs Attention">Needs Attention</option>
                    <option value="Out of Service">Out of Service</option>
                </select>
            </div>

            <div class="col-12">
                <label for="equipment_comments" class="form-label">Equipment Comments</label>
                <textarea class="form-control" id="equipment_comments" name="equipment_comments" rows="3" placeholder="Any additional comments about the equipment..."></textarea>
            </div>
        </div>
    </div>
</section>

<script>
// Auto-fill equipment details when selecting from dropdown
document.addEventListener('DOMContentLoaded', function() {
    const equipmentTypeSelect = document.getElementById('equipment_type');
    const serialField = document.getElementById('equipment_serial');
    const modelField = document.getElementById('equipment_model');
    
    if (equipmentTypeSelect) {
        equipmentTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.hasAttribute('data-serial')) {
                serialField.value = selectedOption.getAttribute('data-serial');
                modelField.value = selectedOption.getAttribute('data-model') || '';
            } else {
                serialField.value = '';
                modelField.value = '';
            }
        });
    }
});
</script>