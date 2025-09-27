<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServiceModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add Inspection Service
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Select the type of inspection service to add to your report.</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="service-card" data-service="lifting">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-dumbbell fa-3x text-primary mb-3"></i>
                                    <h6 class="card-title">Lifting Equipment</h6>
                                    <p class="card-text small">Inspection of lifting equipment including slings, shackles, hooks, and rigging gear.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="service-card" data-service="mpi">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-search fa-3x text-success mb-3"></i>
                                    <h6 class="card-title">MPI Service</h6>
                                    <p class="card-text small">Magnetic Particle Inspection for detecting surface and near-surface defects.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="service-card" data-service="visual">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-eye fa-3x text-warning mb-3"></i>
                                    <h6 class="card-title">Visual Inspection</h6>
                                    <p class="card-text small">Comprehensive visual examination and documentation of equipment condition.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="service-card" data-service="ultrasonic">
                            <div class="card h-100 border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-wave-square fa-3x text-info mb-3"></i>
                                    <h6 class="card-title">Ultrasonic Testing</h6>
                                    <p class="card-text small">Non-destructive testing using ultrasonic waves to detect internal defects.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmAddService" disabled>
                    <i class="fas fa-check me-2"></i>Add Selected Service
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.service-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.service-card.selected .card {
    background-color: #f8f9fa;
    border-width: 2px;
}

.service-card.selected .card-body {
    background-color: rgba(13, 110, 253, 0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedService = null;
    
    // Service card selection
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove previous selection
            document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
            
            // Add selection to clicked card
            this.classList.add('selected');
            selectedService = this.dataset.service;
            
            // Enable confirm button
            document.getElementById('confirmAddService').disabled = false;
        });
    });
    
    // Confirm service addition
    document.getElementById('confirmAddService').addEventListener('click', function() {
        if (selectedService) {
            addServiceToForm(selectedService);
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addServiceModal'));
            modal.hide();
            
            // Reset selection
            document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
            selectedService = null;
            this.disabled = true;
        }
    });
});

function addServiceToForm(serviceType) {
    // Add the selected service to the main form
    const serviceSection = document.getElementById('section-add-service');
    const selectedServicesContainer = serviceSection.querySelector('.selected-services');
    
    if (!selectedServicesContainer) {
        console.error('Selected services container not found');
        return;
    }
    
    // Create service badge
    const serviceBadge = document.createElement('span');
    serviceBadge.className = 'badge bg-primary me-2 mb-2 service-badge';
    serviceBadge.dataset.service = serviceType;
    serviceBadge.innerHTML = `
        <i class="fas fa-${getServiceIcon(serviceType)} me-1"></i>
        ${getServiceName(serviceType)}
        <button type="button" class="btn-close btn-close-white ms-2" onclick="removeService('${serviceType}')"></button>
    `;
    
    selectedServicesContainer.appendChild(serviceBadge);
    
    // Show the corresponding service form
    showServiceForm(serviceType);
    
    // Update hidden input
    updateSelectedServicesInput();
    
    // Show success message
    showToast('Service added successfully', 'success');
}

function getServiceIcon(serviceType) {
    const icons = {
        'lifting': 'dumbbell',
        'mpi': 'search',
        'visual': 'eye',
        'ultrasonic': 'wave-square'
    };
    return icons[serviceType] || 'cog';
}

function getServiceName(serviceType) {
    const names = {
        'lifting': 'Lifting Equipment',
        'mpi': 'MPI Service',
        'visual': 'Visual Inspection',
        'ultrasonic': 'Ultrasonic Testing'
    };
    return names[serviceType] || serviceType.charAt(0).toUpperCase() + serviceType.slice(1);
}

function removeService(serviceType) {
    const badge = document.querySelector(`.service-badge[data-service="${serviceType}"]`);
    if (badge) {
        badge.remove();
        // Hide the corresponding service form
        const formElement = document.getElementById(`${serviceType}-service-form`);
        if (formElement) {
            formElement.style.display = 'none';
        }
        updateSelectedServicesInput();
        showToast('Service removed', 'info');
    }
}

function updateSelectedServicesInput() {
    const badges = document.querySelectorAll('.service-badge');
    const services = Array.from(badges).map(badge => badge.dataset.service);
    
    let input = document.querySelector('input[name="selected_services"]');
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_services';
        document.getElementById('liftingInspectionForm').appendChild(input);
    }
    
    input.value = JSON.stringify(services);
}

function showToast(message, type = 'info') {
    // Simple toast notification (can be enhanced with a proper toast library)
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}
</script>
