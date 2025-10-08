{{-- Step 1: Client Information & Job Details --}}
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    Client Information
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.client-information', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-briefcase me-2"></i>
                    Job Details
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.job-details', ['inspection' => $inspection])
            </div>
        </div>
    </div>
</div>