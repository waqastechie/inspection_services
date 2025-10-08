{{-- Items Table Section --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Items Table
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Manage individual items or components being inspected. Each item can have its own condition assessment and results.
                </p>
                
                {{-- Item Management Buttons --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Inspection Items</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#itemModal">
                        <i class="fas fa-plus me-1"></i>Add Item
                    </button>
                </div>

                {{-- Items Display --}}
                <div id="itemList" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Reference</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Condition</th>
                                    <th>Result</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody">
                                {{-- Item rows will be populated by JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- No Items Message --}}
                <div id="noItemMessage" class="alert alert-info text-center">
                    <i class="fas fa-list fa-2x mb-2"></i>
                    <p class="mb-0">No items have been added yet.</p>
                    <small class="text-muted">Click "Add Item" to register components or parts for inspection.</small>
                </div>

                {{-- Hidden inputs for item data --}}
                <div id="itemInputsContainer">
                    {{-- Hidden inputs will be populated by JavaScript --}}
                </div>

                {{-- Existing Items (for edit mode) --}}
                @if(isset($inspection) && $inspection->inspectionEquipment)
                    @foreach($inspection->inspectionEquipment->where('category', 'item') as $index => $item)
                        <input type="hidden" name="items[{{ $index }}][reference]" value="{{ $item->equipment_type }}">
                        <input type="hidden" name="items[{{ $index }}][description]" value="{{ $item->description }}">
                        <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item->quantity ?? 1 }}">
                        <input type="hidden" name="items[{{ $index }}][condition]" value="{{ $item->condition }}">
                        <input type="hidden" name="items[{{ $index }}][result]" value="{{ $item->test_result ?? 'pending' }}">
                        <input type="hidden" name="items[{{ $index }}][remarks]" value="{{ $item->notes }}">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Item Modal --}}
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">
                    <i class="fas fa-list me-2"></i>Add Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="row">
                        {{-- Item Reference --}}
                        <div class="col-md-6 mb-3">
                            <label for="item_ref" class="form-label">
                                <i class="fas fa-tag me-1"></i>Item Reference <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="item_ref" name="item_ref" 
                                   placeholder="e.g., BOLT-001" required>
                            <div class="form-text">Unique identifier for the item</div>
                        </div>

                        {{-- Item Description --}}
                        <div class="col-md-6 mb-3">
                            <label for="item_description" class="form-label">
                                <i class="fas fa-file-text me-1"></i>Description <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="item_description" name="item_description" 
                                   placeholder="e.g., High Tensile Bolt M20" required>
                            <div class="form-text">Detailed description of the item</div>
                        </div>

                        {{-- Quantity --}}
                        <div class="col-md-6 mb-3">
                            <label for="item_quantity" class="form-label">
                                <i class="fas fa-sort-numeric-up me-1"></i>Quantity
                            </label>
                            <input type="number" class="form-control" id="item_quantity" name="item_quantity" 
                                   min="1" value="1" placeholder="1">
                            <div class="form-text">Number of items</div>
                        </div>

                        {{-- Condition --}}
                        <div class="col-md-6 mb-3">
                            <label for="item_condition" class="form-label">
                                <i class="fas fa-clipboard-check me-1"></i>Condition
                            </label>
                            <select class="form-select" id="item_condition" name="item_condition">
                                <option value="">Select Condition</option>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="damaged">Damaged</option>
                                <option value="worn">Worn</option>
                                <option value="corroded">Corroded</option>
                                <option value="cracked">Cracked</option>
                                <option value="deformed">Deformed</option>
                            </select>
                            <div class="form-text">Current condition of the item</div>
                        </div>

                        {{-- Test Result --}}
                        <div class="col-md-6 mb-3">
                            <label for="item_result" class="form-label">
                                <i class="fas fa-check-circle me-1"></i>Test Result
                            </label>
                            <select class="form-select" id="item_result" name="item_result">
                                <option value="pending">Pending</option>
                                <option value="pass">Pass</option>
                                <option value="fail">Fail</option>
                                <option value="conditional">Conditional Pass</option>
                                <option value="not_tested">Not Tested</option>
                                <option value="requires_attention">Requires Attention</option>
                            </select>
                            <div class="form-text">Inspection result for this item</div>
                        </div>

                        {{-- Location --}}
                        <div class="col-md-6 mb-3">
                            <label for="item_location" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Location
                            </label>
                            <input type="text" class="form-control" id="item_location" name="item_location" 
                                   placeholder="e.g., Section A, Position 3">
                            <div class="form-text">Physical location of the item</div>
                        </div>

                        {{-- Remarks --}}
                        <div class="col-12 mb-3">
                            <label for="item_remarks" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Remarks
                            </label>
                            <textarea class="form-control" id="item_remarks" name="item_remarks" rows="3" 
                                      placeholder="Additional notes or observations about this item..."></textarea>
                            <div class="form-text">Any additional information about the item</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveItem()">
                    <i class="fas fa-save me-1"></i>Save Item
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Item Management --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize item management
    if (typeof modalItems === 'undefined') {
        window.modalItems = [];
    }
    
    // Load existing items for edit mode
    @if(isset($inspection) && $inspection->inspectionEquipment)
        @foreach($inspection->inspectionEquipment->where('category', 'item') as $item)
            modalItems.push({
                id: {{ $item->id }},
                reference: '{{ $item->equipment_type }}',
                description: '{{ $item->description }}',
                quantity: {{ $item->quantity ?? 1 }},
                condition: '{{ $item->condition }}',
                result: '{{ $item->test_result ?? 'pending' }}',
                location: '{{ $item->location ?? '' }}',
                remarks: '{{ $item->notes ?? '' }}'
            });
        @endforeach
        updateItemDisplay();
    @endif
    
    // Auto-format item reference to uppercase
    document.getElementById('item_ref').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Validate quantity input
    document.getElementById('item_quantity').addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1) {
            this.value = 1;
        }
    });
});

