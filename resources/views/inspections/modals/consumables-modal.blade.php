<!-- Consumables Assignment Modal -->
<div class="modal fade" id="consumableModal" tabindex="-1" aria-labelledby="consumableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consumableModalLabel">
                    <i class="fas fa-box me-2"></i>
                    Add Consumable Item
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="consumableForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="consumableType" class="form-label">Item Type *</label>
                            <select class="form-select" id="consumableType" required>
                                <option value="">Select item type</option>
                                <option value="Wire Rope Sling">Wire Rope Sling</option>
                                <option value="Chain Sling">Chain Sling</option>
                                <option value="Synthetic Sling">Synthetic Sling</option>
                                <option value="Lifting Beam">Lifting Beam</option>
                                <option value="Spreader Beam">Spreader Beam</option>
                                <option value="Shackle">Shackle</option>
                                <option value="Hook">Hook</option>
                                <option value="Eyebolt">Eyebolt</option>
                                <option value="Turnbuckle">Turnbuckle</option>
                                <option value="Clamp">Clamp</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="consumableDescription" class="form-label">Description *</label>
                            <input type="text" class="form-control" id="consumableDescription" required 
                                   placeholder="Item description">
                        </div>
                        <div class="col-md-6">
                            <label for="consumableQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="consumableQuantity" 
                                   min="1" value="1" placeholder="Quantity">
                        </div>
                        <div class="col-md-6">
                            <label for="consumableCapacity" class="form-label">WLL/Capacity</label>
                            <input type="text" class="form-control" id="consumableCapacity" 
                                   placeholder="Working Load Limit">
                        </div>
                        <div class="col-md-6">
                            <label for="consumableSerial" class="form-label">Serial/ID Number</label>
                            <input type="text" class="form-control" id="consumableSerial" 
                                   placeholder="Serial or identification number">
                        </div>
                        <div class="col-md-6">
                            <label for="consumableCondition" class="form-label">Condition</label>
                            <select class="form-select" id="consumableCondition">
                                <option value="Good">Good</option>
                                <option value="Satisfactory">Satisfactory</option>
                                <option value="Needs Attention">Needs Attention</option>
                                <option value="Replace">Replace</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="consumableNotesModal" class="form-label">Notes</label>
                            <textarea class="form-control" id="consumableNotesModal" rows="2" 
                                      placeholder="Any additional notes about this item"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addConsumable()">
                    <i class="fas fa-plus me-2"></i>Add Item
                </button>
            </div>
        </div>
    </div>
</div>
