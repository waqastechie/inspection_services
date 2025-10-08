<!-- Other Tests Section - Edit Wizard Version -->
<div class="form-section" id="section-other-services">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-cogs me-3"></i>
                    Other Tests
                </h2>
                <p class="section-description">
                    Select and configure additional testing services
                </p>
            </div>
            <div class="section-indicator">
                <i class="fas fa-circle text-muted"></i>
            </div>
        </div>
    </div>

    <div class="section-content">
        <!-- Sub-Service Selection -->
        <div class="mb-4">
            <label class="form-label fw-bold">
                Select Other Tests <span class="text-danger">*</span>
            </label>
            
            <div class="row g-3 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="sub-service-card" data-sub-service="drop-test">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-3">
                                <div class="service-icon mb-2">
                                    <i class="fas fa-arrow-down fa-2x text-danger"></i>
                                </div>
                                <h6 class="card-title mb-0">Drop Test</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="sub-service-card" data-sub-service="tilt-test">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-3">
                                <div class="service-icon mb-2">
                                    <i class="fas fa-balance-scale fa-2x text-warning"></i>
                                </div>
                                <h6 class="card-title mb-0">Tilt Test</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="sub-service-card" data-sub-service="lowering-test">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-3">
                                <div class="service-icon mb-2">
                                    <i class="fas fa-arrow-circle-down fa-2x text-info"></i>
                                </div>
                                <h6 class="card-title mb-0">Lowering Test</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selected Sub-Services Display -->
        <div class="selected-sub-services-container mt-4">
            <div class="alert alert-success" id="selectedSubServicesAlert" style="display: none;">
                <h6 class="alert-heading mb-2">
                    <i class="fas fa-check-circle me-2"></i>
                    Selected Other Tests:
                </h6>
                <div id="selectedSubServicesList" class="d-flex flex-wrap gap-2">
                    <!-- Selected sub-services badges will appear here -->
                </div>
            </div>
        </div>

        <!-- Hidden inputs to store selected sub-services -->
        <div id="selectedSubServicesInputs">
            <!-- Hidden inputs will be generated here -->
        </div>

        <!-- Sub-Service Forms (hidden by default; shown when selected or when data exists) -->
        <div id="subServiceForms" class="mt-4">
            <div id="dropTestFormSection" style="{{ (isset($inspection) && $inspection->otherTest && (
                    $inspection->otherTest->drop_test_load ||
                    $inspection->otherTest->drop_type ||
                    $inspection->otherTest->drop_distance ||
                    $inspection->otherTest->drop_suspended ||
                    $inspection->otherTest->drop_impact_speed ||
                    $inspection->otherTest->drop_result ||
                    $inspection->otherTest->drop_notes
                )) ? '' : 'display:none;' }}">
                @include('inspections.edit-wizard-sections.drop-test')
            </div>
            <div id="tiltTestFormSection" style="{{ (isset($inspection) && $inspection->otherTest && (
                    $inspection->otherTest->tilt_test_load ||
                    $inspection->otherTest->loaded_tilt ||
                    $inspection->otherTest->empty_tilt ||
                    $inspection->otherTest->tilt_results ||
                    $inspection->otherTest->tilt_stability ||
                    $inspection->otherTest->tilt_direction ||
                    $inspection->otherTest->tilt_duration ||
                    $inspection->otherTest->tilt_notes
                )) ? '' : 'display:none;' }}">
                @include('inspections.edit-wizard-sections.tilt-test')
            </div>
            <div id="loweringTestFormSection" style="{{ (isset($inspection) && $inspection->otherTest && (
                    $inspection->otherTest->lowering_test_load ||
                    $inspection->otherTest->lowering_impact_speed ||
                    $inspection->otherTest->lowering_result ||
                    $inspection->otherTest->lowering_method ||
                    $inspection->otherTest->lowering_distance ||
                    $inspection->otherTest->lowering_duration ||
                    $inspection->otherTest->lowering_cycles ||
                    $inspection->otherTest->brake_efficiency ||
                    $inspection->otherTest->control_response ||
                    $inspection->otherTest->lowering_notes
                )) ? '' : 'display:none;' }}">
                @include('inspections.edit-wizard-sections.lowering-test')
            </div>
        </div>
    </div>
</div>

<style>
.sub-service-card .card {
    transition: all 0.3s ease;
    border-color: #e5e7eb;
}

.sub-service-card .card:hover {
    border-color: #3b82f6;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    transform: translateY(-2px);
}

.sub-service-card.selected .card {
    border-color: #3b82f6 !important;
    background-color: #dbeafe;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    position: relative;
}

.sub-service-card.selected .card::after {
    content: '✓';
    position: absolute;
    top: 8px;
    right: 12px;
    background-color: #3b82f6;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.sub-service-card .card-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
}

.sub-service-card.selected .card-title {
    color: #1d4ed8;
}
</style>

