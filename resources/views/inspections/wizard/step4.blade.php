{{-- Step 4: Equipment and Items Management --}}
<div class="step-content">
    <div class="alert alert-info">
        <h5><i class="fas fa-tools me-2"></i>Equipment Items Management</h5>
        <p>Manage inspection items with their equipment types and detailed specifications.</p>
    </div>
    
    <!-- Items Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Items
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Items List -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Inspection Items</h6>
                        <button type="button" class="btn btn-success btn-sm" id="addNewRowBtn">
                            <i class="fas fa-plus me-1"></i>Add New Row
                        </button>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-sm" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="min-width: 150px;">Equipment Type</th>
                                    <th style="min-width: 120px;">Serial Number</th>
                                    <th style="min-width: 200px;">Description and Identification</th>
                                    <th style="min-width: 100px;">SWL</th>
                                    <th style="min-width: 120px;">Test Load Applied</th>
                                    <th style="min-width: 120px;">Reason of Examination</th>
                                    <th style="min-width: 140px;">Date of Manufacture</th>
                                    <th style="min-width: 140px;">Date of Last Examination</th>
                                    <th style="min-width: 140px;">Date of Next Examination</th>
                                    <th style="min-width: 100px;">Status</th>
                                    <th style="min-width: 80px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Default editable row -->
                                <tr data-row-id="row_default">
                                    <td>
                                        <select class="form-select form-select-sm" data-field="equipment_type">
                                            <option value="">Select Equipment Type</option>
                                            <option value="Gas Rack">Gas Rack</option>
                                            <option value="Wire Rope">Wire Rope</option>
                                            <option value="Bow Shackle">Bow Shackle</option>
                                            <option value="Chain Sling">Chain Sling</option>
                                            <option value="Lifting Frame">Lifting Frame</option>
                                            <option value="Offshore Container">Offshore Container</option>
                                            <option value="Safety Pin">Safety Pin</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" data-field="serial_number" placeholder="Serial Number">
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm" data-field="description" rows="2" placeholder="Description and Identification"></textarea>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control form-control-sm" data-field="swl" placeholder="SWL (kg/tons)">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control form-control-sm" data-field="test_load_applied" placeholder="Test Load (kg/tons)">
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" data-field="reason_for_examination">
                                            <option value="">Select Reason</option>
                                            <option value="C">C</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm" data-field="date_of_manufacture">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm" data-field="date_of_last_examination">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm" data-field="date_of_next_examination">
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" data-field="status">
                                            <option value="">Select Status</option>
                                            <option value="ND">ND (No Defect)</option>
                                            <option value="D">D (Defect)</option>
                                            <option value="NI">NI (Not Inspected)</option>
                                            <option value="R">R (Requires Further Inspection)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTableRow('row_default')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
/* Equipment table styling */
.equipment-table-container {
    max-height: 400px;
    overflow-y: auto;
}

.table th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 1;
}



/* Responsive table styling */
</style>

