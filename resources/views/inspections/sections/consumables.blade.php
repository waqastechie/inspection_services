<!-- Consumables Section -->
<section class="form-section" id="section-consumables">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-flask"></i>
            Consumables
        </h2>
        <p class="section-description">
            Consumables used in the inspection
        </p>
    </div>

    <div class="section-content">
        <!-- No Consumables Message -->
        <div id="noConsumableMessage" class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-flask text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
            <h5 class="text-muted mb-3">No Consumables Added</h5>
            <p class="text-muted mb-4">Click the button below to add consumables for this inspection.</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#consumableModal">
                <i class="fas fa-plus me-2"></i>
                Add Consumable
            </button>
        </div>

        <!-- Consumable List -->
        <div id="consumableList" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Consumable List
                </h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#consumableModal">
                    <i class="fas fa-plus me-2"></i>
                    Add Consumable
                </button>
            </div>
            
            <div class="row" id="consumableCards">
                <!-- Consumable cards will be populated here -->
            </div>
        </div>

        <!-- Hidden inputs for form submission -->
        <div id="consumableInputsContainer">
            <!-- Hidden inputs will be generated here -->
        </div>
    </div>
</section>
