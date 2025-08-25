@extends('layouts.master')

@section('title', 'Edit Client - Professional Inspection Services')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Edit Client
                    </h1>
                    <p class="text-muted mb-0">Update client information for <strong>{{ $client->client_name }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Clients
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.clients.update', $client) }}">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="client_name" class="form-label">Client Name *</label>
                                <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
                                       id="client_name" name="client_name" value="{{ old('client_name', $client->client_name) }}" 
                                       placeholder="e.g., Saipem Trechville LLC" required>
                                @error('client_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="client_code" class="form-label">Client Code</label>
                                <input type="text" class="form-control @error('client_code') is-invalid @enderror" 
                                       id="client_code" name="client_code" value="{{ old('client_code', $client->client_code) }}" 
                                       placeholder="Auto-generated if empty" maxlength="10">
                                @error('client_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Unique identifier for this client</div>
                            </div>
                            <div class="col-md-6">
                                <label for="company_type" class="form-label">Company Type</label>
                                <select class="form-select @error('company_type') is-invalid @enderror" 
                                        id="company_type" name="company_type">
                                    <option value="">Select Type</option>
                                    <option value="LLC" {{ old('company_type', $client->company_type) === 'LLC' ? 'selected' : '' }}>LLC</option>
                                    <option value="Corporation" {{ old('company_type', $client->company_type) === 'Corporation' ? 'selected' : '' }}>Corporation</option>
                                    <option value="Partnership" {{ old('company_type', $client->company_type) === 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="Sole Proprietorship" {{ old('company_type', $client->company_type) === 'Sole Proprietorship' ? 'selected' : '' }}>Sole Proprietorship</option>
                                    <option value="Government" {{ old('company_type', $client->company_type) === 'Government' ? 'selected' : '' }}>Government</option>
                                    <option value="Non-Profit" {{ old('company_type', $client->company_type) === 'Non-Profit' ? 'selected' : '' }}>Non-Profit</option>
                                </select>
                                @error('company_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="industry" class="form-label">Industry</label>
                                <select class="form-select @error('industry') is-invalid @enderror" 
                                        id="industry" name="industry">
                                    <option value="">Select Industry</option>
                                    <option value="Oil & Gas" {{ old('industry', $client->industry) === 'Oil & Gas' ? 'selected' : '' }}>Oil & Gas</option>
                                    <option value="Manufacturing" {{ old('industry', $client->industry) === 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                    <option value="Construction" {{ old('industry', $client->industry) === 'Construction' ? 'selected' : '' }}>Construction</option>
                                    <option value="Marine/Offshore" {{ old('industry', $client->industry) === 'Marine/Offshore' ? 'selected' : '' }}>Marine/Offshore</option>
                                    <option value="Power Generation" {{ old('industry', $client->industry) === 'Power Generation' ? 'selected' : '' }}>Power Generation</option>
                                    <option value="Transportation" {{ old('industry', $client->industry) === 'Transportation' ? 'selected' : '' }}>Transportation</option>
                                    <option value="Mining" {{ old('industry', $client->industry) === 'Mining' ? 'selected' : '' }}>Mining</option>
                                    <option value="Aerospace" {{ old('industry', $client->industry) === 'Aerospace' ? 'selected' : '' }}>Aerospace</option>
                                    <option value="Chemical" {{ old('industry', $client->industry) === 'Chemical' ? 'selected' : '' }}>Chemical</option>
                                    <option value="Other" {{ old('industry', $client->industry) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('industry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-address-book me-2"></i>
                            Contact Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" 
                                          placeholder="Street address">{{ old('address', $client->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $client->city) }}" 
                                       placeholder="City">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state', $client->state) }}" 
                                       placeholder="State or Province">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $client->postal_code) }}" 
                                       placeholder="ZIP/Postal Code">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-select @error('country') is-invalid @enderror" 
                                        id="country" name="country">
                                    <option value="United States" {{ old('country', $client->country) === 'United States' ? 'selected' : '' }}>United States</option>
                                    <option value="Canada" {{ old('country', $client->country) === 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Mexico" {{ old('country', $client->country) === 'Mexico' ? 'selected' : '' }}>Mexico</option>
                                    <option value="United Kingdom" {{ old('country', $client->country) === 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="Other" {{ old('country', $client->country) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $client->phone) }}" 
                                       placeholder="+1 (555) 123-4567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $client->email) }}" 
                                       placeholder="contact@company.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website', $client->website) }}" 
                                       placeholder="https://www.company.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Primary Contact -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>
                            Primary Contact
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                       id="contact_person" name="contact_person" value="{{ old('contact_person', $client->contact_person) }}" 
                                       placeholder="Full Name">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_position" class="form-label">Position/Title</label>
                                <input type="text" class="form-control @error('contact_position') is-invalid @enderror" 
                                       id="contact_position" name="contact_position" value="{{ old('contact_position', $client->contact_position) }}" 
                                       placeholder="e.g., Project Manager">
                                @error('contact_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label">Direct Phone</label>
                                <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $client->contact_phone) }}" 
                                       placeholder="+1 (555) 123-4567">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_email" class="form-label">Direct Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email', $client->contact_email) }}" 
                                       placeholder="person@company.com">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-briefcase me-2"></i>
                            Business Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="tax_id" class="form-label">Tax ID/EIN</label>
                                <input type="text" class="form-control @error('tax_id') is-invalid @enderror" 
                                       id="tax_id" name="tax_id" value="{{ old('tax_id', $client->tax_id) }}" 
                                       placeholder="XX-XXXXXXX">
                                @error('tax_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                       id="registration_number" name="registration_number" value="{{ old('registration_number', $client->registration_number) }}" 
                                       placeholder="Business registration number">
                                @error('registration_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                <select class="form-select @error('payment_terms') is-invalid @enderror" 
                                        id="payment_terms" name="payment_terms">
                                    <option value="Net 30" {{ old('payment_terms', $client->payment_terms) === 'Net 30' ? 'selected' : '' }}>Net 30</option>
                                    <option value="Net 15" {{ old('payment_terms', $client->payment_terms) === 'Net 15' ? 'selected' : '' }}>Net 15</option>
                                    <option value="Net 60" {{ old('payment_terms', $client->payment_terms) === 'Net 60' ? 'selected' : '' }}>Net 60</option>
                                    <option value="Due on Receipt" {{ old('payment_terms', $client->payment_terms) === 'Due on Receipt' ? 'selected' : '' }}>Due on Receipt</option>
                                    <option value="Cash on Delivery" {{ old('payment_terms', $client->payment_terms) === 'Cash on Delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                                </select>
                                @error('payment_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="credit_limit" class="form-label">Credit Limit</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('credit_limit') is-invalid @enderror" 
                                           id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $client->credit_limit) }}" 
                                           placeholder="0.00" step="0.01" min="0">
                                    @error('credit_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="preferred_currency" class="form-label">Preferred Currency</label>
                                <select class="form-select @error('preferred_currency') is-invalid @enderror" 
                                        id="preferred_currency" name="preferred_currency">
                                    <option value="USD" {{ old('preferred_currency', $client->preferred_currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="CAD" {{ old('preferred_currency', $client->preferred_currency) === 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                    <option value="EUR" {{ old('preferred_currency', $client->preferred_currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('preferred_currency', $client->preferred_currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                </select>
                                @error('preferred_currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-sticky-note me-2"></i>
                            Additional Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Additional notes about this client...">{{ old('notes', $client->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Client
                                    </label>
                                    <div class="form-text">Inactive clients won't appear in dropdowns</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Client
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
