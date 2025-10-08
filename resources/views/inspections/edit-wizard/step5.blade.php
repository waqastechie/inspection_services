{{-- Step 5: Consumables --}}
<div class="step-content">
    <div class="alert alert-info">
        <h5><i class="fas fa-flask me-2"></i>Consumables Assignment</h5>
        <p>Assign consumables and materials needed for this inspection.</p>
    </div>
    
    <div class="row">
        <div class="col-12">
            @include('inspections.sections.consumables', [
                'inspection' => $inspection ?? null,
                'consumables' => $consumables ?? []
            ])
        </div>
    </div>
</div>