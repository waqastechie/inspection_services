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
                
                <div class="header-section mb-5">
                    <!-- Modern Header Card -->
                    <div class="header-card shadow-lg border-0" style="border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative;">
                        <!-- Background Pattern -->
                        <div class="header-pattern" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px), radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 30px 30px;"></div>
                        
                        <div class="header-content" style="position: relative; z-index: 2; padding: 2.5rem;">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="header-text">
                                        <!-- Main Title -->
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="header-icon" style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-right: 1.5rem;">
                                                <i class="fas fa-edit text-white" style="font-size: 2rem;"></i>
                                            </div>
                                            <div>
                                                <h1 class="text-white mb-2 fw-bold" style="font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                    Edit Inspection Report
                                                </h1>
                                                <div class="header-subtitle d-flex align-items-center">
                                                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold" style="font-size: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                                        <i class="fas fa-hashtag me-2"></i>{{ $inspection->inspection_number }}
                                                    </span>
                                                    <span class="text-white ms-3 opacity-90" style="font-size: 1.1rem;">
                                                        <i class="fas fa-calendar-alt me-2"></i>
                                                        {{ $inspection->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Status and Client Info -->
                                        <div class="header-meta d-flex flex-wrap gap-3 align-items-center">
                                            @if($inspection->status)
                                            <div class="status-chip">
                                                @php
                                                    $statusConfig = [
                                                        'draft' => ['color' => 'warning', 'icon' => 'fa-edit', 'text' => 'Draft'],
                                                        'in_progress' => ['color' => 'info', 'icon' => 'fa-spinner', 'text' => 'In Progress'],
                                                        'completed' => ['color' => 'success', 'icon' => 'fa-check', 'text' => 'Completed'],
                                                        'cancelled' => ['color' => 'danger', 'icon' => 'fa-times', 'text' => 'Cancelled']
                                                    ];
                                                    $config = $statusConfig[$inspection->status] ?? ['color' => 'secondary', 'icon' => 'fa-question', 'text' => ucfirst($inspection->status)];
                                                @endphp
                                                <span class="badge bg-{{ $config['color'] }} px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                                                    <i class="fas {{ $config['icon'] }} me-2"></i>{{ $config['text'] }}
                                                </span>
                                            </div>
                                            @endif
                                            
                                            @if($inspection->client_name)
                                            <div class="client-chip">
                                                <span class="badge bg-dark bg-opacity-25 text-white px-3 py-2 rounded-pill" style="font-size: 0.9rem; backdrop-filter: blur(10px);">
                                                    <i class="fas fa-building me-2"></i>{{ $inspection->client_name }}
                                                </span>
                                            </div>
                                            @endif
                                            
                                            @if($inspection->lead_inspector_name)
                                            <div class="inspector-chip">
                                                <span class="badge bg-dark bg-opacity-25 text-white px-3 py-2 rounded-pill" style="font-size: 0.9rem; backdrop-filter: blur(10px);">
                                                    <i class="fas fa-user-tie me-2"></i>{{ $inspection->lead_inspector_name }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 text-end">
                                    <div class="header-actions">
                                        <!-- Back Button -->
                                        <a href="{{ route('inspections.show', $inspection->id) }}" 
                                           class="btn btn-white btn-lg px-4 py-3 rounded-pill shadow-sm" 
                                           style="backdrop-filter: blur(10px); background: rgba(255,255,255,0.9); border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s ease;">
                                            <i class="fas fa-arrow-left me-2"></i>Back to View
                                        </a>
                                        
                                        <!-- Progress Indicator -->
                                        <div class="progress-indicator mt-3" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 12px; padding: 1rem;">
                                            <div class="text-white text-center">
                                                <small class="opacity-90 d-block mb-2">Edit Progress</small>
                                                <div class="progress" style="height: 8px; border-radius: 4px; background: rgba(255,255,255,0.3);">
                                                    <div class="progress-bar bg-success" style="width: 75%; transition: width 0.3s ease;"></div>
                                                </div>
                                                <small class="opacity-75 mt-2 d-block">Auto-saved</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="header-decoration" style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.6;"></div>
                        <div class="header-decoration" style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.4;"></div>
                    </div>
                    
                    <!-- Quick Info Bar -->
                    <div class="quick-info-bar mt-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-clock text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Last Modified</h6>
                                        <p>{{ $inspection->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-list-alt text-success"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Services</h6>
                                        <p>{{ $inspection->services->count() }} Services</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-users text-info"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Personnel</h6>
                                        <p>{{ $inspection->personnelAssignments->count() }} Assigned</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-images text-warning"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Images</h6>
                                        <p>{{ is_array($inspection->inspection_images) ? count($inspection->inspection_images) : 0 }} Photos</p>
                                    </div>
                                </div>
                            </div>
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

                <form id="liftingInspectionForm" method="POST" action="{{ route('inspections.update', $inspection->id) }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    
                    @include('inspections.sections.client-information', ['inspection' => $inspection])
                    
                    @include('inspections.sections.add-service', ['inspection' => $inspection])
                    
                    @include('inspections.sections.lifting-examination', ['inspection' => $inspection])
                    
                    @include('inspections.sections.load-test', ['inspection' => $inspection])
                    
                    @include('inspections.sections.mpi-service', ['inspection' => $inspection])
                    
                    @include('inspections.sections.thorough-examination', ['inspection' => $inspection])
                    
                    @include('inspections.sections.visual', ['inspection' => $inspection])
                    
                    @include('inspections.sections.equipment', ['inspection' => $inspection])
                    
                    @include('inspections.sections.asset-details', ['inspection' => $inspection])
                    
                    @include('inspections.sections.items-table', ['inspection' => $inspection])
                    
                    @include('inspections.sections.consumables', ['inspection' => $inspection])
                    
                    @include('inspections.sections.personnel-assignment', ['inspection' => $inspection])
                    
                    @include('inspections.sections.comments', ['inspection' => $inspection])
                    
                    @include('inspections.sections.image-upload', ['inspection' => $inspection])
                    
                    @include('inspections.sections.edit-status', ['inspection' => $inspection])
                    
                    @include('inspections.sections.export-section', ['isEdit' => true])

                </form>
            </div>
        </div>
    </div>
</div>

@include('inspections.modals.add-service-modal')
@include('inspections.modals.lifting-examination-modal')
@include('inspections.modals.mpi-service-modal')
@include('inspections.modals.confirmation-modal')

<!-- New Modal-Based Entry Modals -->
@include('inspections.modals.asset-modal')
@include('inspections.modals.item-modal')
@include('inspections.modals.personnel-new-modal')
@include('inspections.modals.equipment-new-modal')
@include('inspections.modals.consumable-new-modal')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/inspection-form.css') }}">
<link rel="stylesheet" href="{{ asset('css/auto-save.css') }}">
<style>
/* Header Card Styles */
.header-card {
    position: relative;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.header-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

/* Button Hover Effects */
.btn-white:hover {
    background: rgba(255,255,255,1) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Quick Info Bar Styles */
.quick-info-bar {
    margin-top: 2rem;
}

.info-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    height: 100%;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    border-color: #3b82f6;
}

.info-card .info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

.info-card:hover .info-icon {
    transform: scale(1.1);
    background: #eff6ff;
}

.info-card h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-card p {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

/* Status and Badge Styles */
.badge {
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 2px solid rgba(255,255,255,0.3);
}

/* Progress Bar Animation */
.progress-bar {
    background: linear-gradient(45deg, #10b981, #059669) !important;
    border-radius: 4px;
    position: relative;
    overflow: hidden;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Header Text Shadow */
.text-white {
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

/* Backdrop Filter Support */
@supports (backdrop-filter: blur(10px)) {
    .header-icon,
    .progress-indicator,
    .client-chip .badge,
    .inspector-chip .badge {
        backdrop-filter: blur(10px);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        padding: 1.5rem !important;
    }
    
    .header-text h1 {
        font-size: 2rem !important;
    }
    
    .header-meta {
        margin-top: 1rem;
    }
    
    .header-actions {
        margin-top: 1.5rem;
        text-align: center !important;
    }
    
    .quick-info-bar {
        margin-top: 1rem;
    }
    
    .info-card {
        padding: 1rem;
        margin-bottom: 1rem;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced Focus States */
.btn:focus {
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
}

/* Smooth Transitions */
* {
    transition: all 0.3s ease;
}

/* Header Pattern Animation */
.header-pattern {
    animation: patternMove 20s linear infinite;
}

@keyframes patternMove {
    0% { background-position: 0 0; }
    100% { background-position: 30px 30px; }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/inspection-form.js') }}"></script>
<script>
    // Set edit mode and current inspector data before DOM loads
    window.isEditMode = true;
    window.currentInspector = @json($inspection->lead_inspector_name ?? '');
    window.currentInspectorCertification = @json($inspection->lead_inspector_certification ?? '');
    window.currentClient = @json($inspection->client_name ?? '');
    
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
                    'status' => $service->status,
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
        
        @if($inspection->inspection_images)
            // Populate images
            const images = {!! json_encode($inspection->inspection_images) !!};
            if (images && Array.isArray(images) && window.populateImages) {
                window.populateImages(images);
            }
        @endif
    });
</script>
@endpush
