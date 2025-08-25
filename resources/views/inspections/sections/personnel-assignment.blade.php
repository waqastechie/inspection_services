<!-- Personnel Section -->
<section class="form-section" id="section-personnel">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-users"></i>
            Personnel
        </h2>
        <p class="section-description">
            Personnel involved in the inspection
        </p>
    </div>

    <div class="section-content">
        <!-- No Personnel Message -->
        <div id="noPersonnelMessage" class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-users text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
            <h5 class="text-muted mb-3">No Personnel Added</h5>
            <p class="text-muted mb-4">Click the button below to add personnel for this inspection.</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#personnelNewModal">
                <i class="fas fa-plus me-2"></i>
                Add Personnel
            </button>
        </div>

        <!-- Personnel List -->
        <div id="personnelList" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Personnel List
                </h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#personnelNewModal">
                    <i class="fas fa-plus me-2"></i>
                    Add Personnel
                </button>
            </div>
            
            <div class="row" id="personnelCards">
                <!-- Personnel cards will be populated here -->
            </div>
        </div>

        <!-- Hidden inputs for form submission -->
        <div id="personnelInputsContainer">
            <!-- Hidden inputs will be generated here -->
        </div>
    </div>
</section>