<script>
// Define functions at global scope immediately
window.addNewEditableRow = function() {
    var tableBody = document.getElementById('itemsTableBody');
    if (!tableBody) {
        console.error('Table body not found!');
        return;
    }
    
    var rowId = 'row_' + Date.now();
    var row = document.createElement('tr');
    row.setAttribute('data-row-id', rowId);
    
    // Use simple string concatenation instead of template literals
    row.innerHTML = '<td>' +
        '<select class="form-select form-select-sm" data-field="equipment_type">' +
        '<option value="">Select Equipment Type</option>' +
        '<option value="Gas Rack">Gas Rack</option>' +
        '<option value="Wire Rope">Wire Rope</option>' +
        '<option value="Bow Shackle">Bow Shackle</option>' +
        '<option value="Chain Sling">Chain Sling</option>' +
        '<option value="Lifting Frame">Lifting Frame</option>' +
        '<option value="Offshore Container">Offshore Container</option>' +
        '<option value="Safety Pin">Safety Pin</option>' +
        '<option value="Other">Other</option>' +
        '</select>' +
        '</td>' +
        '<td><input type="text" class="form-control form-control-sm" data-field="serial_number" placeholder="Serial Number"></td>' +
        '<td><textarea class="form-control form-control-sm" data-field="description" rows="2" placeholder="Description"></textarea></td>' +
        '<td><input type="number" step="0.01" min="0" class="form-control form-control-sm" data-field="swl" placeholder="SWL (kg/tons)"></td>' +
        '<td><input type="number" step="0.01" min="0" class="form-control form-control-sm" data-field="test_load_applied" placeholder="Test Load (kg/tons)"></td>' +
        '<td>' +
        '<select class="form-select form-select-sm" data-field="reason_for_examination">' +
        '<option value="">Select Reason</option>' +
        '<option value="C">C</option>' +
        '<option value="A">A</option>' +
        '<option value="B">B</option>' +
        '<option value="D">D</option>' +
        '<option value="E">E</option>' +
        '</select>' +
        '</td>' +
        '<td><input type="date" class="form-control form-control-sm" data-field="date_of_manufacture"></td>' +
        '<td><input type="date" class="form-control form-control-sm" data-field="date_of_last_examination"></td>' +
        '<td><input type="date" class="form-control form-control-sm" data-field="date_of_next_examination"></td>' +
        '<td>' +
        '<select class="form-select form-select-sm" data-field="status">' +
        '<option value="">Select Status</option>' +
        '<option value="ND">ND (No Defect)</option>' +
        '<option value="D">D (Defect)</option>' +
        '<option value="NI">NI (Not Inspected)</option>' +
        '<option value="R">R (Requires Further Inspection)</option>' +
        '</select>' +
        '</td>' +
        '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeTableRow(\'' + rowId + '\')"><i class="fas fa-trash"></i></button></td>';
    
    tableBody.appendChild(row);
    console.log('New equipment row added successfully');
};

window.removeTableRow = function(rowId) {
    var row = document.querySelector('tr[data-row-id="' + rowId + '"]');
    if (row) {
        row.remove();
    }
    
    var tableBody = document.getElementById('itemsTableBody');
    if (tableBody && tableBody.children.length === 0) {
        window.addNewEditableRow();
    }
};

// Also create regular function definitions for compatibility
function addNewEditableRow() {
    window.addNewEditableRow();
}

function removeTableRow(rowId) {
    window.removeTableRow(rowId);
}

// Equipment Items Management
document.addEventListener('DOMContentLoaded', function() {
    console.log('[step4] script loaded');
    window.step4Ready = true;
    const inspectionId = getInspectionId();
    const clientId = getClientId();
    
    if (inspectionId) {
        window.currentInspectionId = inspectionId;
    }
    if (clientId) {
        window.currentClientId = clientId;
    }
    
    // Initialize staged items store and Save & Continue hook
    window.stagedItems = [];
    initializeSaveAndContinue();
    
    loadItems();
    
    // Handle add new row button
    const addRowBtn = document.getElementById('addNewRowBtn');
    if (addRowBtn) {
        addRowBtn.addEventListener('click', function() {
            addNewEditableRow();
        });
    }
});

// Function to collect data from all table rows
function collectTableData() {
    const rows = document.querySelectorAll('#itemsTableBody tr[data-row-id]');
    const items = [];
    
    console.log('Found rows:', rows.length);
    
    rows.forEach(row => {
        const item = {
            temp_id: row.getAttribute('data-row-id'),
            category: 'item'
        };
        
        // Collect data from all input fields in this row
        const fields = row.querySelectorAll('[data-field]');
        console.log('Found fields in row:', fields.length);
        
        fields.forEach(field => {
            const fieldName = field.getAttribute('data-field');
            let value = field.value;
            
            // Validate numeric fields
            if (fieldName === 'swl' || fieldName === 'test_load_applied') {
                if (value && isNaN(parseFloat(value))) {
                    console.warn(`Invalid numeric value for ${fieldName}: ${value}. Setting to empty.`);
                    value = '';
                }
            }
            
            item[fieldName] = value;
        });
        
        console.log('Collected item data:', item);
        
        // Include all rows that have any data (relaxed validation for debugging)
        if (item.equipment_type || item.serial_number || item.description) {
            items.push(item);
            console.log('Added item to collection');
        } else {
            console.log('Item filtered out - no key data');
        }
    });
    
    console.log('Final collected items:', items);
    return items;
}

// Initialize Save and Continue functionality
function initializeSaveAndContinue() {
    // This function will be called to set up the save behavior
    // The actual save will be triggered when user clicks Save & Continue
}

