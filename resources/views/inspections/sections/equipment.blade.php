<!-- Equipment Section -->
<section class="form-section" id="section-equipment">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-tools"></i>
            Equipment
        </h2>
        <p class="section-description">
            Equipment used in the inspection
        </p>
    </div>

    <div class="section-content">
        <!-- No Equipment Message -->
        <div id="noEquipmentMessage" class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-tools text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
            <h5 class="text-muted mb-3">No Equipment Added</h5>
            <p class="text-muted mb-4">Click the button below to add equipment for this inspection.</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#equipmentNewModal">
                <i class="fas fa-plus me-2"></i>
                Add Equipment
            </button>
        </div>

        <!-- Equipment List -->
        <div id="equipmentList" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Equipment List
                </h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#equipmentNewModal">
                    <i class="fas fa-plus me-2"></i>
                    Add Equipment
                </button>
            </div>
            
            <div class="row" id="equipmentCards">
                <!-- Equipment cards will be populated here -->
            </div>
        </div>

        <!-- Hidden inputs for form submission -->
        <div id="equipmentInputsContainer">
            <!-- Hidden inputs will be generated here -->
        </div>
    </div>
</section>

<script>
// Equipment management
let equipmentList = [];
let equipmentCounter = 1;

function addEquipmentToList(equipment) {
    // Add unique ID
    equipment.id = equipmentCounter++;
    
    // Add to list
    equipmentList.push(equipment);
    
    // Update display
    updateEquipmentDisplay();
    updateEquipmentInputs();
}

function removeEquipment(equipmentId) {
    if (confirm('Are you sure you want to remove this equipment?')) {
        equipmentList = equipmentList.filter(eq => eq.id !== equipmentId);
        updateEquipmentDisplay();
        updateEquipmentInputs();
    }
}

function updateEquipmentDisplay() {
    const noEquipmentMessage = document.getElementById('noEquipmentMessage');
    const equipmentListDiv = document.getElementById('equipmentList');
    const equipmentCards = document.getElementById('equipmentCards');

    if (equipmentList.length === 0) {
        noEquipmentMessage.style.display = 'block';
        equipmentListDiv.style.display = 'none';
        return;
    }

    noEquipmentMessage.style.display = 'none';
    equipmentListDiv.style.display = 'block';

    // Clear existing cards
    equipmentCards.innerHTML = '';

    // Generate equipment cards
    equipmentList.forEach(equipment => {
        const servicesText = Array.isArray(equipment.services) 
            ? equipment.services.map(s => s.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())).join(', ')
            : 'No services assigned';

        // Get condition badge class
        let conditionBadgeClass = 'bg-success';
        if (equipment.condition === 'fair') conditionBadgeClass = 'bg-warning';
        else if (equipment.condition === 'poor') conditionBadgeClass = 'bg-danger';

        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4';
        card.innerHTML = `
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-tools me-2 text-primary"></i>
                            ${equipment.name}
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                onclick="removeEquipment(${equipment.id})" title="Remove Equipment">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    ${equipment.type ? `<div class="mb-2"><small class="text-muted"><strong>Type:</strong> ${equipment.type}</small></div>` : ''}
                    ${equipment.model ? `<div class="mb-2"><small class="text-muted"><strong>Model:</strong> ${equipment.model}</small></div>` : ''}
                    ${equipment.serial ? `<div class="mb-2"><small class="text-muted"><strong>Serial:</strong> ${equipment.serial}</small></div>` : ''}
                    
                    <div class="mb-2">
                        <span class="badge ${conditionBadgeClass}">
                            ${equipment.condition ? equipment.condition.toUpperCase() : 'GOOD'}
                        </span>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Services:</strong><br>
                        <small class="text-muted">${servicesText}</small>
                    </div>
                    
                    ${equipment.cal_due ? `
                        <div class="mb-2">
                            <strong>Cal Due:</strong><br>
                            <small class="text-muted">${new Date(equipment.cal_due).toLocaleDateString()}</small>
                        </div>
                    ` : ''}
                    
                    ${equipment.notes ? `
                        <div class="mb-1">
                            <strong>Notes:</strong><br>
                            <small class="text-muted">${equipment.notes}</small>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        equipmentCards.appendChild(card);
    });
}

function updateEquipmentInputs() {
    const container = document.getElementById('equipmentInputsContainer');
    container.innerHTML = '';

    equipmentList.forEach((equipment, index) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `equipment[${index}]`;
        input.value = JSON.stringify(equipment);
        container.appendChild(input);
    });
}

// Populate equipment on edit mode
function populateEquipment(equipmentData) {
    equipmentList = equipmentData || [];
    equipmentCounter = equipmentList.length + 1;
    updateEquipmentDisplay();
    updateEquipmentInputs();
}

// Make function available globally
window.addEquipmentToList = addEquipmentToList;
window.populateEquipment = populateEquipment;
</script>
