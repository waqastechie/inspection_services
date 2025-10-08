{{-- Step 2: Services & Examinations --}}
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Services Selection
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.edit-wizard-sections.add-service', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-weight-hanging me-2"></i>
                    Lifting Examination
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.edit-wizard-sections.lifting-examination', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-dumbbell me-2"></i>
                    Load Test
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.edit-wizard-sections.load-test', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-magnet me-2"></i>
                    MPI Service
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.edit-wizard-sections.mpi-service', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-search me-2"></i>
                    Thorough Examination
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.edit-wizard-sections.thorough-examination', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Visual Inspection
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.edit-wizard-sections.visual', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Other Tests
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.edit-wizard-sections.other-services', ['inspection' => $inspection])
            </div>
        </div>
    </div>
</div>