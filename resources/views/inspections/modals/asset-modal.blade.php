<!-- Equipment Type Modal -->
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel">Add Equipment Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assetForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="asset_ref" class="form-label">Asset Reference <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="asset_ref" name="asset_ref" required>
                        </div>
                        <div class="col-md-6">
                            <label for="asset_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="asset_description" name="asset_description">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="asset_location" name="asset_location">
                        </div>
                        <div class="col-md-6">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="text" class="form-control" id="capacity" name="capacity" placeholder="e.g., 10T, 5000kg">
                        </div>
                        <div class="col-md-6">
                            <label for="last_examined" class="form-label">Last Examined</label>
                            <input type="date" class="form-control" id="last_examined" name="last_examined">
                        </div>
                        <div class="col-md-6">
                            <label for="next_due" class="form-label">Next Due</label>
                            <input type="date" class="form-control" id="next_due" name="next_due">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAssetBtn">Add Asset</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Asset Modal -->
<div class="modal fade" id="editAssetModal" tabindex="-1" aria-labelledby="editAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssetModalLabel">Edit Equipment Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAssetForm">
                    <input type="hidden" id="edit_asset_index" name="edit_asset_index">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_asset_ref" class="form-label">Asset Reference <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_asset_ref" name="asset_ref" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_asset_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_asset_description" name="asset_description">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_asset_location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="edit_asset_location" name="asset_location">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_capacity" class="form-label">Capacity</label>
                            <input type="text" class="form-control" id="edit_capacity" name="capacity" placeholder="e.g., 10T, 5000kg">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_last_examined" class="form-label">Last Examined</label>
                            <input type="date" class="form-control" id="edit_last_examined" name="last_examined">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_next_due" class="form-label">Next Due</label>
                            <input type="date" class="form-control" id="edit_next_due" name="next_due">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateAsset()">Update Asset</button>
            </div>
        </div>
    </div>
</div>
