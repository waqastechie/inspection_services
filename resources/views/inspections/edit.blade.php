@extends('layouts.app')

@section('title', 'Edit Inspection Report - Professional Inspection Services')

@php
    $showProgress = true;
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="form-container">
                
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

                @auth
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-user me-2"></i>
                        Logged in as: {{ auth()->user()->name }} ({{ auth()->user()->email }})
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @else
                    <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Warning: You are not logged in. Please <a href="{{ route('login') }}">login</a> to submit inspections.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endauth

                <form id="liftingInspectionForm" method="POST" action="{{ route('inspections.update', $inspection->id) }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    
                    @include('inspections.sections.client-information', ['inspection' => $inspection])
                    
                    @include('inspections.sections.job-details', ['inspection' => $inspection])
                    
                    @include('inspections.sections.add-service', ['inspection' => $inspection])
                    
                    @include('inspections.sections.lifting-examination', ['inspection' => $inspection])
                    
                    @include('inspections.sections.load-test', ['inspection' => $inspection])
                    
                    @include('inspections.sections.mpi-service', ['inspection' => $inspection])
                    
                    @include('inspections.sections.thorough-examination', ['inspection' => $inspection])
                    
                    @include('inspections.sections.visual', ['inspection' => $inspection])
                    
                    @include('inspections.sections.equipment-details', ['inspection' => $inspection])
                    
                    @include('inspections.sections.asset-details', ['inspection' => $inspection])
                    
                    @include('inspections.sections.items-table', ['inspection' => $inspection])
                    
                    @include('inspections.sections.consumables', ['inspection' => $inspection])
                    
                    @include('inspections.sections.comments', ['inspection' => $inspection])
                    
                    @include('inspections.sections.image-upload', ['inspection' => $inspection])

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Update Inspection
                        </button>
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
<link rel="stylesheet" href="{{ asset('css/auto-save.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/auto-save.js') }}"></script>
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

