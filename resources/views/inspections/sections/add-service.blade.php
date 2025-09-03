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
    content: '✓';
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

    // Initially hide all service sections except client info and add-service
    const sectionsToHide = [
        'section-lifting-examination',
        'section-load-test',
        'section-mpi-service',
        'section-thorough-examination',
        'section-visual'
        // Personnel, Equipment, Consumables, Comments should always be visible
    ];
    
    sectionsToHide.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            section.style.display = 'none';
        }
    });

    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            const serviceName = this.dataset.service;
            const serviceTitle = this.querySelector('.card-title').textContent;
            const relatedSection = this.dataset.section;
            
            if (this.classList.contains('selected')) {
                // Deselect service
                this.classList.remove('selected');
                selectedServices = selectedServices.filter(s => s.name !== serviceName);
                
                // Hide related section with smooth animation
                if (relatedSection) {
                    const section = document.getElementById(relatedSection);
                    if (section) {
                        // Animate hide
                        section.style.opacity = '0';
                        section.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            section.style.display = 'none';
                        }, 300);
                    }
                }
            } else {
                // Select service
                this.classList.add('selected');
                selectedServices.push({
                    name: serviceName,
                    title: serviceTitle,
                    section: relatedSection
                });
                
                // Show related section with smooth animation
                if (relatedSection) {
                    const section = document.getElementById(relatedSection);
                    if (section) {
                        section.style.display = 'block';
                        section.style.opacity = '0';
                        section.style.transform = 'translateY(-10px)';
                        
                        // Animate show
                        setTimeout(() => {
                            section.style.opacity = '1';
                            section.style.transform = 'translateY(0)';
                        }, 10);
                        
                        // Smooth scroll to section
                        setTimeout(() => {
                            section.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'start' 
                            });
                        }, 300);
                    }
                }
            }
            
            updateSelectedServicesDisplay();
        });
    });

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