<script>
(function() {
    function initOtherServices() {
        const subServiceCards = document.querySelectorAll('.sub-service-card');
        const selectedSubServicesAlert = document.getElementById('selectedSubServicesAlert');
        const selectedSubServicesList = document.getElementById('selectedSubServicesList');
        const selectedSubServicesInputs = document.getElementById('selectedSubServicesInputs');
        let selectedSubServices = [];

    // Map sub-service name to form section ID
    const subServiceFormSectionMap = {
        'drop-test': 'dropTestFormSection',
        'tilt-test': 'tiltTestFormSection',
        'lowering-test': 'loweringTestFormSection'
    };

    // Define functions first before using them
    function updateSubServiceForms() {
        console.log('DEBUG: updateSubServiceForms called with:', selectedSubServices);
        // Hide all sub-service form sections
        Object.values(subServiceFormSectionMap).forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'none';
                console.log('DEBUG: Hiding section:', sectionId);
            } else {
                console.log('DEBUG: Section not found:', sectionId);
            }
        });
        // Show selected sub-service form sections
        selectedSubServices.forEach(subService => {
            const sectionId = subServiceFormSectionMap[subService.name];
            if (sectionId) {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.style.display = 'block';
                    console.log('DEBUG: Showing section:', sectionId);
                } else {
                    console.log('DEBUG: Section not found for showing:', sectionId);
                }
            }
        });
    }

    function updateSelectedSubServicesDisplay() {
        if (selectedSubServices.length === 0) {
            selectedSubServicesAlert.style.display = 'none';
            selectedSubServicesInputs.innerHTML = '';
            return;
        }

        selectedSubServicesAlert.style.display = 'block';

        // Update badges
        selectedSubServicesList.innerHTML = selectedSubServices.map(subService => 
            `<span class="badge bg-success">
                <i class="fas fa-check me-1"></i>${subService.title}
            </span>`
        ).join('');

        // Update hidden inputs
        selectedSubServicesInputs.innerHTML = selectedSubServices.map(subService => 
            `<input type="hidden" name="selected_sub_services[]" value="${subService.name}">`
        ).join('');
    }

    // Pre-select sub-services if we're editing an inspection with existing Other Test data
    @if(isset($inspection) && $inspection->otherTest)
        const otherTestData = @json($inspection->otherTest);
        console.log('DEBUG: Other Test data:', otherTestData);
        
        // Check which sub-services should be pre-selected based on existing data
        // Check if Drop Test has any data
        if (otherTestData && (otherTestData.drop_test_load || otherTestData.drop_type || 
            otherTestData.drop_distance || otherTestData.drop_suspended || 
            otherTestData.drop_impact_speed || otherTestData.drop_result || 
            otherTestData.drop_notes)) {
            const dropTestCard = document.querySelector('[data-sub-service="drop-test"]');
            if (dropTestCard) {
                dropTestCard.classList.add('selected');
                selectedSubServices.push({
                    name: 'drop-test',
                    title: 'Drop Test'
                });
                console.log('DEBUG: Pre-selected Drop Test');
            }
        }
        
        // Check if Tilt Test has any data
        if (otherTestData && (otherTestData.tilt_test_load || otherTestData.loaded_tilt || 
            otherTestData.empty_tilt || otherTestData.tilt_results || 
            otherTestData.tilt_stability || otherTestData.tilt_direction || 
            otherTestData.tilt_duration || otherTestData.tilt_notes)) {
            const tiltTestCard = document.querySelector('[data-sub-service="tilt-test"]');
            if (tiltTestCard) {
                tiltTestCard.classList.add('selected');
                selectedSubServices.push({
                    name: 'tilt-test',
                    title: 'Tilt Test'
                });
                console.log('DEBUG: Pre-selected Tilt Test');
            }
        }
        
        // Check if Lowering Test has any data
        if (otherTestData && (otherTestData.lowering_test_load || otherTestData.lowering_impact_speed || 
            otherTestData.lowering_result || otherTestData.lowering_method || 
            otherTestData.lowering_distance || otherTestData.lowering_duration || 
            otherTestData.lowering_cycles || otherTestData.brake_efficiency || 
            otherTestData.control_response || otherTestData.lowering_notes)) {
            const loweringTestCard = document.querySelector('[data-sub-service="lowering-test"]');
            if (loweringTestCard) {
                loweringTestCard.classList.add('selected');
                selectedSubServices.push({
                    name: 'lowering-test',
                    title: 'Lowering Test'
                });
                console.log('DEBUG: Pre-selected Lowering Test');
            }
        }
        
        console.log('DEBUG: Pre-selected sub-services:', selectedSubServices);
        
        // Update display after pre-selection
        updateSelectedSubServicesDisplay();
        updateSubServiceForms();
    @endif

        subServiceCards.forEach(card => {
            card.addEventListener('click', function() {
                const subServiceName = this.dataset.subService;
                const subServiceTitle = this.querySelector('.card-title').textContent;

                if (this.classList.contains('selected')) {
                    // Deselect sub-service
                    this.classList.remove('selected');
                    selectedSubServices = selectedSubServices.filter(s => s.name !== subServiceName);
                } else {
                    // Select sub-service
                    this.classList.add('selected');
                    selectedSubServices.push({
                        name: subServiceName,
                        title: subServiceTitle
                    });
                }

                updateSelectedSubServicesDisplay();
                updateSubServiceForms();
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initOtherServices);
    } else {
        // DOMContentLoaded already fired; initialize immediately
        initOtherServices();
    }
})();
</script>