@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Create New User</h1>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3 text-primary">Basic Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimum 8 characters required</div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Select a role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="inspector" {{ old('role') == 'inspector' ? 'selected' : '' }}>Inspector</option>
                                        <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle text-primary"></i>
                                        <strong>Note:</strong> Certification will be required for Admin and Inspector roles
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3 text-primary">Additional Information</h5>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                           id="department" name="department" value="{{ old('department') }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="certification" class="form-label">
                                        Certification/Qualification <span class="text-info">*</span>
                                    </label>
                                    
                                    <!-- Quick Select Common Certifications -->
                                    <div class="mb-2">
                                        <label class="form-label small text-muted">Quick Select (Click to add):</label>
                                        <div class="d-flex flex-wrap gap-1">
                                            <button type="button" class="btn btn-outline-primary btn-sm cert-btn" data-cert="NDT Level II Certified">NDT Level II</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm cert-btn" data-cert="Lifting Equipment Inspector">Lifting Equipment</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm cert-btn" data-cert="Certified Welding Inspector (CWI)">CWI</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm cert-btn" data-cert="ASNT MT/PT Level III">ASNT Level III</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm cert-btn" data-cert="API 570 Piping Inspector">API 570</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm cert-btn" data-cert="CSWIP Welding Inspector">CSWIP</button>
                                        </div>
                                    </div>
                                    
                                    <textarea class="form-control @error('certification') is-invalid @enderror" 
                                              id="certification" name="certification" rows="4" 
                                              placeholder="Enter certifications, qualifications, licenses, and expertise areas..."
                                              required>{{ old('certification') }}</textarea>
                                    @error('certification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle text-primary"></i>
                                        <strong>This field will auto-populate in inspection forms.</strong> Include all relevant certifications, 
                                        license numbers, expiry dates, and areas of expertise.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="certification_expiry" class="form-label">Certification Expiry Date</label>
                                    <input type="date" class="form-control @error('certification_expiry') is-invalid @enderror" 
                                           id="certification_expiry" name="certification_expiry" value="{{ old('certification_expiry') }}">
                                    @error('certification_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave blank if certification doesn't expire</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            Active User
                                        </label>
                                    </div>
                                    <div class="form-text">Uncheck to create an inactive user</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to tomorrow for certification expiry
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const certificationExpiry = document.getElementById('certification_expiry');
    if (certificationExpiry) {
        certificationExpiry.min = tomorrow.toISOString().split('T')[0];
    }
    
    // Handle quick certification selection
    const certificationTextarea = document.getElementById('certification');
    const certButtons = document.querySelectorAll('.cert-btn');
    
    certButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const certText = this.dataset.cert;
            const currentValue = certificationTextarea.value.trim();
            
            // Check if certification already exists
            if (currentValue.toLowerCase().includes(certText.toLowerCase())) {
                // Highlight the button briefly to show it's already added
                this.classList.add('btn-warning');
                this.innerHTML = '<i class="fas fa-check"></i> Already Added';
                setTimeout(() => {
                    this.classList.remove('btn-warning');
                    this.innerHTML = this.textContent.replace('✓ Already Added', '').trim();
                }, 2000);
                return;
            }
            
            // Add certification to textarea
            if (currentValue) {
                certificationTextarea.value = currentValue + '\n• ' + certText;
            } else {
                certificationTextarea.value = '• ' + certText;
            }
            
            // Visual feedback
            this.classList.add('btn-success');
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> Added';
            
            setTimeout(() => {
                this.classList.remove('btn-success');
                this.innerHTML = originalText;
            }, 2000);
            
            // Focus textarea
            certificationTextarea.focus();
        });
    });
    
    // Role-based certification requirements
    const roleSelect = document.getElementById('role');
    const certificationField = document.getElementById('certification');
    const certificationLabel = document.querySelector('label[for="certification"]');
    
    function updateCertificationRequirement() {
        const selectedRole = roleSelect.value;
        const isRequired = ['inspector', 'admin'].includes(selectedRole);
        
        if (isRequired) {
            certificationField.required = true;
            certificationLabel.innerHTML = 'Certification/Qualification <span class="text-danger">*</span>';
            certificationField.classList.add('border-warning');
            
            if (selectedRole === 'inspector' && !certificationField.value.trim()) {
                certificationField.placeholder = 'e.g., NDT Level II Certified\n• Lifting Equipment Inspector\n• ASNT MT/PT Level III\n• Valid until: 2026-12-31';
            } else if (selectedRole === 'admin' && !certificationField.value.trim()) {
                certificationField.placeholder = 'e.g., Senior Inspector\n• Management Certification\n• Technical Review Authority\n• 10+ years experience';
            }
        } else {
            certificationField.required = false;
            certificationLabel.innerHTML = 'Certification/Qualification <span class="text-info">*</span>';
            certificationField.classList.remove('border-warning');
            
            if (selectedRole === 'viewer') {
                certificationField.placeholder = 'e.g., Technical Support\n• Quality Assurance\n• Documentation Specialist';
            }
        }
    }
    
    roleSelect.addEventListener('change', updateCertificationRequirement);
    
    // Initialize on page load
    updateCertificationRequirement();
});
</script>
@endsection
