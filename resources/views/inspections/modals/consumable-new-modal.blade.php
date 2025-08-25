<!-- Consumable Modal -->
<div class="modal fade" id="consumableModal" tabindex="-1" aria-labelledby="consumableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consumableModalLabel">Add Consumable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newConsumableForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="consumable_description" class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="consumable_description" name="consumable_description" required placeholder="e.g., Penetrant Dye, Magnetic Ink, Wire Rope">
                        </div>
                        <div class="col-md-4">
                            <label for="consumable_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="consumable_quantity" name="consumable_quantity" step="0.01" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="consumable_unit" class="form-label">Unit</label>
                            <select class="form-select" id="consumable_unit" name="consumable_unit">
                                <option value="">Select unit...</option>
                                <option value="litres">Litres</option>
                                <option value="ml">Millilitres</option>
                                <option value="kg">Kilograms</option>
                                <option value="g">Grams</option>
                                <option value="pieces">Pieces</option>
                                <option value="meters">Meters</option>
                                <option value="rolls">Rolls</option>
                                <option value="bottles">Bottles</option>
                                <option value="cans">Cans</option>
                                <option value="tubes">Tubes</option>
                                <option value="packets">Packets</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="consumable_batch_number" class="form-label">Batch/Lot Number</label>
                            <input type="text" class="form-control" id="consumable_batch_number" name="consumable_batch_number">
                        </div>
                        <div class="col-md-6">
                            <label for="consumable_manufacturer" class="form-label">Manufacturer/Brand</label>
                            <input type="text" class="form-control" id="consumable_manufacturer" name="consumable_manufacturer" placeholder="e.g., Magnaflux, Chemetall">
                        </div>
                        <div class="col-md-6">
                            <label for="consumable_product_code" class="form-label">Product Code</label>
                            <input type="text" class="form-control" id="consumable_product_code" name="consumable_product_code">
                        </div>
                        <div class="col-md-6">
                            <label for="consumable_expiry_date" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="consumable_expiry_date" name="consumable_expiry_date">
                        </div>
                        <div class="col-md-6">
                            <label for="consumable_cost" class="form-label">Unit Cost (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="consumable_cost" name="consumable_cost" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="consumable_supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="consumable_supplier" name="consumable_supplier">
                        </div>
                        <div class="col-md-6">
                            <label for="consumable_condition" class="form-label">Condition</label>
                            <select class="form-select" id="consumable_condition" name="consumable_condition">
                                <option value="new">New</option>
                                <option value="used">Used</option>
                                <option value="expired">Expired</option>
                                <option value="damaged">Damaged</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="consumable_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="consumable_notes" name="consumable_notes" rows="2" placeholder="Additional notes about usage, storage, or observations..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveConsumableBtn">Add Consumable</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Consumable Modal -->
<div class="modal fade" id="editConsumableModal" tabindex="-1" aria-labelledby="editConsumableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editConsumableModalLabel">Edit Consumable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editConsumableForm">
                    <input type="hidden" id="edit_consumable_index" name="edit_consumable_index">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="edit_consumable_description" class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_consumable_description" name="consumable_description" required placeholder="e.g., Penetrant Dye, Magnetic Ink, Wire Rope">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_consumable_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_consumable_quantity" name="consumable_quantity" step="0.01" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_consumable_unit" class="form-label">Unit</label>
                            <select class="form-select" id="edit_consumable_unit" name="consumable_unit">
                                <option value="">Select unit...</option>
                                <option value="litres">Litres</option>
                                <option value="ml">Millilitres</option>
                                <option value="kg">Kilograms</option>
                                <option value="g">Grams</option>
                                <option value="pieces">Pieces</option>
                                <option value="meters">Meters</option>
                                <option value="rolls">Rolls</option>
                                <option value="bottles">Bottles</option>
                                <option value="cans">Cans</option>
                                <option value="tubes">Tubes</option>
                                <option value="packets">Packets</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_consumable_batch_number" class="form-label">Batch/Lot Number</label>
                            <input type="text" class="form-control" id="edit_consumable_batch_number" name="consumable_batch_number">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_consumable_manufacturer" class="form-label">Manufacturer/Brand</label>
                            <input type="text" class="form-control" id="edit_consumable_manufacturer" name="consumable_manufacturer" placeholder="e.g., Magnaflux, Chemetall">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_consumable_product_code" class="form-label">Product Code</label>
                            <input type="text" class="form-control" id="edit_consumable_product_code" name="consumable_product_code">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_consumable_expiry_date" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="edit_consumable_expiry_date" name="consumable_expiry_date">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_consumable_cost" class="form-label">Unit Cost (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="edit_consumable_cost" name="consumable_cost" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_consumable_supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="edit_consumable_supplier" name="consumable_supplier">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_consumable_condition" class="form-label">Condition</label>
                            <select class="form-select" id="edit_consumable_condition" name="consumable_condition">
                                <option value="new">New</option>
                                <option value="used">Used</option>
                                <option value="expired">Expired</option>
                                <option value="damaged">Damaged</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_consumable_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit_consumable_notes" name="consumable_notes" rows="2" placeholder="Additional notes about usage, storage, or observations..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateConsumable()">Update Consumable</button>
            </div>
        </div>
    </div>
</div>
