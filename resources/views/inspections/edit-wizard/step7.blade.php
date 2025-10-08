{{-- Step 7: Final Comments & Recommendations --}}
<div class="step-content">
    <div class="alert alert-info">
        <h5><i class="fas fa-comments me-2"></i>Final Comments & Recommendations</h5>
        <p>Add final comments, recommendations, and environmental conditions for this inspection.</p>
    </div>
    
    @include('inspections.sections.comments', [
        'inspection' => $inspection ?? null
    ])
    
    @include('inspections.sections.environmental', [
        'inspection' => $inspection ?? null
    ])
</div>