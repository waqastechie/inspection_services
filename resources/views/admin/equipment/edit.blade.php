@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Equipment</h2>
                <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Equipment
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Basic Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $equipment->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Type *</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" 
                                           id="type" name="type" value="{{ old('type', $equipment->type) }}" required>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="brand_model" class="form-label">Brand/Model</label>
                                    <input type="text" class="form-control @error('brand_model') is-invalid @enderror" 
                                           id="brand_model" name="brand_model" value="{{ old('brand_model', $equipment->brand_model) }}">
                                    @error('brand_model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                           id="serial_number" name="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}">
                                    @error('serial_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Equipment Details -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Equipment Details</h5>
                                
                                <div class="mb-3">
                                    <label for="condition" class="form-label">Condition *</label>
                                    <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition" required>
                                        <option value="">Select Condition</option>
                                        <option value="excellent" {{ old('condition', $equipment->condition) === 'excellent' ? 'selected' : '' }}>Excellent</option>
                                        <option value="good" {{ old('condition', $equipment->condition) === 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('condition', $equipment->condition) === 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="needs_maintenance" {{ old('condition', $equipment->condition) === 'needs_maintenance' ? 'selected' : '' }}>Needs Maintenance</option>
                                        <option value="out_of_service" {{ old('condition', $equipment->condition) === 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="calibration_date" class="form-label">Calibration Date</label>
                                    <input type="date" class="form-control @error('calibration_date') is-invalid @enderror" 
                                           id="calibration_date" name="calibration_date" value="{{ old('calibration_date', $equipment->calibration_date ? $equipment->calibration_date->format('Y-m-d') : '') }}">
                                    @error('calibration_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="calibration_due" class="form-label">Next Calibration Due</label>
                                    <input type="date" class="form-control @error('calibration_due') is-invalid @enderror" 
                                           id="calibration_due" name="calibration_due" value="{{ old('calibration_due', $equipment->calibration_due ? $equipment->calibration_due->format('Y-m-d') : '') }}">
                                    @error('calibration_due')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="calibration_certificate" class="form-label">Calibration Certificate</label>
                                    <input type="text" class="form-control @error('calibration_certificate') is-invalid @enderror" 
                                           id="calibration_certificate" name="calibration_certificate" value="{{ old('calibration_certificate', $equipment->calibration_certificate) }}">
                                    @error('calibration_certificate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="usage_hours" class="form-label">Usage Hours</label>
                                    <input type="number" step="0.01" class="form-control @error('usage_hours') is-invalid @enderror" 
                                           id="usage_hours" name="usage_hours" value="{{ old('usage_hours', $equipment->usage_hours) }}">
                                    @error('usage_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $equipment->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Additional Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="specifications" class="form-label">
                                        Specifications
                                        <i class="fas fa-info-circle text-primary" data-bs-toggle="tooltip" 
                                           title="Enter technical specifications, operating parameters, or key features"></i>
                                    </label>
                                    <textarea class="form-control @error('specifications') is-invalid @enderror" 
                                              id="specifications" name="specifications" rows="4" 
                                              placeholder="e.g., Range: 0-100mm, Frequency: 5MHz, Accuracy: ±0.1mm, Operating Temperature: -10°C to +50°C">{{ old('specifications', $equipment->specifications) }}</textarea>
                                    @error('specifications')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">Enter technical specifications, operating ranges, accuracy, etc.</small>
                                    </div>
                                </div>
                            </div>                            
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="maintenance_notes" class="form-label">Maintenance Notes</label>
                                    <textarea class="form-control @error('maintenance_notes') is-invalid @enderror" 
                                              id="maintenance_notes" name="maintenance_notes" rows="4">{{ old('maintenance_notes', $equipment->maintenance_notes) }}</textarea>
                                    @error('maintenance_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Equipment
                                </button>
                                <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                <a href="{{ route('admin.equipment.show', $equipment) }}" class="btn btn-info ms-2">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
