{{-- Job Details Section --}}
<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">
            <i class="fas fa-briefcase me-2"></i>Job Details
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Contract -->
            <div class="col-md-6 mb-3">
                <label for="contract" class="form-label">Contract</label>
                <input type="text" 
                       class="form-control @error('contract') is-invalid @enderror" 
                       id="contract" 
                       name="contract" 
                       value="{{ old('contract', $inspection->contract ?? '') }}"
                       placeholder="Contract reference">
                @error('contract')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Work Order -->
            <div class="col-md-6 mb-3">
                <label for="work_order" class="form-label">Work Order</label>
                <input type="text" 
                       class="form-control @error('work_order') is-invalid @enderror" 
                       id="work_order" 
                       name="work_order" 
                       value="{{ old('work_order', $inspection->work_order ?? '') }}"
                       placeholder="Work order number">
                @error('work_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Purchase Order -->
            <div class="col-md-6 mb-3">
                <label for="purchase_order" class="form-label">Purchase Order</label>
                <input type="text" 
                       class="form-control @error('purchase_order') is-invalid @enderror" 
                       id="purchase_order" 
                       name="purchase_order" 
                       value="{{ old('purchase_order', $inspection->purchase_order ?? '') }}"
                       placeholder="Purchase order number">
                @error('purchase_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Client Job Reference -->
            <div class="col-md-6 mb-3">
                <label for="client_job_reference" class="form-label">Client Job Reference</label>
                <input type="text" 
                       class="form-control @error('client_job_reference') is-invalid @enderror" 
                       id="client_job_reference" 
                       name="client_job_reference" 
                       value="{{ old('client_job_reference', $inspection->client_job_reference ?? '') }}"
                       placeholder="Client job reference">
                @error('client_job_reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Rig -->
            <div class="col-md-6 mb-3">
                <label for="rig" class="form-label">Rig</label>
                <input type="text" 
                       class="form-control @error('rig') is-invalid @enderror" 
                       id="rig" 
                       name="rig" 
                       value="{{ old('rig', $inspection->rig ?? '') }}"
                       placeholder="Rig name/number">
                @error('rig')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Report Number -->
            <div class="col-md-6 mb-3">
                <label for="report_number" class="form-label">Report Number</label>
                <input type="text" 
                       class="form-control @error('report_number') is-invalid @enderror" 
                       id="report_number" 
                       name="report_number" 
                       value="{{ old('report_number', $inspection->report_number ?? '') }}"
                       placeholder="Report number">
                @error('report_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Revision -->
            <div class="col-md-6 mb-3">
                <label for="revision" class="form-label">Revision</label>
                <input type="text" 
                       class="form-control @error('revision') is-invalid @enderror" 
                       id="revision" 
                       name="revision" 
                       value="{{ old('revision', $inspection->revision ?? '1.0') }}"
                       placeholder="Revision number">
                @error('revision')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Area of Examination -->
            <div class="col-md-6 mb-3">
                <label for="area_of_examination" class="form-label">Area of Examination</label>
                <input type="text" 
                       class="form-control @error('area_of_examination') is-invalid @enderror" 
                       id="area_of_examination" 
                       name="area_of_examination" 
                       value="{{ old('area_of_examination', $inspection->area_of_examination ?? '') }}"
                       placeholder="Area to be examined">
                @error('area_of_examination')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Standards -->
            <div class="col-md-6 mb-3">
                <label for="standards" class="form-label">Standards</label>
                <input type="text" 
                       class="form-control @error('standards') is-invalid @enderror" 
                       id="standards" 
                       name="standards" 
                       value="{{ old('standards', $inspection->standards ?? '') }}"
                       placeholder="e.g., DNV 2.7-1">
                @error('standards')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Local Procedure Number -->
            <div class="col-md-6 mb-3">
                <label for="local_procedure_number" class="form-label">Local Procedure Number</label>
                <input type="text" 
                       class="form-control @error('local_procedure_number') is-invalid @enderror" 
                       id="local_procedure_number" 
                       name="local_procedure_number" 
                       value="{{ old('local_procedure_number', $inspection->local_procedure_number ?? '') }}"
                       placeholder="Local procedure number">
                @error('local_procedure_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Drawing Number -->
            <div class="col-md-6 mb-3">
                <label for="drawing_number" class="form-label">Drawing Number</label>
                <input type="text" 
                       class="form-control @error('drawing_number') is-invalid @enderror" 
                       id="drawing_number" 
                       name="drawing_number" 
                       value="{{ old('drawing_number', $inspection->drawing_number ?? '') }}"
                       placeholder="Drawing reference number">
                @error('drawing_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Test Restrictions -->
            <div class="col-md-6 mb-3">
                <label for="test_restrictions" class="form-label">Test Restrictions</label>
                <input type="text" 
                       class="form-control @error('test_restrictions') is-invalid @enderror" 
                       id="test_restrictions" 
                       name="test_restrictions" 
                       value="{{ old('test_restrictions', $inspection->test_restrictions ?? '') }}"
                       placeholder="Any test restrictions">
                @error('test_restrictions')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Surface Condition -->
            <div class="col-md-12 mb-3">
                <label for="surface_condition" class="form-label">Surface Condition</label>
                <textarea class="form-control @error('surface_condition') is-invalid @enderror" 
                          id="surface_condition" 
                          name="surface_condition" 
                          rows="3" 
                          placeholder="Describe surface condition">{{ old('surface_condition', $inspection->surface_condition ?? '') }}</textarea>
                @error('surface_condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>