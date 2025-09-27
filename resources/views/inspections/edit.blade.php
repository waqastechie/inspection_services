@extends('layouts.master')

@section('title', 'Edit Inspection Report - Professional Inspection Services')

@php
    $showProgress = true;
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="form-container">
                
                <!-- Simple Header matching Create Page -->
                <div class="header-section mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="text-primary mb-2">
                                <i class="fas fa-edit me-2"></i>Edit Inspection Report
                            </h1>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="fas fa-hashtag me-1"></i>{{ $inspection->inspection_number }}
                                </span>
                                @if($inspection->status)
                                    @php
                                        $statusConfig = [
                                            'draft' => ['color' => 'warning', 'icon' => 'fa-edit', 'text' => 'Draft'],
                                            'in_progress' => ['color' => 'info', 'icon' => 'fa-spinner', 'text' => 'In Progress'],
                                            'completed' => ['color' => 'success', 'icon' => 'fa-check', 'text' => 'Completed'],
                                            'cancelled' => ['color' => 'danger', 'icon' => 'fa-times', 'text' => 'Cancelled']
                                        ];
                                        $config = $statusConfig[$inspection->status] ?? ['color' => 'secondary', 'icon' => 'fa-question', 'text' => ucfirst($inspection->status)];
                                    @endphp
                                    <span class="badge bg-{{ $config['color'] }} px-3 py-2">
                                        <i class="fas {{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                    </span>
                                @endif
                                @if($inspection->client?->client_name)
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="fas fa-building me-1"></i>{{ $inspection->client->client_name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to View
                            </a>
                        </div>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="liftingInspectionForm" method="POST" action="{{ route('inspections.update', $inspection->id) }}" enctype="multipart/form-data" 
                      x-data="inspectionWizard()" @submit="validateAndSubmit($event)"
                      class="needs-validation">
                    @csrf
                    @method('PUT')
                    
                    <!-- Wizard Step Navigation -->
                    <div class="wizard-navigation mb-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="step-indicators d-flex justify-content-between">
                                    <template x-for="(step, index) in steps" :key="index">
                                        <div class="step-indicator" 
                                             :class="{'active': currentStep === index, 'completed': isStepCompleted(index)}"
                                             @click="goToStep(index)">
                                            <div class="step-number" x-text="index + 1"></div>
                                            <div class="step-title" x-text="step.title"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wizard Steps -->
                    <div class="wizard-content">
                        <!-- Step 1: Client Information -->
                        <div class="wizard-step" x-show="currentStep === 0" x-transition>
                            @include('inspections.sections.client-information', ['inspection' => $inspection])
                        </div>

                        <!-- Step 2: Services & Examination -->
                        <div class="wizard-step" x-show="currentStep === 1" x-transition>
                            @include('inspections.sections.add-service', ['inspection' => $inspection])
                            @include('inspections.sections.testing-methods', ['inspection' => $inspection])
                            @include('inspections.sections.lifting-examination', ['inspection' => $inspection])
                            @include('inspections.sections.load-test', ['inspection' => $inspection])
                            @include('inspections.sections.mpi-service', ['inspection' => $inspection])
                            @include('inspections.sections.thorough-examination', ['inspection' => $inspection])
                            @include('inspections.sections.visual', ['inspection' => $inspection])
                        </div>

                        <!-- Step 3: Equipment Details -->
                        <div class="wizard-step" x-show="currentStep === 2" x-transition>
                            @include('inspections.sections.equipment', ['inspection' => $inspection])
                        </div>

                        <!-- Step 4: Personnel & Assets (Merged with Comments & Images) -->
                        <div class="wizard-step" x-show="currentStep === 3" x-transition>
                            @include('inspections.sections.asset-details', ['inspection' => $inspection])
                            @include('inspections.sections.items-table', ['inspection' => $inspection])
                            @include('inspections.sections.consumables', ['inspection' => $inspection])
                            @include('inspections.sections.personnel-assignment', ['inspection' => $inspection])
                            @include('inspections.sections.comments', ['inspection' => $inspection])
                            @include('inspections.sections.image-upload', ['inspection' => $inspection])
                            @include('inspections.sections.edit-status', ['inspection' => $inspection])
                            @include('inspections.sections.export-section', ['isEdit' => true])
                        </div>
                    </div>

                    <!-- Wizard Navigation Buttons -->
                    <div class="wizard-navigation-buttons mt-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            x-show="currentStep > 0" 
                                            @click="previousStep()"
                                            :disabled="isNavigating">
                                        <i class="fas fa-arrow-left me-2"></i>Previous
                                    </button>
                                    
                                    <div class="d-flex align-items-center">
                                        <span class="text-muted me-3" x-text="`Step ${currentStep + 1} of ${steps.length}`"></span>
                                        <div class="progress me-3" style="width: 200px; height: 8px;">
                                            <div class="progress-bar bg-primary" 
                                                 :style="`width: ${((currentStep + 1) / steps.length) * 100}%`"></div>
                                        </div>
                                        <span class="text-muted small" x-text="`${Math.round(((currentStep + 1) / steps.length) * 100)}% Complete`"></span>
                                    </div>
                                    
                                    <div>
                                        <button type="button" class="btn btn-primary" 
                                                x-show="currentStep < steps.length - 1" 
                                                @click="nextStep()"
                                                :disabled="isNavigating">
                                            Next<i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                        
                                        <button type="submit" class="btn btn-success" 
                                                x-show="currentStep === steps.length - 1"
                                                :disabled="isSubmitting">
                                            <span x-show="!isSubmitting">
                                                <i class="fas fa-save me-2"></i>Update Inspection
                                            </span>
                                            <span x-show="isSubmitting">
                                                <i class="fas fa-spinner fa-spin me-2"></i>Updating...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@include('inspections.modals.add-service-modal')
@include('inspections.modals.lifting-examination-modal')
@include('inspections.modals.mpi-service-modal')
@include('inspections.modals.visual-inspection-modal')
@include('inspections.modals.load-test-modal')
@include('inspections.modals.thorough-examination-modal')
@include('inspections.modals.ultrasonic-test-modal')
@include('inspections.modals.confirmation-modal')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/inspection-form.css') }}">
<style>
/* Wizard Navigation Styles */
.wizard-navigation {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.step-indicators {
    gap: 1rem;
}

.step-indicator {
    flex: 1;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 8px;
    position: relative;
}

.step-indicator:hover {
    background: #f8fafc;
}

.step-indicator.active {
    background: #eff6ff;
    border: 2px solid #3b82f6;
}

.step-indicator.completed {
    background: #f0fdf4;
    border: 2px solid #10b981;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.step-indicator.active .step-number {
    background: #3b82f6;
    color: white;
}

.step-indicator.completed .step-number {
    background: #10b981;
    color: white;
}

.step-indicator.completed .step-number::before {
    content: '✓';
    font-weight: bold;
}

.step-title {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    transition: all 0.3s ease;
}

.step-indicator.active .step-title {
    color: #3b82f6;
    font-weight: 600;
}

.step-indicator.completed .step-title {
    color: #10b981;
    font-weight: 600;
}

/* Wizard Content Styles */
.wizard-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.wizard-step {
    padding: 0;
}

/* Wizard Navigation Buttons */
.wizard-navigation-buttons {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.wizard-navigation-buttons .btn {
    min-width: 120px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.wizard-navigation-buttons .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Progress Bar in Navigation */
.wizard-navigation-buttons .progress {
    border-radius: 4px;
    background: #f1f5f9;
}

.wizard-navigation-buttons .progress-bar {
    background: linear-gradient(45deg, #3b82f6, #1d4ed8);
    border-radius: 4px;
    transition: width 0.3s ease;
}

/* Alpine.js Validation Styles */
.is-invalid-alpine {
    border-color: #dc3545 !important;
    padding-right: calc(1.5em + 0.75rem) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4M6.2 7.4 4.8 6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right calc(0.375em + 0.1875rem) center !important;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
}

.invalid-feedback-alpine {
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
    display: block;
}

/* Responsive Design */
@media (max-width: 768px) {
    .step-indicators {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .step-indicator {
        padding: 0.75rem;
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .step-title {
        font-size: 0.75rem;
    }
    
    .wizard-navigation-buttons .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .wizard-navigation-buttons .btn {
        min-width: auto;
        width: 100%;
    }
}

/* Animation for step transitions */
[x-transition] {
    transition: all 0.3s ease;
}

[x-transition].x-transition-enter {
    opacity: 0;
    transform: translateX(20px);
}

[x-transition].x-transition-enter-active {
    opacity: 1;
    transform: translateX(0);
}

[x-transition].x-transition-leave {
    opacity: 1;
    transform: translateX(0);
}

[x-transition].x-transition-leave-active {
    opacity: 0;
    transform: translateX(-20px);
}

/* Hide completed step numbers, show checkmark */
.step-indicator.completed .step-number span {
    display: none;
}

/* Enhanced Focus States */
.step-indicator:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.btn:focus {
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
}

/* Loading state for buttons */
.btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/inspection-form-simple.js') }}"></script>
<script>
    // Set edit mode and current inspector data before DOM loads
    window.isEditMode = true;
    window.currentInspector = @json($inspection->lead_inspector_name ?? '');
    window.currentInspectorCertification = @json($inspection->lead_inspector_certification ?? '');
    window.currentClient = @json($inspection->client?->client_name ?? '');
    
    // Populate existing data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        @if($inspection->services->isNotEmpty())
            // Populate services
                    const services = {!! json_encode($inspection->services->map(function($service) {
                return [
                    'type' => $service->service_type,
                    'name' => $service->service_name ?? $service->service_type_name,
                    'description' => $service->service_description,
                    'parameters' => $service->test_parameters,
                    'criteria' => $service->acceptance_criteria,
                    'codes' => $service->applicable_codes,
                    'duration' => $service->estimated_duration,
                    'cost' => $service->cost_estimate,
                ];
            })) !!};
            
            if (window.populateServices) {
                window.populateServices(services);
            }
        @endif
        
        @if($inspection->personnelAssignments->isNotEmpty())
            // Populate personnel
            const personnel = {!! json_encode($inspection->personnelAssignments->map(function($person) {
                return [
                    'name' => $person->personnel_name,
                    'role' => $person->role_position,
                    'certification' => $person->certification_level,
                    'cert_number' => $person->certification_number,
                    'cert_expiry' => $person->certification_expiry?->format('Y-m-d'),
                    'services' => $person->assigned_services,
                    'contact' => $person->contact_information,
                    'start_time' => $person->availability_start,
                    'end_time' => $person->availability_end,
                    'rate' => $person->hourly_rate,
                ];
            })) !!};
            
            if (window.populatePersonnel) {
                window.populatePersonnel(personnel);
            }
        @endif
        
        @if($inspection->consumableAssignments->isNotEmpty())
            // Populate consumables
            const consumables = {!! json_encode($inspection->consumableAssignments->map(function($item) {
                return [
                    'name' => $item->consumable_name,
                    'type' => $item->consumable_type,
                    'brand' => $item->brand_manufacturer,
                    'code' => $item->product_code,
                    'batch' => $item->batch_lot_number,
                    'expiry' => $item->expiry_date?->format('Y-m-d'),
                    'quantity' => $item->quantity_used,
                    'unit' => $item->unit,
                    'cost' => $item->unit_cost,
                    'supplier' => $item->supplier,
                    'services' => $item->assigned_services,
                    'condition' => $item->condition,
                    'notes' => $item->notes,
                ];
            })) !!};
            
            if (window.populateConsumables) {
                window.populateConsumables(consumables);
            }
        @endif
        
        @php
            // Get images using the new relationship
            $existingImages = $inspection->images_for_edit ?? collect();
            $hasImages = count($existingImages) > 0;
        @endphp
        
        @if($hasImages)
            <script>
            // Populate images using available format
            const images = {!! json_encode($existingImages) !!};
            console.log('Images data from PHP:', images);
            
            // Function to wait for populateImages to be available
            function waitForPopulateImages() {
                if (window.populateImages) {
                    console.log('populateImages function found, calling it...');
                    window.populateImages(images);
                } else {
                    console.log('populateImages function not yet available, waiting...');
                    setTimeout(waitForPopulateImages, 100);
                }
            }
            
            // Start waiting for the function
            waitForPopulateImages();
            </script>
        @endif
    });
</script>
@endpush

<!-- Alpine.js Wizard Component -->
<script>
    function inspectionWizard() {
        return {
            currentStep: 0,
            isNavigating: false,
            isSubmitting: false,
            hasAttemptedSubmit: @json($errors->any()),
            errors: {},
            touchedFields: {},
            
            steps: [
                { title: 'Client Information', id: 'basic-info' },
                { title: 'Services & Examination', id: 'services-testing' },
                { title: 'Equipment Details', id: 'equipment-assets' },
                { title: 'Personnel & Assets', id: 'personnel-assets' }
            ],

            // Initialize the component
            init() {
                console.log('Initializing inspection wizard...');
                // If there are Laravel validation errors, mark as attempted submit
                if (@json($errors->any())) {
                    this.hasAttemptedSubmit = true;
                    console.log('Form has backend validation errors, showing them');
                }
                
                // Update progress bar
                this.updateProgress();
            },

            // Validation rules matching backend
            rules: {
                inspection_number: { required: true, maxLength: 255 },
                client_id: { required: true },
                location: { required: true, maxLength: 255 },
                equipment_type: { required: true, maxLength: 255 },
                equipment_description: { required: true, maxLength: 255 },
                inspection_date: { required: true, type: 'date' },
                overall_result: { required: true, options: ['pass', 'fail', 'conditional_pass'] },
                lead_inspector_name: { required: true, maxLength: 255 },
                report_date: { required: true, type: 'date' },
                temperature: { type: 'number', min: -50, max: 100 },
                humidity: { type: 'number', min: 0, max: 100 },
                manufacture_year: { type: 'number', min: 1900, max: new Date().getFullYear() + 5 }
            },

            // Navigation methods
            goToStep(stepIndex) {
                if (this.isNavigating || stepIndex === this.currentStep) return;
                
                // Validate current step before moving if user has attempted to submit
                if (this.hasAttemptedSubmit && stepIndex > this.currentStep) {
                    if (!this.validateCurrentStep()) {
                        this.showStepValidationError();
                        return;
                    }
                }
                
                this.currentStep = stepIndex;
                this.updateProgress();
                this.scrollToTop();
            },

            nextStep() {
                if (this.currentStep < this.steps.length - 1) {
                    // Validate current step before proceeding
                    if (this.hasAttemptedSubmit && !this.validateCurrentStep()) {
                        this.showStepValidationError();
                        return;
                    }
                    
                    this.isNavigating = true;
                    setTimeout(() => {
                        this.currentStep++;
                        this.updateProgress();
                        this.scrollToTop();
                        this.isNavigating = false;
                    }, 150);
                }
            },

            previousStep() {
                if (this.currentStep > 0) {
                    this.isNavigating = true;
                    setTimeout(() => {
                        this.currentStep--;
                        this.updateProgress();
                        this.scrollToTop();
                        this.isNavigating = false;
                    }, 150);
                }
            },

            // Progress and validation methods
            updateProgress() {
                const progressPercent = ((this.currentStep + 1) / this.steps.length) * 100;
                
                // Update main progress bar if it exists
                const mainProgressBar = document.getElementById('formProgress');
                if (mainProgressBar) {
                    mainProgressBar.style.width = progressPercent + '%';
                    mainProgressBar.setAttribute('aria-valuenow', progressPercent);
                }

                const progressText = document.getElementById('progressText');
                if (progressText) {
                    progressText.textContent = Math.round(progressPercent) + '% Complete';
                }
            },

            isStepCompleted(stepIndex) {
                if (stepIndex < this.currentStep) return true;
                if (stepIndex === this.currentStep && this.hasAttemptedSubmit) {
                    return this.validateStepByIndex(stepIndex);
                }
                return false;
            },

            validateCurrentStep() {
                return this.validateStepByIndex(this.currentStep);
            },

            validateStepByIndex(stepIndex) {
                // Get fields for the specific step
                const stepFields = this.getFieldsForStep(stepIndex);
                const formData = new FormData(document.getElementById('liftingInspectionForm'));
                
                let stepValid = true;
                for (const fieldName of stepFields) {
                    const value = formData.get(fieldName);
                    if (!this.validateFieldForStep(fieldName, value)) {
                        stepValid = false;
                    }
                }
                
                return stepValid;
            },

            getFieldsForStep(stepIndex) {
                switch(stepIndex) {
                    case 0: // Basic Info
                        return ['inspection_number', 'client_id', 'location', 'inspection_date', 'lead_inspector_name'];
                    case 1: // Services & Testing
                        return []; // Services are dynamic, validate separately
                    case 2: // Equipment Type
                        return ['equipment_type', 'equipment_description'];
                    case 3: // Items Details
                        return []; // Personnel and consumables are dynamic
                    case 4: // Final Details
                        return ['overall_result', 'report_date'];
                    default:
                        return [];
                }
            },

            validateFieldForStep(fieldName, value) {
                this.touchedFields[fieldName] = true;
                
                const rule = this.rules[fieldName];
                if (!rule) return true;

                // Clear existing error for this field
                delete this.errors[fieldName];

                if (rule.required && (!value || value.toString().trim() === '')) {
                    this.errors[fieldName] = `${this.getFieldLabel(fieldName)} is required.`;
                    return false;
                }

                if (!value || value.toString().trim() === '') return true;

                if (rule.maxLength && value.length > rule.maxLength) {
                    this.errors[fieldName] = `${this.getFieldLabel(fieldName)} must not exceed ${rule.maxLength} characters.`;
                    return false;
                }

                if (rule.type === 'number') {
                    const numValue = parseFloat(value);
                    if (isNaN(numValue)) {
                        this.errors[fieldName] = `${this.getFieldLabel(fieldName)} must be a valid number.`;
                        return false;
                    }
                    if (rule.min !== undefined && numValue < rule.min) {
                        this.errors[fieldName] = `${this.getFieldLabel(fieldName)} must be at least ${rule.min}.`;
                        return false;
                    }
                    if (rule.max !== undefined && numValue > rule.max) {
                        this.errors[fieldName] = `${this.getFieldLabel(fieldName)} must not exceed ${rule.max}.`;
                        return false;
                    }
                }

                if (rule.type === 'date' && value) {
                    const date = new Date(value);
                    if (isNaN(date.getTime())) {
                        this.errors[fieldName] = `${this.getFieldLabel(fieldName)} must be a valid date.`;
                        return false;
                    }
                }

                if (rule.options && !rule.options.includes(value)) {
                    this.errors[fieldName] = `Please select a valid ${this.getFieldLabel(fieldName).toLowerCase()}.`;
                    return false;
                }

                return true;
            },

            // Field validation methods (same as before)
            validateField(fieldName, value) {
                return this.validateFieldForStep(fieldName, value);
            },

            shouldShowError(fieldName) {
                return this.hasAttemptedSubmit || this.touchedFields[fieldName];
            },

            getFieldLabel(fieldName) {
                const labels = {
                    inspection_number: 'Report Number',
                    client_id: 'Client',
                    location: 'Location',
                    equipment_type: 'Equipment Type',
                    equipment_description: 'Equipment Description',
                    inspection_date: 'Inspection Date',
                    overall_result: 'Overall Result',
                    lead_inspector_name: 'Lead Inspector Name',
                    report_date: 'Report Date',
                    temperature: 'Temperature',
                    humidity: 'Humidity',
                    manufacture_year: 'Manufacture Year'
                };
                return labels[fieldName] || fieldName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            },

            validateAndSubmit(event) {
                this.hasAttemptedSubmit = true;
                
                if (this.isSubmitting) {
                    event.preventDefault();
                    return;
                }

                // Validate all steps
                if (!this.validateAllSteps()) {
                    event.preventDefault();
                    
                    // Find first step with errors and navigate to it
                    for (let i = 0; i < this.steps.length; i++) {
                        if (!this.validateStepByIndex(i)) {
                            this.goToStep(i);
                            break;
                        }
                    }

                    this.showErrorNotification();
                    return;
                }

                this.isSubmitting = true;
            },

            validateAllSteps() {
                this.errors = {};
                let allValid = true;
                
                for (let i = 0; i < this.steps.length; i++) {
                    if (!this.validateStepByIndex(i)) {
                        allValid = false;
                    }
                }
                
                return allValid;
            },

            showStepValidationError() {
                const stepName = this.steps[this.currentStep].title;
                const message = `Please complete all required fields in the "${stepName}" step before proceeding.`;
                
                this.showNotification(message, 'warning');
            },

            showErrorNotification() {
                const errorCount = Object.keys(this.errors).length;
                const message = `Please fix ${errorCount} validation error${errorCount > 1 ? 's' : ''} before submitting.`;
                
                this.showNotification(message, 'danger');
            },

            showNotification(message, type = 'info') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                const container = document.querySelector('.container');
                if (container) {
                    container.insertAdjacentHTML('afterbegin', alertHtml);
                    
                    // Auto-dismiss after 5 seconds
                    setTimeout(() => {
                        const alert = container.querySelector('.alert');
                        if (alert) {
                            alert.remove();
                        }
                    }, 5000);
                }
            },

            scrollToTop() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        };
    }
</script>

