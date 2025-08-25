<!-- Asset Details Section -->
<section class="form-section" id="section-asset-details">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-cube"></i>
            Asset Details
        </h2>
        <p class="section-description">
            Assets under inspection
        </p>
    </div>

    <div class="section-content">
        <!-- No Assets Message -->
        <div id="noAssetMessage" class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-cube text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
            <h5 class="text-muted mb-3">No Assets Added</h5>
            <p class="text-muted mb-4">Click the button below to add assets for this inspection.</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#assetModal">
                <i class="fas fa-plus me-2"></i>
                Add Asset
            </button>
        </div>

        <!-- Asset List -->
        <div id="assetList" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Asset List
                </h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assetModal">
                    <i class="fas fa-plus me-2"></i>
                    Add Asset
                </button>
            </div>
            
            <div class="row" id="assetCards">
                <!-- Asset cards will be populated here -->
            </div>
        </div>

        <!-- Hidden inputs for form submission -->
        <div id="assetInputsContainer">
            <!-- Hidden inputs will be generated here -->
        </div>
    </div>
</section>
