{{-- Step 3: Equipment & Asset Details --}}
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>
                    Equipment Details
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.equipment-details', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>
                    Asset Details
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.asset-details', ['inspection' => $inspection])
            </div>
        </div>
    </div>
</div>