// Save item function (if not already defined)
if (typeof saveItem === 'undefined') {
    function saveItem() {
        const modal = document.getElementById('itemModal');
        const form = modal.querySelector('form');
        
        // Get form data
        const formData = new FormData(form);
        const item = {
            id: Date.now(),
            reference: formData.get('item_ref'),
            description: formData.get('item_description'),
            quantity: parseInt(formData.get('item_quantity')) || 1,
            condition: formData.get('item_condition'),
            result: formData.get('item_result'),
            location: formData.get('item_location'),
            remarks: formData.get('item_remarks')
        };
        
        // Validation
        if (!item.reference || !item.description) {
            alert('Please fill in required fields (Reference and Description)');
            return false;
        }
        
        // Add to array
        modalItems.push(item);
        
        // Update display
        updateItemDisplay();
        
        // Clear form and close modal
        form.reset();
        const bsModal = bootstrap.Modal.getInstance(modal);
        bsModal.hide();
        
        if (typeof showAlert === 'function') {
            showAlert('Item added successfully!', 'success');
        }
        return true;
    }
}

// Update item display function (if not already defined)
if (typeof updateItemDisplay === 'undefined') {
    function updateItemDisplay() {
        const noItemMessage = document.getElementById('noItemMessage');
        const itemList = document.getElementById('itemList');
        const itemTableBody = document.getElementById('itemTableBody');
        const itemInputsContainer = document.getElementById('itemInputsContainer');
        
        if (modalItems.length === 0) {
            if (noItemMessage) noItemMessage.style.display = 'block';
            if (itemList) itemList.style.display = 'none';
            if (itemInputsContainer) itemInputsContainer.innerHTML = '';
            return;
        }
        
        if (noItemMessage) noItemMessage.style.display = 'none';
        if (itemList) itemList.style.display = 'block';
        
        // Update table rows
        if (itemTableBody) {
            itemTableBody.innerHTML = modalItems.map((item, index) => `
                <tr>
                    <td><strong>${item.reference}</strong></td>
                    <td>${item.description}</td>
                    <td>${item.quantity}</td>
                    <td>
                        <span class="badge bg-${getConditionBadgeColor(item.condition)}">
                            ${item.condition || 'Not specified'}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-${getResultBadgeColor(item.result)}">
                            ${item.result || 'Pending'}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editItem(${index})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})" title="Remove">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }
        
        // Update hidden inputs
        if (itemInputsContainer) {
            itemInputsContainer.innerHTML = modalItems.map((item, index) => `
                <input type="hidden" name="items[${index}][reference]" value="${item.reference}">
                <input type="hidden" name="items[${index}][description]" value="${item.description}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${index}][condition]" value="${item.condition || ''}">
                <input type="hidden" name="items[${index}][result]" value="${item.result || ''}">
                <input type="hidden" name="items[${index}][location]" value="${item.location || ''}">
                <input type="hidden" name="items[${index}][remarks]" value="${item.remarks || ''}">
            `).join('');
        }
    }
}

// Helper functions for badge colors
function getConditionBadgeColor(condition) {
    switch(condition) {
        case 'excellent': return 'success';
        case 'good': return 'primary';
        case 'fair': return 'warning';
        case 'poor': case 'damaged': case 'cracked': case 'deformed': return 'danger';
        case 'worn': case 'corroded': return 'warning';
        default: return 'secondary';
    }
}

function getResultBadgeColor(result) {
    switch(result) {
        case 'pass': return 'success';
        case 'fail': return 'danger';
        case 'conditional': return 'warning';
        case 'requires_attention': return 'warning';
        case 'not_tested': return 'secondary';
        default: return 'info';
    }
}

// Edit and remove functions
function editItem(index) {
    const item = modalItems[index];
    const modal = document.getElementById('itemModal');
    const form = modal.querySelector('form');
    
    // Populate form
    form.item_ref.value = item.reference;
    form.item_description.value = item.description;
    form.item_quantity.value = item.quantity;
    form.item_condition.value = item.condition || '';
    form.item_result.value = item.result || '';
    form.item_location.value = item.location || '';
    form.item_remarks.value = item.remarks || '';
    
    // Remove from array (will be re-added when saved)
    modalItems.splice(index, 1);
    updateItemDisplay();
    
    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function removeItem(index) {
    if (confirm('Are you sure you want to remove this item?')) {
        modalItems.splice(index, 1);
        updateItemDisplay();
        if (typeof showAlert === 'function') {
            showAlert('Item removed successfully', 'info');
        }
    }
}
</script>