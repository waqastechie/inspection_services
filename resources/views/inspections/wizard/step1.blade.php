{{-- Step 1: Client Information & Job Details --}}
<div class="step-content">
    @include('inspections.sections.client-information', [
        'clients' => $clients ?? [], 
        'users' => $users ?? [],
        'inspection' => $inspection ?? null
    ])
    
    @include('inspections.sections.job-details', [
        'inspection' => $inspection ?? null
    ])
</div>