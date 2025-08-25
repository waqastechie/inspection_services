<!-- Items Section -->
<section class="form-section" id="section-items">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-box"></i>
            Items
        </h2>
        <p class="section-description">
            Items under inspection
        </p>
    </div>

    <div class="section-content">
        <!-- No Items Message -->
        <div id="noItemMessage" class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-box text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
            <h5 class="text-muted mb-3">No Items Added</h5>
            <p class="text-muted mb-4">Click the button below to add items for this inspection.</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#itemModal">
                <i class="fas fa-plus me-2"></i>
                Add Item
            </button>
        </div>

        <!-- Item List -->
        <div id="itemList" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Item List
                </h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal">
                    <i class="fas fa-plus me-2"></i>
                    Add Item
                </button>
            </div>
            
            <div class="row" id="itemCards">
                <!-- Item cards will be populated here -->
            </div>
        </div>

        <!-- Hidden inputs for form submission -->
        <div id="itemInputsContainer">
            <!-- Hidden inputs will be generated here -->
        </div>
    </div>
</section>
