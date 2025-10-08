{{-- Step 6: Images & Documentation --}}
<div class="step-content">
    <div class="alert alert-info">
        <h5><i class="fas fa-camera me-2"></i>Images & Documentation</h5>
        <p>Upload images and documentation for this inspection.</p>
    </div>
    
    @include('inspections.sections.image-upload', [
        'inspection' => $inspection ?? null
    ])
</div>