@extends('layouts.app')

@section('title', 'Create Inspection Report - Step by Step')

@section('content')
@php($wizardMode = true)
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Create Inspection Report
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Progress Bar -->
                    <div class="progress-wrapper mb-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"
                                         aria-valuenow="{{ $currentStep }}" 
                                         aria-valuemin="1" 
                                         aria-valuemax="{{ $totalSteps }}">
                                        Step {{ $currentStep }} of {{ $totalSteps }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step Indicators -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    @foreach($steps as $index => $step)
                                        <div class="text-center flex-fill">
                                            <div class="step-indicator {{ $index + 1 <= $currentStep ? 'active' : '' }} {{ $index + 1 < $currentStep ? 'completed' : '' }}">
                                                <span class="step-number">{{ $index + 1 }}</span>
                                            </div>
                                            <small class="step-label">{{ $step['title'] }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Step Content -->
                    <div class="step-content">
                        <h5 class="mb-3">{{ $steps[$currentStep - 1]['title'] }}</h5>
                        <p class="text-muted mb-4">{{ $steps[$currentStep - 1]['description'] }}</p>
                        
                        <form method="POST" action="{{ route('inspections.wizard.save') }}" enctype="multipart/form-data" id="wizardForm">
                            @csrf
                            <input type="hidden" name="step" value="{{ $currentStep }}">
                            @if(isset($inspection))
                                <input type="hidden" name="inspection_id" value="{{ $inspection->id }}">
                            @endif
                            
                            @include($steps[$currentStep - 1]['view'], [
                                'inspection' => $inspection ?? null,
                                'clients' => $clients ?? [],
                                'users' => $users ?? [],
                                'personnel' => $personnel ?? [],
                                'consumables' => $consumables ?? []
                            ])
                            
                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <div>
                                    @if($currentStep > 1)
                                        <a href="{{ route('inspections.wizard.step', ['step' => $currentStep - 1, 'inspection' => $inspection->id ?? null]) }}" 
                                           class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Previous
                                        </a>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($currentStep < $totalSteps)
                                        <button type="submit" class="btn btn-primary">
                                            Save & Continue <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>Submit for QA
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Draft Status Info -->
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Status: 
                                    @if(isset($inspection))
                                        <span class="badge bg-{{ $inspection->status == 'draft' ? 'warning' : ($inspection->status == 'qa' ? 'info' : 'success') }}">
                                            {{ ucfirst($inspection->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                    - Your progress is automatically saved
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wizardForm = document.getElementById('wizardForm');
    if (wizardForm) {
        wizardForm.addEventListener('submit', function(e) {
            const step = document.querySelector('input[name="step"]').value;
            console.log('Submitting wizard step:', step);
            
            if (step == '2') {
                const selectedServicesInput = document.getElementById('selectedServicesInput');
                if (selectedServicesInput) {
                    console.log('Selected services data being submitted:', selectedServicesInput.value);
                } else {
                    console.warn('selectedServicesInput not found in step 2');
                }
            }
            
            // Log all form data for debugging
            const formData = new FormData(wizardForm);
            console.log('All form data being submitted:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
        });
    }
});
</script>
@endsection

{{-- Include Modals --}}
@include('inspections.modals.consumable-new-modal')

@push('styles')
<style>
.step-indicator {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    border: 2px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step-indicator.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.step-indicator.completed {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.step-indicator.completed::after {
    content: "✓";
    font-size: 16px;
    font-weight: bold;
}

.step-indicator.completed .step-number {
    display: none;
}

.step-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.progress-wrapper {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
}

.step-content {
    min-height: 400px;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/inspection-form-simple.js') }}"></script>
<script>
// Equipment management functions - Handled by equipment.blade.php section

function removeEquipment(button) {
    if (confirm('Are you sure you want to remove this equipment?')) {
        button.closest('tr').remove();
    }
}

function clearEquipmentTable() {
    if (confirm('Are you sure you want to clear all equipment entries?')) {
        document.getElementById('equipmentTableBody').innerHTML = '';
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'Good': return 'success';
        case 'Satisfactory': return 'primary';
        case 'Needs Attention': return 'warning';
        case 'Out of Service': return 'danger';
        default: return 'secondary';
    }
}
</script>
@endpush
