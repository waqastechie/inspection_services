{{-- Step 4: Items, Consumables, Comments & Images --}}
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Items Table
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.items-table', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>
                    Consumables
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.consumables', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-comments me-2"></i>
                    Comments
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.comments', ['inspection' => $inspection])
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-images me-2"></i>
                    Image Upload
                </h5>
            </div>
            <div class="card-body">
                @include('inspections.sections.image-upload', ['inspection' => $inspection])
            </div>
        </div>
    </div>
</div>