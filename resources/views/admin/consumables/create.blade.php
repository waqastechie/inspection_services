@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Add New Consumable</h2>
                <a href="{{ route('admin.consumables.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Consumables
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.consumables.store') }}" method="POST">
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
                                    <label for="brand_manufacturer" class="form-label">Brand/Manufacturer</label>
                                    <input type="text" class="form-control @error('brand_manufacturer') is-invalid @enderror" 
                                           id="brand_manufacturer" name="brand_manufacturer" value="{{ old('brand_manufacturer') }}">
                                    @error('brand_manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="product_code" class="form-label">Product Code</label>
                                    <input type="text" class="form-control @error('product_code') is-invalid @enderror" 
                                           id="product_code" name="product_code" value="{{ old('product_code') }}">
                                    @error('product_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="batch_lot_number" class="form-label">Batch/Lot Number</label>
                                    <input type="text" class="form-control @error('batch_lot_number') is-invalid @enderror" 
                                           id="batch_lot_number" name="batch_lot_number" value="{{ old('batch_lot_number') }}">
                                    @error('batch_lot_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Inventory Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Inventory Information</h5>
                                
                                <div class="mb-3">
                                    <label for="quantity_available" class="form-label">Quantity Available *</label>
                                    <input type="number" step="0.01" class="form-control @error('quantity_available') is-invalid @enderror" 
                                           id="quantity_available" name="quantity_available" value="{{ old('quantity_available') }}" required min="0">
                                    @error('quantity_available')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                           id="unit" name="unit" value="{{ old('unit') }}" placeholder="e.g., pcs, ml, kg">
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="unit_cost" class="form-label">Unit Cost</label>
                                    <input type="number" step="0.01" class="form-control @error('unit_cost') is-invalid @enderror" 
                                           id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" min="0">
                                    @error('unit_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                           id="supplier" name="supplier" value="{{ old('supplier') }}">
                                    @error('supplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    <label for="condition" class="form-label">Condition *</label>
                                    <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition" required>
                                        <option value="">Select Condition</option>
                                        <option value="new" {{ old('condition') === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="expired" {{ old('condition') === 'expired' ? 'selected' : '' }}>Expired</option>
                                        <option value="damaged" {{ old('condition') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>
                                    @error('condition')
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

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="storage_requirements" class="form-label">Storage Requirements</label>
                                    <textarea class="form-control @error('storage_requirements') is-invalid @enderror" 
                                              id="storage_requirements" name="storage_requirements" rows="3">{{ old('storage_requirements') }}</textarea>
                                    @error('storage_requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="safety_notes" class="form-label">Safety Notes</label>
                                    <textarea class="form-control @error('safety_notes') is-invalid @enderror" 
                                              id="safety_notes" name="safety_notes" rows="3">{{ old('safety_notes') }}</textarea>
                                    @error('safety_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Consumable
                                </button>
                                <a href="{{ route('admin.consumables.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
