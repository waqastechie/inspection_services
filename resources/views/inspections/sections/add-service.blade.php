<!-- Add Service Section -->
<section class="form-section" id="section-add-service">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-list me-3"></i>
                    Add Service
                </h2>
                <p class="section-description">
                    Select the inspection services to be performed
                </p>
            </div>
            <div class="section-indicator">
                <i class="fas fa-circle text-muted"></i>
            </div>
        </div>
    </div>

    <div class="section-content">
        <div class="mb-4">
            <label class="form-label fw-bold">
                Select Services to Perform Through Examination <span class="text-danger">*</span>
            </label>
            
            <div class="row g-3 mb-4">
                <!-- First Row -->
                <div class="col-lg-3 col-md-6">
                    <div class="service-card" data-service="lifting-examination" data-section="section-lifting-examination">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-tools fa-3x text-primary"></i>
                                </div>
                                <h6 class="card-title mb-0">Lifting Examination</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="service-card" data-service="load-test" data-section="section-load-test">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-weight-hanging fa-3x text-secondary"></i>
                                </div>
                                <h6 class="card-title mb-0">Load Test</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="service-card" data-service="mpi-service" data-section="section-mpi-service">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-search fa-3x text-info"></i>
                                </div>
                                <h6 class="card-title mb-0">MPI</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="service-card" data-service="thorough-examination" data-section="section-thorough-examination">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-eye fa-3x text-success"></i>
                                </div>
                                <h6 class="card-title mb-0">Thorough Examination</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="col-lg-3 col-md-6">
                    <div class="service-card" data-service="visual" data-section="section-visual">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-binoculars fa-3x text-warning"></i>
                                </div>
                                <h6 class="card-title mb-0">Visual</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="service-card" data-service="other-services" data-section="section-other-services">
                        <div class="card h-100 border-2" style="cursor: pointer;">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-cogs fa-3x text-dark"></i>
                                </div>
                                <h6 class="card-title mb-0">Other Tests</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selected Services Display -->
        <div class="selected-services-container mt-4">
            <div class="alert alert-info" id="selectedServicesAlert" style="display: none;">
                <h6 class="alert-heading mb-2">
                    <i class="fas fa-check-circle me-2"></i>
                    Selected Services:
                </h6>
                <div id="selectedServicesList" class="d-flex flex-wrap gap-2">
                    <!-- Selected services badges will appear here -->
                </div>
            </div>
        </div>

        <!-- Hidden inputs to store selected services -->
        <div id="selectedServicesInputs">
            <!-- Hidden inputs will be generated here -->
        </div>

        <!-- Inline Service Forms (hidden by default, shown when selected) -->
        <div id="inlineServiceForms" class="mt-5">
            <div id="liftingExaminationFormSection" style="display:none;">
                @include('inspections.sections.lifting-examination')
            </div>
            <div id="mpiServiceFormSection" style="display:none;">
                @include('inspections.sections.mpi-service')
            </div>
            <div id="loadTestFormSection" style="display:none;">
                @include('inspections.sections.load-test')
            </div>
            <div id="thoroughExaminationFormSection" style="display:none;">
                @include('inspections.sections.thorough-examination')
            </div>
            <div id="visualFormSection" style="display:none;">
                @include('inspections.sections.visual')
            </div>
            <div id="otherServicesFormSection" style="display:none;">
                @include('inspections.sections.other-services')
            </div>
        </div>
    </div>
</section>

<style>
.service-card .card {
    transition: all 0.3s ease;
    border-color: #e2e8f0 !important;
    background-color: #ffffff;
    min-height: 140px;
}

.service-card .card:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    transform: translateY(-2px);
}

.service-card.selected .card {
    border-color: #3b82f6 !important;
    background-color: #dbeafe;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    position: relative;
}

.service-card.selected .card::after {
    content: "\2713";
    position: absolute;
    top: 10px;
    right: 15px;
    background-color: #3b82f6;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
}

.service-card.selected .service-icon i {
    color: #3b82f6 !important;
}

.service-card .service-icon i {
    transition: color 0.3s ease;
}

.selected-services-container .badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

/* Section fade animation */
.form-section {
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}

/* Ensure sections have proper initial state */
.form-section {
    opacity: 1;
    transform: translateY(0);
}

/* Match your screenshot styling */
.service-card .card-body {
    padding: 2rem 1rem;
}

.service-card .card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
}

