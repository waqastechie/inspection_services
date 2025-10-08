{{-- Step 4: Equipment and Items Management --}}
<div class="step-content">
    <!-- Reason for Examination Information Box -->
    <div class="alert alert-primary mb-3">
        <h6><i class="fas fa-info-circle me-2"></i>Reference Information</h6>
        <div class="row">
            <div class="col-md-6">
                <small>
                    <strong>Reason for Examination:</strong><br>
                    <strong>A:</strong> New Installation<br>
                    <strong>B:</strong> 6 Monthly<br>
                    <strong>C:</strong> 12 Monthly<br>
                    <strong>D:</strong> Written Scheme<br>
                    <strong>E:</strong> Exceptional Circumstances
                </small>
            </div>
            <div class="col-md-6">
                <small>
                    <strong>Status Reference:</strong><br>
                    <strong>ND</strong> – No Defect<br>
                    <strong>SDR</strong> – See Defect Report<br>
                    <strong>NF</strong> – Not Found<br>
                    <strong>OBS</strong> – Observation (see Defect Report)
                </small>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <h5><i class="fas fa-tools me-2"></i>Equipment Items Management</h5>
        <p>Manage inspection items with their equipment types and detailed specifications.</p>
    </div>
    
    @include('inspections.sections.equipment-step4', [
        'inspection' => $inspection ?? null,
        'equipment' => $equipment ?? []
    ])
</div>