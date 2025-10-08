<!-- Consumables Section -->
<section class="form-section" id="section-consumables">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-box"></i>
            Consumables & Materials
        </h2>
        <p class="section-description">
            Document consumable items and materials used during the inspection.
        </p>
    </div>

    <div class="section-content">
        <!-- Add Consumable Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Consumable Items</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#consumableModal">
                <i class="fas fa-plus me-2"></i>Add Consumable
            </button>
        </div>

        <!-- Consumables Table -->
        <div class="table-responsive">
            <table class="table table-striped" id="consumablesTable">
                <!-- Use light header with dark text for readability -->
                <thead class="bg-light">
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Batch/Lot</th>
                        <th>Manufacturer</th>
                        <th>Condition</th>
                        <th>Services</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="consumablesTableBody">
                    <tr id="noConsumablesRow">
                        <td colspan="9" class="text-center text-muted">
                            <i class="fas fa-box fa-2x mb-2"></i>
                            <br>No consumable items added yet
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Hidden inputs for form submission -->
        <div id="consumableInputs">
            <!-- Hidden inputs will be generated here -->
        </div>

        <!-- Consumables Summary -->
        <div class="consumables-summary mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">Total Items</h6>
                            <h4 class="text-primary mb-0" id="totalConsumables">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">Good Condition</h6>
                            <h4 class="text-success mb-0" id="goodConditionCount">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">Need Attention</h6>
                            <h4 class="text-warning mb-0" id="attentionCount">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize consumables data array
    window.consumablesData = window.consumablesData || [];

    // Helper function to get selected services
    function getSelectedServices() {
        const servicesSelect = document.getElementById('consumable_assigned_services');
        if (!servicesSelect) return '';
        
        const selectedOptions = Array.from(servicesSelect.selectedOptions);
        return selectedOptions.map(option => option.value).join(',');
    }

    // Function to add consumable item
    window.addConsumable = function() {
        // Get form values from the modal
        const consumableSelect = document.getElementById('consumable_select');
        const selectedConsumableId = consumableSelect ? consumableSelect.value : null;
        
        const consumable = {
            consumable_id: selectedConsumableId || null, // Store the database ID if selected
            description: document.getElementById('consumable_description').value,
            quantity: document.getElementById('consumable_quantity').value || 1,
            unit: document.getElementById('consumable_unit').value || '',
            batch_number: document.getElementById('consumable_batch_number').value || '',
            manufacturer: document.getElementById('consumable_manufacturer').value || '',
            product_code: document.getElementById('consumable_product_code').value || '',
            expiry_date: document.getElementById('consumable_expiry_date').value || '',
            cost: document.getElementById('consumable_cost').value || '',
            supplier: document.getElementById('consumable_supplier').value || '',
            condition: document.getElementById('consumable_condition').value || 'new',
            notes: document.getElementById('consumable_notes').value || '',
            assigned_services: getSelectedServices()
        };

        // Validate required fields
        if (!consumable.description) {
            alert('Please fill in the description field');
            return;
        }

        // Add to data array
        window.consumablesData.push(consumable);

        // Update table display
        updateConsumablesTable();

        // Update summary
        updateConsumablesSummary();

        // Clear form
        document.getElementById('newConsumableForm').reset();

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('consumableModal'));
        modal.hide();
    };

    // Function to remove consumable
    window.removeConsumable = function(index) {
        if (confirm('Are you sure you want to remove this consumable item?')) {
            window.consumablesData.splice(index, 1);
            updateConsumablesTable();
            updateConsumablesSummary();
        }
    };

    // Function to update consumables table
    function updateConsumablesTable() {
        const tbody = document.getElementById('consumablesTableBody');
        const noDataRow = document.getElementById('noConsumablesRow');

        if (window.consumablesData.length === 0) {
            noDataRow.style.display = '';
            return;
        }

        noDataRow.style.display = 'none';

        // Clear existing rows (except no data row)
        Array.from(tbody.children).forEach(row => {
            if (row.id !== 'noConsumablesRow') {
                row.remove();
            }
        });

        // Add rows for each consumable
        window.consumablesData.forEach((consumable, index) => {
            const row = document.createElement('tr');
            const isFromDatabase = consumable.consumable_id ? true : false;
            const dbIndicator = isFromDatabase ? '<i class="fas fa-database text-info ms-1" title="From database"></i>' : '<i class="fas fa-plus-circle text-success ms-1" title="New item"></i>';
            
            row.innerHTML = `
                <td>${consumable.description}${dbIndicator}</td>
                <td>${consumable.quantity}</td>
                <td>${consumable.unit}</td>
                <td>${consumable.batch_number}</td>
                <td>${consumable.manufacturer}</td>
                <td>
                    <span class="badge ${getConditionClass(consumable.condition)}">
                        ${consumable.condition}
                    </span>
                </td>
                <td>
                    <small class="text-muted">${formatServices(consumable.assigned_services)}</small>
                </td>
                <td>${consumable.notes}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeConsumable(${index})" title="Remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Update hidden inputs for form submission
        updateConsumableInputs();
    }

    // Function to update hidden inputs
    function updateConsumableInputs() {
        const container = document.getElementById('consumableInputs');
        container.innerHTML = '';

        window.consumablesData.forEach((consumable, index) => {
            const inputs = [
                `<input type="hidden" name="consumables[${index}][consumable_id]" value="${consumable.consumable_id || ''}">`,
                `<input type="hidden" name="consumables[${index}][description]" value="${consumable.description}">`,
                `<input type="hidden" name="consumables[${index}][quantity]" value="${consumable.quantity}">`,
                `<input type="hidden" name="consumables[${index}][unit]" value="${consumable.unit}">`,
                `<input type="hidden" name="consumables[${index}][batch_number]" value="${consumable.batch_number}">`,
                `<input type="hidden" name="consumables[${index}][manufacturer]" value="${consumable.manufacturer}">`,
                `<input type="hidden" name="consumables[${index}][product_code]" value="${consumable.product_code}">`,
                `<input type="hidden" name="consumables[${index}][expiry_date]" value="${consumable.expiry_date}">`,
                `<input type="hidden" name="consumables[${index}][cost]" value="${consumable.cost}">`,
                `<input type="hidden" name="consumables[${index}][supplier]" value="${consumable.supplier}">`,
                `<input type="hidden" name="consumables[${index}][condition]" value="${consumable.condition}">`,
                `<input type="hidden" name="consumables[${index}][notes]" value="${consumable.notes}">`,
                `<input type="hidden" name="consumables[${index}][assigned_services]" value="${consumable.assigned_services || ''}">`
            ];
            container.innerHTML += inputs.join('');
        });
    }

    // Function to update summary cards
    function updateConsumablesSummary() {
        const total = window.consumablesData.length;
        const goodCondition = window.consumablesData.filter(item => item.condition === 'new').length;
        const needsAttention = window.consumablesData.filter(item => 
            item.condition === 'expired' || item.condition === 'damaged'
        ).length;

        document.getElementById('totalConsumables').textContent = total;
        document.getElementById('goodConditionCount').textContent = goodCondition;
        document.getElementById('attentionCount').textContent = needsAttention;
    }

    // Helper function to get condition badge class
    function getConditionClass(condition) {
        switch(condition) {
            case 'new': return 'bg-success';
            case 'used': return 'bg-info';
            case 'expired': return 'bg-warning';
            case 'damaged': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    // Helper function to format services for display
    function formatServices(services) {
        if (!services || services.trim() === '') {
            return 'Auto-assigned';
        }
        
        const serviceArray = services.split(',');
        if (serviceArray.length <= 2) {
            return serviceArray.join(', ');
        } else {
            return serviceArray.slice(0, 2).join(', ') + ` +${serviceArray.length - 2} more`;
        }
    }

    // Initialize summary on page load
    updateConsumablesSummary();
    
    // Add event listener for the modal's save button
    const saveConsumableBtn = document.getElementById('saveConsumableBtn');
    if (saveConsumableBtn) {
        saveConsumableBtn.addEventListener('click', function() {
            window.addConsumable();
        });
    }
});
</script>

<style>
.consumables-summary .card {
    transition: transform 0.2s;
}

.consumables-summary .card:hover {
    transform: translateY(-2px);
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
}

.table td {
    font-size: 0.875rem;
    vertical-align: middle;
}

#consumablesTable .btn {
    padding: 0.25rem 0.5rem;
}
</style>