// Function to save all table data to database (called by Save & Continue)
window.step4SaveAndContinue = function() {
    console.log('=== STEP 4 SAVE AND CONTINUE CALLED ===');
    
    // Collect current data from the editable table
    const items = collectTableData();
    console.log('Collected equipment items for save:', items);
    console.log('Items count:', items ? items.length : 0);
    
    // Log collected data for verification
    console.log('Collected equipment items:', items);
    console.log('Items count:', items ? items.length : 0);
    
    // Add equipment data to the main wizard form as a hidden input
    let equipmentDataInput = document.querySelector('input[name="equipment_data"]');
    if (!equipmentDataInput) {
        equipmentDataInput = document.createElement('input');
        equipmentDataInput.type = 'hidden';
        equipmentDataInput.name = 'equipment_data';
        
        // Find the wizard form and add the input
        const wizardForm = document.querySelector('form[action*="wizard/save"]') || document.querySelector('form[method="POST"]');
        console.log('Found wizard form:', wizardForm ? 'YES' : 'NO');
        if (wizardForm) {
            wizardForm.appendChild(equipmentDataInput);
            console.log('Added equipment_data input to form');
        } else {
            console.error('Could not find wizard form to add equipment data!');
            alert('ERROR: Could not find wizard form to add equipment data!');
            return false;
        }
    }
    
    // Set the equipment data as JSON string (always, even if empty)
    if (equipmentDataInput) {
        const jsonData = items && items.length > 0 ? JSON.stringify(items) : '[]';
        equipmentDataInput.value = jsonData;
        console.log('Equipment data set in form input:', jsonData);
        
        // Show collected data in console for verification
        console.log('Equipment data prepared for submission:', jsonData);
        
        // Show detailed debug info
        console.log('=== DETAILED DEBUG INFO ===');
        console.log('Items collected:', items);
        console.log('JSON data length:', jsonData.length);
        console.log('Form input element:', equipmentDataInput);
        console.log('Form input value preview:', jsonData.substring(0, 500));
        
        // Log data summary for debugging
        const summary = items && items.length > 0 ? 
            `Collected ${items.length} items` : 
            'No items collected';
        console.log('EQUIPMENT DATA SUMMARY:', summary);
        
        return true; // Allow form submission
        
    } else {
        console.error('Could not create equipment_data input!');
        alert('ERROR: Could not create equipment_data input!');
        return false;
    }
    
    // Return success - the actual saving will be handled by the wizard form submission
    console.log('=== STEP 4 SAVE FUNCTION COMPLETE ===');
    return Promise.resolve(true);
}



function getInspectionId() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('inspection_id')) {
        return urlParams.get('inspection_id');
    }
    
    const element = document.querySelector('[data-inspection-id]');
    if (element) {
        return element.dataset.inspectionId;
    }
    
    const pathMatch = window.location.pathname.match(/\/inspections\/(\d+)/);
    if (pathMatch) {
        return pathMatch[1];
    }
    
    return null;
}

function getClientId() {
    const element = document.querySelector('[data-client-id]');
    if (element) {
        return element.dataset.clientId;
    }
    
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('client_id')) {
        return urlParams.get('client_id');
    }
    
    return null;
}

// Items Management
function loadItems() {
    const inspectionId = window.currentInspectionId || getInspectionId();
    if (!inspectionId) {
        console.log('No inspection ID available for items loading');
        return;
    }

    fetch(`/api/inspection-equipment?inspection_id=${inspectionId}&category=item`)
        .then(response => response.json())
        .then(data => {
            const list = Array.isArray(data) ? data : (data?.data || []);
            window.lastLoadedItems = list;
            renderItemsTable(list);
        })
        .catch(error => {
            console.error('Error loading items:', error);
        });
}

function renderItemsTable(persistedItems) {
    const tbody = document.getElementById('itemsTableBody');
    const noItemsRow = document.getElementById('noItemsRow');
    if (!tbody || !noItemsRow) return;

    // Clear rows except placeholder
    const existingRows = tbody.querySelectorAll('tr:not(#noItemsRow)');
    existingRows.forEach(row => row.remove());

    const staged = Array.isArray(window.stagedItems) ? window.stagedItems : [];
    const hasAny = (persistedItems && persistedItems.length) || staged.length;
    noItemsRow.style.display = hasAny ? 'none' : '';

    // Render persisted items first
    (persistedItems || []).forEach(item => appendItemRow(item, false));
    // Render staged items
    staged.forEach((item, idx) => appendItemRow({ ...item, _stagedIndex: idx }, true));
}