.service-card.selected .card-title {
    color: #1d4ed8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCards = document.querySelectorAll('.service-card');
    const selectedServicesAlert = document.getElementById('selectedServicesAlert');
    const selectedServicesList = document.getElementById('selectedServicesList');
    const selectedServicesInputs = document.getElementById('selectedServicesInputs');
    let selectedServices = [];

    // Pre-select services if we're editing an inspection
    @if(isset($inspection) && $inspection->services_performed)
        @php
            $servicesPerformed = $inspection->services_performed;
            if (is_string($servicesPerformed)) {
                $servicesPerformed = json_decode($servicesPerformed, true);
            }
            $servicesPerformed = $servicesPerformed ?: [];
        @endphp
        const existingServices = @json($servicesPerformed);
        console.log('DEBUG: Existing services from services_performed:', existingServices);
        
        // Map service names to data-service values
        const serviceNameMap = {
            'Lifting Examination': 'lifting-examination',
            'MPI Service': 'mpi-service', 
            'Load Test': 'load-test',
            'Thorough Examination': 'thorough-examination',
            'Visual': 'visual',
            'Other Services': 'other-services'
        };
        
        // Pre-select existing services
        existingServices.forEach(serviceName => {
            console.log('DEBUG: Processing service name:', serviceName);
            const serviceType = serviceNameMap[serviceName] || serviceName.toLowerCase().replace(/\s+/g, '-');
            const serviceCard = document.querySelector(`[data-service="${serviceType}"]`);
            console.log('DEBUG: Found service card for', serviceType, ':', serviceCard);
            
            if (serviceCard) {
                serviceCard.classList.add('selected');
                const serviceTitle = serviceCard.querySelector('.card-title').textContent;
                console.log('DEBUG: Selected service:', serviceType, 'with title:', serviceTitle);
                selectedServices.push({
                    name: serviceType,
                    title: serviceTitle
                });
            } else {
                console.log('DEBUG: No service card found for:', serviceType);
            }
        });
        
        console.log('DEBUG: Final selected services:', selectedServices);
        
        // Update display after pre-selection
        updateSelectedServicesDisplay();
        updateInlineServiceForms();
    @endif

    // Map service name to form section ID
    const serviceFormSectionMap = {
        'lifting-examination': 'liftingExaminationFormSection',
        'mpi-service': 'mpiServiceFormSection',
        'load-test': 'loadTestFormSection',
        'thorough-examination': 'thoroughExaminationFormSection',
        'visual': 'visualFormSection',
        'other-services': 'otherServicesFormSection'
    };

    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            const serviceName = this.dataset.service;
            const serviceTitle = this.querySelector('.card-title').textContent;

            if (this.classList.contains('selected')) {
                // Deselect service
                this.classList.remove('selected');
                selectedServices = selectedServices.filter(s => s.name !== serviceName);
            } else {
                // Select service
                this.classList.add('selected');
                selectedServices.push({
                    name: serviceName,
                    title: serviceTitle
                });
            }

            updateSelectedServicesDisplay();
            updateInlineServiceForms();
        });
    });

    function updateInlineServiceForms() {
        // Hide all service form sections
        Object.values(serviceFormSectionMap).forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) section.style.display = 'none';
        });
        // Show selected service form sections
        selectedServices.forEach(service => {
            const sectionId = serviceFormSectionMap[service.name];
            if (sectionId) {
                const section = document.getElementById(sectionId);
                if (section) section.style.display = 'block';
            }
        });
    }

    function updateSelectedServicesDisplay() {
        if (selectedServices.length === 0) {
            selectedServicesAlert.style.display = 'none';
            selectedServicesInputs.innerHTML = '';
            return;
        }

        selectedServicesAlert.style.display = 'block';

        // Update badges
        selectedServicesList.innerHTML = selectedServices.map(service => 
            `<span class="badge bg-primary">
                <i class="fas fa-check me-1"></i>${service.title}
            </span>`
        ).join('');

        // Update hidden inputs
        selectedServicesInputs.innerHTML = selectedServices.map(service => 
            `<input type="hidden" name="selected_services[]" value="${service.name}">`
        ).join('');

        // Update section indicator
        const sectionIndicator = document.querySelector('#section-add-service .section-indicator i');
        if (sectionIndicator) {
            sectionIndicator.className = 'fas fa-check-circle text-success';
        }
    }

});
</script>
