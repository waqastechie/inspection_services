{{-- Client Information Section --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-building me-2"></i>Client Information
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Client Selection -->
            <div class="col-md-6 mb-3">
                <label for="client_id" class="form-label required">Client</label>
                <select class="form-select @error('client_id') is-invalid @enderror" 
                        id="client_id" name="client_id" required>
                    <option value="">Select Client</option>
                    @if(isset($clients) && $clients->count() > 0)
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" 
                                    {{ (old('client_id', $inspection->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                                {{ $client->display_name }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>No clients found - Please add clients first</option>
                    @endif
                </select>
                @error('client_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Inspection Date -->
            <div class="col-md-6 mb-3">
                <label for="inspection_date" class="form-label required">Inspection Date</label>
                <input type="date" 
                       class="form-control @error('inspection_date') is-invalid @enderror" 
                       id="inspection_date" 
                       name="inspection_date" 
                       value="{{ old('inspection_date', $inspection->inspection_date ?? now()->format('Y-m-d')) }}" 
                       required>
                @error('inspection_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Location -->
            <div class="col-md-6 mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" 
                       class="form-control @error('location') is-invalid @enderror" 
                       id="location" 
                       name="location" 
                       value="{{ old('location', $inspection->location ?? '') }}"
                       placeholder="Inspection location">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Inspector -->
            <div class="col-md-6 mb-3">
                <label for="inspector_id" class="form-label">Inspector</label>
                <select class="form-select @error('inspector_id') is-invalid @enderror" 
                        id="inspector_id" name="inspector_id">
                    <option value="">Select Inspector</option>
                    @if(isset($users) && $users->count() > 0)
                        @foreach($users as $inspector)
                            <option value="{{ $inspector->id }}" 
                                    {{ (old('inspector_id', $inspection->inspector_id ?? auth()->id()) == $inspector->id) ? 'selected' : '' }}>
                                {{ $inspector->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>No inspectors found</option>
                    @endif
                </select>
                @error('inspector_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Project Reference -->
            <div class="col-md-6 mb-3">
                <label for="project_reference" class="form-label">Project Reference</label>
                <input type="text" 
                       class="form-control @error('project_reference') is-invalid @enderror" 
                       id="project_reference" 
                       name="project_reference" 
                       value="{{ old('project_reference', $inspection->project_reference ?? '') }}"
                       placeholder="Project or job reference">
                @error('project_reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Inspection Type -->
            <div class="col-md-6 mb-3">
                <label for="inspection_type" class="form-label">Inspection Type</label>
                <select class="form-select @error('inspection_type') is-invalid @enderror" 
                        id="inspection_type" name="inspection_type">
                    <option value="">Select Type</option>
                    <option value="lifting_examination" {{ (old('inspection_type', $inspection->inspection_type ?? '') == 'lifting_examination') ? 'selected' : '' }}>
                        Lifting Equipment Examination
                    </option>
                    <option value="mpi" {{ (old('inspection_type', $inspection->inspection_type ?? '') == 'mpi') ? 'selected' : '' }}>
                        Magnetic Particle Inspection (MPI)
                    </option>
                    <option value="visual" {{ (old('inspection_type', $inspection->inspection_type ?? '') == 'visual') ? 'selected' : '' }}>
                        Visual Inspection
                    </option>
                    <option value="thorough_examination" {{ (old('inspection_type', $inspection->inspection_type ?? '') == 'thorough_examination') ? 'selected' : '' }}>
                        Thorough Examination
                    </option>
                    <option value="load_test" {{ (old('inspection_type', $inspection->inspection_type ?? '') == 'load_test') ? 'selected' : '' }}>
                        Load Test
                    </option>
                </select>
                @error('inspection_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="row">
            <div class="col-12 mb-3">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" 
                          name="notes" 
                          rows="3" 
                          placeholder="Any additional notes about this inspection">{{ old('notes', $inspection->notes ?? '') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if(isset($inspection) && $inspection->inspection_number)
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Inspection Number:</strong> {{ $inspection->inspection_number }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-secondary">
                        <i class="fas fa-calendar me-2"></i>
                        <strong>Status:</strong> 
                        <span class="badge bg-{{ $inspection->status === 'draft' ? 'warning' : ($inspection->status === 'completed' ? 'success' : 'primary') }}">
                            {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>