{{-- Asset Details Section --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-cube me-2"></i>Asset Details
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Manage assets associated with this inspection. Assets represent equipment, components, or items being examined.
                </p>
                
                {{-- Asset Management Buttons --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Registered Assets</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assetModal">
                        <i class="fas fa-plus me-1"></i>Add Asset
                    </button>
                </div>

                {{-- Assets Display --}}
                <div id="assetList" style="display: none;">
                    <div class="row" id="assetCards">
                        {{-- Asset cards will be populated by JavaScript --}}
                    </div>
                </div>

                {{-- No Assets Message --}}
                <div id="noAssetMessage" class="alert alert-info text-center">
                    <i class="fas fa-cube fa-2x mb-2"></i>
                    <p class="mb-0">No assets have been added yet.</p>
                    <small class="text-muted">Click "Add Asset" to register equipment or components for this inspection.</small>
                </div>

                {{-- Hidden inputs for asset data --}}
                <div id="assetInputsContainer">
                    {{-- Hidden inputs will be populated by JavaScript --}}
                </div>

                {{-- Existing Assets (for edit mode) --}}
                @if(isset($inspection) && $inspection->inspectionEquipment)
                    @foreach($inspection->inspectionEquipment->where('category', 'asset') as $index => $asset)
                        <input type="hidden" name="assets[{{ $index }}][reference]" value="{{ $asset->equipment_type }}">
                        <input type="hidden" name="assets[{{ $index }}][description]" value="{{ $asset->description }}">
                        <input type="hidden" name="assets[{{ $index }}][location]" value="{{ $asset->location ?? '' }}">
                        <input type="hidden" name="assets[{{ $index }}][capacity]" value="{{ $asset->swl }}">
                        <input type="hidden" name="assets[{{ $index }}][last_examined]" value="{{ $asset->last_calibration_date }}">
                        <input type="hidden" name="assets[{{ $index }}][next_due]" value="{{ $asset->next_calibration_date }}">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Asset Modal --}}
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel">
                    <i class="fas fa-cube me-2"></i>Add Asset
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assetForm">
                    <div class="row">
                        {{-- Asset Reference --}}
                        <div class="col-md-6 mb-3">
                            <label for="asset_ref" class="form-label">
                                <i class="fas fa-tag me-1"></i>Asset Reference <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="asset_ref" name="asset_ref" 
                                   placeholder="e.g., GAS-RACK-001" required>
                            <div class="form-text">Unique identifier for the asset</div>
                        </div>

                        {{-- Asset Description --}}
                        <div class="col-md-6 mb-3">
                            <label for="asset_description" class="form-label">
                                <i class="fas fa-file-text me-1"></i>Description
                            </label>
                            <input type="text" class="form-control" id="asset_description" name="asset_description" 
                                   placeholder="e.g., Gas Rack Assembly">
                            <div class="form-text">Brief description of the asset</div>
                        </div>

                        {{-- Asset Location --}}
                        <div class="col-md-6 mb-3">
                            <label for="asset_location" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Location
                            </label>
                            <input type="text" class="form-control" id="asset_location" name="asset_location" 
                                   placeholder="e.g., Platform A, Deck 3">
                            <div class="form-text">Physical location of the asset</div>
                        </div>

                        {{-- Capacity --}}
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">
                                <i class="fas fa-weight-hanging me-1"></i>Capacity/Rating
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="capacity" name="capacity" 
                                       placeholder="e.g., 5000">
                                <select class="form-select" name="capacity_unit" style="max-width: 100px;">
                                    <option value="kg">kg</option>
                                    <option value="tonnes">tonnes</option>
                                    <option value="lbs">lbs</option>
                                    <option value="kN">kN</option>
                                </select>
                            </div>
                            <div class="form-text">Maximum capacity or rating</div>
                        </div>

                        {{-- Last Examined --}}
                        <div class="col-md-6 mb-3">
                            <label for="last_examined" class="form-label">
                                <i class="fas fa-calendar-check me-1"></i>Last Examined
                            </label>
                            <input type="date" class="form-control" id="last_examined" name="last_examined">
                            <div class="form-text">Date of last inspection</div>
                        </div>

                        {{-- Next Due --}}
                        <div class="col-md-6 mb-3">
                            <label for="next_due" class="form-label">
                                <i class="fas fa-calendar-times me-1"></i>Next Due
                            </label>
                            <input type="date" class="form-control" id="next_due" name="next_due">
                            <div class="form-text">Next inspection due date</div>
                        </div>

                        {{-- Asset Type --}}
                        <div class="col-md-6 mb-3">
                            <label for="asset_type" class="form-label">
                                <i class="fas fa-cogs me-1"></i>Asset Type
                            </label>
                            <select class="form-select" id="asset_type" name="asset_type">
                                <option value="">Select Asset Type</option>
                                <option value="lifting_equipment">Lifting Equipment</option>
                                <option value="rigging_equipment">Rigging Equipment</option>
                                <option value="safety_equipment">Safety Equipment</option>
                                <option value="structural_component">Structural Component</option>
                                <option value="mechanical_component">Mechanical Component</option>
                                <option value="electrical_component">Electrical Component</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="form-text">Category of the asset</div>
                        </div>

                        {{-- Condition --}}
                        <div class="col-md-6 mb-3">
                            <label for="asset_condition" class="form-label">
                                <i class="fas fa-clipboard-check me-1"></i>Current Condition
                            </label>
                            <select class="form-select" id="asset_condition" name="asset_condition">
                                <option value="">Select Condition</option>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="unknown">Unknown</option>
                            </select>
                            <div class="form-text">Current condition assessment</div>
                        </div>

                        {{-- Notes --}}
                        <div class="col-12 mb-3">
                            <label for="asset_notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes
                            </label>
                            <textarea class="form-control" id="asset_notes" name="asset_notes" rows="3" 
                                      placeholder="Additional notes about this asset..."></textarea>
                            <div class="form-text">Any additional information about the asset</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveAsset()">
                    <i class="fas fa-save me-1"></i>Save Asset
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Asset Management --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize asset management
    if (typeof modalAssets === 'undefined') {
        window.modalAssets = [];
    }
    
    // Load existing assets for edit mode
    @if(isset($inspection) && $inspection->inspectionEquipment)
        @foreach($inspection->inspectionEquipment->where('category', 'asset') as $asset)
            modalAssets.push({
                reference: '{{ $asset->equipment_type }}',
                description: '{{ $asset->description }}',
                location: '{{ $asset->location ?? '' }}',
                capacity: '{{ $asset->swl }}',
                lastExamined: '{{ $asset->last_calibration_date }}',
                nextDue: '{{ $asset->next_calibration_date }}',
                type: '{{ $asset->equipment_type }}',
                condition: '{{ $asset->condition ?? 'good' }}',
                notes: '{{ $asset->notes ?? '' }}'
            });
        @endforeach
        updateAssetDisplay();
    @endif
    
    // Auto-calculate next due date based on last examined
    document.getElementById('last_examined').addEventListener('change', function() {
        const lastExamined = new Date(this.value);
        if (lastExamined) {
            // Default to 12 months from last examination
            const nextDue = new Date(lastExamined);
            nextDue.setFullYear(nextDue.getFullYear() + 1);
            document.getElementById('next_due').value = nextDue.toISOString().split('T')[0];
        }
    });
    
    // Validate capacity input
    document.getElementById('capacity').addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value < 0) {
            this.value = '';
            showAlert('Capacity cannot be negative', 'warning');
        }
    });
    
    // Auto-format asset reference to uppercase
    document.getElementById('asset_ref').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});

