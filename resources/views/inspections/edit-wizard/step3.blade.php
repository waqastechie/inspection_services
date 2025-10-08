{{-- Step 3: Add Service --}}
<div class="step-content">
    <div class="alert alert-info">
        <h5><i class="fas fa-plus me-2"></i>Add Service</h5>
        <p>Select and configure the service type for this inspection.</p>
    </div>
    
    @include('inspections.sections.add-service', [
        'inspection' => $inspection ?? null
    ])
</div>