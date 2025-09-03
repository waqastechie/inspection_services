<!-- Client & Report Details Section - Based on PDF Format -->
<section class="form-section" id="section-client">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-building"></i>
            Client & Report Details
        </h2>
        <p class="section-description">
            Client information and report identification details
        </p>
    </div>

    <div class="section-content">
        <div class="row g-4">
            <!-- CLIENT DETAILS (Left Column) -->
            <div class="col-md-6">
                <h5 class="mb-3">Client Details</h5>
                
                <!-- Client Selection (Super Admin only) -->
                @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                <div class="mb-3">
                    <label for="clientSelect" class="form-label">Select Client *</label>
                    <select id="clientSelect" name="client_select" class="form-control searchable-dropdown">
                        <option value="">Search and select client...</option>
                        @if(isset($inspection) && $inspection->client_name)
                            <option value="{{ $inspection->client_name }}" selected>{{ $inspection->client_name }}</option>
                        @endif
                    </select>
                    <div class="form-text">Start typing to search for existing clients. The client name will be automatically populated below.</div>
                </div>
                @endif
                
                <!-- Client Name -->
                <div class="mb-3">
                    <label for="clientName" class="form-label">Client Name *</label>
                    <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
                           id="clientName" name="client_name" 
                           value="{{ old('client_name', $inspection?->client_name ?? '') }}" 
                           placeholder="e.g., Saipem Trechville" required>
                    <div class="form-text">This field will be auto-filled when you select a client above.</div>
                    @error('client_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Project Name -->
                <div class="mb-3">
                    <label for="projectName" class="form-label">Project Name *</label>
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror" 
                           id="projectName" name="project_name" 
                           value="{{ old('project_name', $inspection?->project_name ?? '') }}" 
                           placeholder="e.g., Platform Maintenance Project" required>
                    @error('project_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Location of Examination -->
                <div class="mb-3">
                    <label for="location" class="form-label">Location of Examination *</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                           id="location" name="location" 
                           value="{{ old('location', $inspection?->location ?? '') }}" 
                           placeholder="e.g., GEV YARD" required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- REPORT DETAILS (Right Column) -->
            <div class="col-md-6">
                <h5 class="mb-3">Report Details</h5>
                
                <!-- Report Number (Auto-generated) -->
                <div class="mb-3">
                    <label for="reportNumber" class="form-label">Report Number</label>
                    <input type="text" class="form-control" id="reportNumber" 
                           value="Auto-generated on submit" readonly>
                </div>

                <!-- Date of Examination -->
                <div class="mb-3">
                    <label for="inspectionDate" class="form-label">Date of Examination *</label>
                    <input type="date" class="form-control @error('inspection_date') is-invalid @enderror" 
                           id="inspectionDate" name="inspection_date" 
                           value="{{ old('inspection_date', $inspection?->inspection_date?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                    @error('inspection_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Area of Examination -->
                <div class="mb-3">
                    <label for="areaOfExamination" class="form-label">Area of Examination</label>
                    <input type="text" class="form-control" name="area_of_examination" 
                           value="{{ old('area_of_examination', $inspection?->area_of_examination ?? '') }}" 
                           placeholder="e.g., The whole unit and lifting set">
                </div>

                <!-- Services -->
                <div class="mb-3">
                    <label for="services" class="form-label">Services Performed</label>
                    <input type="text" class="form-control" name="services_performed" 
                           value="{{ old('services_performed', $inspection?->services_performed ?? '') }}" 
                           placeholder="e.g., Load Test, Lifting Examination, Thorough Examination, MPI, Visual">
                </div>

                <!-- Weather Conditions -->
                <div class="mb-3">
                    <label for="weatherConditions" class="form-label">Weather Conditions</label>
                    <input type="text" class="form-control" name="weather_conditions" 
                           value="{{ old('weather_conditions', $inspection?->weather_conditions ?? '') }}" 
                           placeholder="e.g., Clear, Overcast, Light Rain">
                </div>

                <!-- Temperature and Humidity -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="temperature" class="form-label">Temperature (°C)</label>
                            <input type="number" class="form-control" name="temperature" 
                                   value="{{ old('temperature', $inspection?->temperature ?? '') }}" 
                                   placeholder="25" step="0.1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="humidity" class="form-label">Humidity (%)</label>
                            <input type="number" class="form-control" name="humidity" 
                                   value="{{ old('humidity', $inspection?->humidity ?? '') }}" 
                                   placeholder="65" min="0" max="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JOB DETAILS Section -->
        <div class="row g-4 mt-4">
            <div class="col-12">
                <h5 class="mb-3">Job Details</h5>
            </div>
            
            <div class="col-md-3">
                <label for="contract" class="form-label">Contract</label>
                <input type="text" class="form-control" name="contract" 
                       value="{{ old('contract', $inspection?->contract ?? '') }}" 
                       placeholder="N/A">
            </div>

            <div class="col-md-3">
                <label for="workOrder" class="form-label">Work Order</label>
                <input type="text" class="form-control" name="work_order" 
                       value="{{ old('work_order', $inspection?->work_order ?? '') }}" 
                       placeholder="GEV-CI 26268">
            </div>

            <div class="col-md-3">
                <label for="purchaseOrder" class="form-label">Purchase Order</label>
                <input type="text" class="form-control" name="purchase_order" 
                       value="{{ old('purchase_order', $inspection?->purchase_order ?? '') }}" 
                       placeholder="N/A">
            </div>

            <div class="col-md-3">
                <label for="clientJobReference" class="form-label">Client Job Reference</label>
                <input type="text" class="form-control" name="client_job_reference" 
                       value="{{ old('client_job_reference', $inspection?->client_job_reference ?? '') }}" 
                       placeholder="OK">
            </div>

            <div class="col-md-4">
                <label for="standards" class="form-label">Standards</label>
                <input type="text" class="form-control" name="standards" 
                       value="{{ old('standards', $inspection?->standards ?? '') }}" 
                       placeholder="DNV 2.7-1">
            </div>

            <div class="col-md-4">
                <label for="localProcedureNumber" class="form-label">Local Procedure Number</label>
                <input type="text" class="form-control" name="local_procedure_number" 
                       value="{{ old('local_procedure_number', $inspection?->local_procedure_number ?? '') }}" 
                       placeholder="TEP 042 - Rev6">
            </div>

            <div class="col-md-4">
                <label for="drawingNumber" class="form-label">Drawing Number</label>
                <input type="text" class="form-control" name="drawing_number" 
                       value="{{ old('drawing_number', $inspection?->drawing_number ?? '') }}" 
                       placeholder="N/A">
            </div>

            <div class="col-md-6">
                <label for="testRestrictions" class="form-label">Test Restrictions</label>
                <input type="text" class="form-control" name="test_restrictions" 
                       value="{{ old('test_restrictions', $inspection?->test_restrictions ?? '') }}" 
                       placeholder="None at time of inspection">
            </div>

            <div class="col-md-6">
                <label for="surfaceCondition" class="form-label">Surface Condition</label>
                <input type="text" class="form-control" name="surface_condition" 
                       value="{{ old('surface_condition', $inspection?->surface_condition ?? '') }}" 
                       placeholder="Acceptable for inspection">
            </div>
        </div>
    </div>
</section>