// Save asset function (if not already defined)
if (typeof saveAsset === 'undefined') {
    function saveAsset() {
        const modal = document.getElementById('assetModal');
        const form = modal.querySelector('form');
        
        // Get form data
        const formData = new FormData(form);
        const asset = {
            reference: formData.get('asset_ref'),
            description: formData.get('asset_description'),
            location: formData.get('asset_location'),
            capacity: formData.get('capacity') + ' ' + formData.get('capacity_unit'),
            lastExamined: formData.get('last_examined'),
            nextDue: formData.get('next_due'),
            type: formData.get('asset_type'),
            condition: formData.get('asset_condition'),
            notes: formData.get('asset_notes')
        };
        
        // Validation
        if (!asset.reference) {
            alert('Please fill in the Asset Reference field');
            return false;
        }
        
        // Add to array
        modalAssets.push(asset);
        
        // Update display
        updateAssetDisplay();
        
        // Clear form and close modal
        form.reset();
        const bsModal = bootstrap.Modal.getInstance(modal);
        bsModal.hide();
        
        if (typeof showAlert === 'function') {
            showAlert('Asset added successfully!', 'success');
        }
        return true;
    }
}

// Update asset display function (if not already defined)
if (typeof updateAssetDisplay === 'undefined') {
    function updateAssetDisplay() {
        const noAssetMessage = document.getElementById('noAssetMessage');
        const assetList = document.getElementById('assetList');
        const assetCards = document.getElementById('assetCards');
        const assetInputsContainer = document.getElementById('assetInputsContainer');
        
        if (modalAssets.length === 0) {
            if (noAssetMessage) noAssetMessage.style.display = 'block';
            if (assetList) assetList.style.display = 'none';
            if (assetInputsContainer) assetInputsContainer.innerHTML = '';
            return;
        }
        
        if (noAssetMessage) noAssetMessage.style.display = 'none';
        if (assetList) assetList.style.display = 'block';
        
        // Update asset cards
        if (assetCards) {
            assetCards.innerHTML = modalAssets.map((asset, index) => `
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-cube me-2"></i>
                                ${asset.reference}
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Description:</strong> ${asset.description || 'Not specified'}</p>
                            <p class="mb-2"><strong>Location:</strong> ${asset.location || 'Not specified'}</p>
                            <p class="mb-2"><strong>Capacity:</strong> ${asset.capacity || 'Not specified'}</p>
                            <p class="mb-2"><strong>Type:</strong> ${asset.type || 'Not specified'}</p>
                            <p class="mb-2"><strong>Condition:</strong> ${asset.condition || 'Not specified'}</p>
                            <p class="mb-2"><strong>Last Examined:</strong> ${asset.lastExamined || 'Not specified'}</p>
                            <p class="mb-2"><strong>Next Due:</strong> ${asset.nextDue || 'Not specified'}</p>
                            ${asset.notes ? `<p class="mb-2"><strong>Notes:</strong> ${asset.notes}</p>` : ''}
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="editAsset(${index})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAsset(${index})">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        // Update hidden inputs
        if (assetInputsContainer) {
            assetInputsContainer.innerHTML = modalAssets.map((asset, index) => `
                <input type="hidden" name="assets[${index}][reference]" value="${asset.reference}">
                <input type="hidden" name="assets[${index}][description]" value="${asset.description || ''}">
                <input type="hidden" name="assets[${index}][location]" value="${asset.location || ''}">
                <input type="hidden" name="assets[${index}][capacity]" value="${asset.capacity || ''}">
                <input type="hidden" name="assets[${index}][last_examined]" value="${asset.lastExamined || ''}">
                <input type="hidden" name="assets[${index}][next_due]" value="${asset.nextDue || ''}">
                <input type="hidden" name="assets[${index}][type]" value="${asset.type || ''}">
                <input type="hidden" name="assets[${index}][condition]" value="${asset.condition || ''}">
                <input type="hidden" name="assets[${index}][notes]" value="${asset.notes || ''}">
            `).join('');
        }
    }
}
</script>