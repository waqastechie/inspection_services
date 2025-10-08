{{-- Step 2: Equipment Details --}}
<div class="step-content">
    <div class="alert alert-info">
        <h5><i class="fas fa-cog me-2"></i>Equipment Details</h5>
        <p>Configure equipment information for this inspection.</p>
    </div>
    
    @include('inspections.sections.equipment-step2', [
        'inspection' => $inspection ?? null,
        'equipment' => $equipment ?? []
    ])
</div>