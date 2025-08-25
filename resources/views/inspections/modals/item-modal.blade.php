<!-- Item Details Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Add Item Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="item_ref" class="form-label">Item Reference <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item_ref" name="item_ref" required>
                        </div>
                        <div class="col-md-6">
                            <label for="item_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="item_quantity" name="item_quantity" min="1">
                        </div>
                        <div class="col-12">
                            <label for="item_description" class="form-label">Description</label>
                            <textarea class="form-control" id="item_description" name="item_description" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="item_condition" class="form-label">Condition</label>
                            <select class="form-select" id="item_condition" name="item_condition">
                                <option value="">Select condition...</option>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="defective">Defective</option>
                                <option value="not_inspected">Not Inspected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="item_result" class="form-label">Test Result</label>
                            <select class="form-select" id="item_result" name="item_result">
                                <option value="">Select result...</option>
                                <option value="pass">Pass</option>
                                <option value="fail">Fail</option>
                                <option value="conditional">Conditional</option>
                                <option value="not_tested">Not Tested</option>
                                <option value="requires_attention">Requires Attention</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="item_remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="item_remarks" name="item_remarks" rows="2" placeholder="Additional notes or observations..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveItemBtn">Add Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="edit_item_index" name="edit_item_index">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_item_ref" class="form-label">Item Reference <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_item_ref" name="item_ref" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_item_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_item_quantity" name="item_quantity" min="1">
                        </div>
                        <div class="col-12">
                            <label for="edit_item_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_item_description" name="item_description" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_item_condition" class="form-label">Condition</label>
                            <select class="form-select" id="edit_item_condition" name="item_condition">
                                <option value="">Select condition...</option>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="defective">Defective</option>
                                <option value="not_inspected">Not Inspected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_item_result" class="form-label">Test Result</label>
                            <select class="form-select" id="edit_item_result" name="item_result">
                                <option value="">Select result...</option>
                                <option value="pass">Pass</option>
                                <option value="fail">Fail</option>
                                <option value="conditional">Conditional</option>
                                <option value="not_tested">Not Tested</option>
                                <option value="requires_attention">Requires Attention</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_item_remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="edit_item_remarks" name="item_remarks" rows="2" placeholder="Additional notes or observations..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateItem()">Update Item</button>
            </div>
        </div>
    </div>
</div>
