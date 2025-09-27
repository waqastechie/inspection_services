@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Add New Equipment</h2>
                <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Equipment
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.equipment.store') }}" method="POST">
                        @csrf
                        


                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Basic Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Type *</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" 
                                           id="type" name="type" value="{{ old('type') }}" required>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Manufacturer</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" 
                                           id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}">
                                    @error('manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           id="model" name="model" value="{{ old('model') }}">
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                           id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
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
                                        <option value="excellent" {{ old('condition') === 'excellent' ? 'selected' : '' }}>Excellent</option>
                                        <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="poor" {{ old('condition') === 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                           id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                                    @error('purchase_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                                    <input type="date" class="form-control @error('warranty_expiry') is-invalid @enderror" 
                                           id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry') }}">
                                    @error('warranty_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="calibration_due" class="form-label">Next Calibration Due</label>
                                    <input type="date" class="form-control @error('calibration_due') is-invalid @enderror" 
                                           id="calibration_due" name="calibration_due" value="{{ old('calibration_due') }}">
                                    @error('calibration_due')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
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
                                              placeholder="e.g., Range: 0-100mm, Frequency: 5MHz, Accuracy: ±0.1mm, Operating Temperature: -10°C to +50°C">{{ old('specifications') }}</textarea>
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
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Inspection Fields (for Items) -->
                        <div id="inspection_fields" class="row mt-4" style="display: none;">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="fas fa-search text-info"></i> Inspection Information</h5>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="swl" class="form-label">SWL (Safe Working Load)</label>
                                    <input type="text" class="form-control @error('swl') is-invalid @enderror" 
                                           id="swl" name="swl" value="{{ old('swl') }}" placeholder="e.g., 1472 Kg, 8.7 T @ 30°">
                                    @error('swl')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="test_load_applied" class="form-label">Test Load Applied</label>
                                    <input type="text" class="form-control @error('test_load_applied') is-invalid @enderror" 
                                           id="test_load_applied" name="test_load_applied" value="{{ old('test_load_applied') }}" placeholder="e.g., 5400 Kg, N/A">
                                    @error('test_load_applied')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="examination_status" class="form-label">Examination Status</label>
                                    <select class="form-select @error('examination_status') is-invalid @enderror" id="examination_status" name="examination_status">
                                        <option value="ND" {{ old('examination_status', 'ND') === 'ND' ? 'selected' : '' }}>ND (Not Done)</option>
                                        <option value="C" {{ old('examination_status') === 'C' ? 'selected' : '' }}>C (Compliant)</option>
                                        <option value="Pass" {{ old('examination_status') === 'Pass' ? 'selected' : '' }}>Pass</option>
                                        <option value="Fail" {{ old('examination_status') === 'Fail' ? 'selected' : '' }}>Fail</option>
                                    </select>
                                    @error('examination_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manufacture_date" class="form-label">Manufacture Date</label>
                                    <input type="date" class="form-control @error('manufacture_date') is-invalid @enderror" 
                                           id="manufacture_date" name="manufacture_date" value="{{ old('manufacture_date') }}">
                                    @error('manufacture_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="last_examination_date" class="form-label">Last Examination Date</label>
                                    <input type="date" class="form-control @error('last_examination_date') is-invalid @enderror" 
                                           id="last_examination_date" name="last_examination_date" value="{{ old('last_examination_date') }}">
                                    @error('last_examination_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="next_examination_date" class="form-label">Next Examination Date</label>
                                    <input type="date" class="form-control @error('next_examination_date') is-invalid @enderror" 
                                           id="next_examination_date" name="next_examination_date" value="{{ old('next_examination_date') }}">
                                    @error('next_examination_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Asset Fields (for Assets) -->
                        <div id="asset_fields" class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="fas fa-clipboard-list text-primary"></i> Asset Information</h5>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Equipment
                                </button>
                                <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryRadios = document.querySelectorAll('input[name="equipment_category"]');
    const parentEquipmentSection = document.getElementById('parent_equipment_section');
    const inspectionFields = document.getElementById('inspection_fields');
    const assetFields = document.getElementById('asset_fields');

    function toggleFieldsBasedOnCategory() {
        const selectedCategory = document.querySelector('input[name="equipment_category"]:checked').value;
        
        if (selectedCategory === 'item') {
            parentEquipmentSection.style.display = 'block';
            inspectionFields.style.display = 'block';
            assetFields.style.display = 'none';
            document.getElementById('parent_equipment_id').required = true;
        } else {
            parentEquipmentSection.style.display = 'none';
            inspectionFields.style.display = 'none';
            assetFields.style.display = 'block';
            document.getElementById('parent_equipment_id').required = false;
        }
    }

    categoryRadios.forEach(radio => {
        radio.addEventListener('change', toggleFieldsBasedOnCategory);
    });

    // Initialize on page load
    toggleFieldsBasedOnCategory();
});
</script>