function appendItemRow(item, isStaged) {
    const tbody = document.getElementById('itemsTableBody');
    if (!tbody) return;

    const row = document.createElement('tr');
    if (isStaged) {
        row.classList.add('table-warning');
        row.setAttribute('data-staged-index', item._stagedIndex);
    }

    const deleteParam = isStaged ? `'__s${item._stagedIndex}'` : (item.id ?? 'null');

    row.innerHTML = `
        <td>${item.equipment_type || '-'}</td>
        <td>${item.serial_number || '-'}</td>
        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">${item.description || '-'}</td>
        <td>${item.swl || '-'}</td>
        <td>${item.test_load_applied || '-'}</td>
        <td>${item.reason_for_examination || '-'}</td>
        <td>${item.date_of_manufacture || '-'}</td>
        <td>${item.date_of_last_examination || '-'}</td>
        <td>${item.date_of_next_examination || '-'}</td>
        <td>
            <span class="badge bg-${getStatusColor(item.status)}">${item.status || 'unknown'}</span>
            ${isStaged ? '<span class="ms-2 badge bg-secondary">staged</span>' : ''}
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.step4DeleteItem(${deleteParam})" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}



window.step4DeleteItem = function(itemId) {
    // Staged item id is like "__s0"
    if (typeof itemId === 'string' && itemId.startsWith('__s')) {
        const idx = parseInt(itemId.replace('__s', ''), 10);
        if (!isNaN(idx) && Array.isArray(window.stagedItems)) {
            window.stagedItems.splice(idx, 1);
            renderItemsTable(window.lastLoadedItems || []);
            return;
        }
    }

    if (!confirm('Are you sure you want to delete this item?')) {
        return;
    }

    fetch(`/api/inspection-equipment/${itemId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh persisted items
            loadItems();
        } else {
            alert('Error deleting item: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error deleting item:', error);
        alert('Error deleting item');
    });
}

function getStatusColor(status) {
    switch(status) {
        case 'ND': return 'success';
        case 'D': return 'danger';
        case 'NI': return 'warning';
        case 'R': return 'info';
        default: return 'secondary';
    }
}

function initializeSaveAndContinue() {
    const btn = document.getElementById('saveContinueBtn') || document.querySelector('[data-action="save-continue"]');
    if (!btn) return;
    btn.addEventListener('click', async function(e) {
        // If this is inside a form submit, intercept first
        if (e) { e.preventDefault?.(); e.stopPropagation?.(); }

        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

        // Use the table-based saving function instead of staged items
        const saveSuccess = window.step4SaveAndContinue();

        btn.disabled = false;
        btn.innerHTML = originalText;

        // Check if save was successful
        console.log('Save function returned:', saveSuccess);
        
        // Proceed if save was successful
        if (saveSuccess) {
            // Proceed with default action (submit or navigate) if applicable
            const parentForm = btn.closest('form');
            if (parentForm) {
                parentForm.submit();
            } else {
                const href = btn.getAttribute('href');
                if (href) {
                    window.location.href = href;
                }
            }
        } else {
            console.log('Form submission prevented - Save failed');
        }
    });
}

// Add form submit handler for Step 4
document.addEventListener('DOMContentLoaded', function() {
    console.log('Step 4 DOM loaded, setting up form handler');
    
    const wizardForm = document.getElementById('wizardForm');
    if (wizardForm) {
        console.log('Found wizard form, adding submit listener');
        
        wizardForm.addEventListener('submit', function(e) {
            console.log('Form submit intercepted on Step 4');
            
            // Check if we're on step 4
            const stepInput = document.querySelector('input[name="step"]');
            const currentStep = stepInput ? stepInput.value : '';
            
            console.log('Current step:', currentStep);
            
            if (currentStep == '4') {
                console.log('Processing Step 4 equipment data');
                e.preventDefault(); // Prevent normal submission
                
                // Call our Step 4 function
                const success = window.step4SaveAndContinue();
                console.log('Step 4 function returned:', success);
                
                if (success) {
                    console.log('Step 4 processing successful, submitting form');
                    // Remove this event listener to prevent infinite loop
                    wizardForm.removeEventListener('submit', arguments.callee);
                    // Submit the form normally
                    wizardForm.submit();
                } else {
                    console.log('Step 4 processing failed');
                    alert('Error processing equipment data. Please check your entries.');
                }
            }
        });
    } else {
        console.error('Wizard form not found!');
    }
});

</script>