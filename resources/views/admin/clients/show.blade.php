@extends('layouts.master')

@section('title', 'Client Details - Professional Inspection Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-building me-2 text-primary"></i>
                        Client Details
                    </h1>
                    <p class="text-muted mb-0">Detailed information for {{ $client->client_name }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Clients
                    </a>
                    <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Client
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Client Information Card -->
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Client Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Basic Information -->
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-building me-2"></i>Basic Information
                                    </h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Client Name</label>
                                    <p class="fw-bold">{{ $client->client_name }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Client Code</label>
                                    <p class="fw-bold">{{ $client->client_code ?: 'Not assigned' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Company Type</label>
                                    <p>{{ $client->company_type ?: 'Not specified' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Industry</label>
                                    <p>{{ $client->industry ?: 'Not specified' }}</p>
                                </div>

                                <!-- Contact Information -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-phone me-2"></i>Contact Information
                                    </h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Phone</label>
                                    <p>{{ $client->phone ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Email</label>
                                    <p>{{ $client->email ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Website</label>
                                    <p>
                                        @if($client->website)
                                            <a href="{{ $client->website }}" target="_blank" class="text-decoration-none">
                                                {{ $client->website }} <i class="fas fa-external-link-alt ms-1"></i>
                                            </a>
                                        @else
                                            Not provided
                                        @endif
                                    </p>
                                </div>

                                <!-- Primary Address -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i>Primary Address
                                    </h6>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label text-muted">Address</label>
                                    <p>{{ $client->address ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label text-muted">City</label>
                                    <p>{{ $client->city ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label text-muted">State</label>
                                    <p>{{ $client->state ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Postal Code</label>
                                    <p>{{ $client->postal_code ?: 'Not provided' }}</p>
                                </div>

                                <!-- Primary Contact -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user me-2"></i>Primary Contact
                                    </h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Contact Person</label>
                                    <p>{{ $client->contact_person ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Position</label>
                                    <p>{{ $client->contact_position ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Contact Phone</label>
                                    <p>{{ $client->contact_phone ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Contact Email</label>
                                    <p>{{ $client->contact_email ?: 'Not provided' }}</p>
                                </div>

                                <!-- Business Information -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-briefcase me-2"></i>Business Information
                                    </h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Tax ID</label>
                                    <p>{{ $client->tax_id ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Registration Number</label>
                                    <p>{{ $client->registration_number ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Payment Terms</label>
                                    <p>{{ $client->payment_terms }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Credit Limit</label>
                                    <p>
                                        @if($client->credit_limit)
                                            {{ $client->preferred_currency }} {{ number_format($client->credit_limit, 2) }}
                                        @else
                                            Not set
                                        @endif
                                    </p>
                                </div>

                                <!-- Notes -->
                                @if($client->notes)
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-sticky-note me-2"></i>Notes
                                    </h6>
                                    <p class="text-muted">{{ $client->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="col-lg-4">
                    <!-- Status Card -->
                    <div class="card mb-4 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 1.25rem;">
                            <h5 class="card-title mb-0 text-white fw-bold">
                                <i class="fas fa-info-circle me-2"></i>
                                Status & Details
                            </h5>
                        </div>
                        <div class="card-body" style="padding: 1.5rem; background: #f8fafc;">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: white; border: 2px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <div>
                                            <label class="form-label text-muted mb-1 fw-medium" style="font-size: 0.875rem;">Current Status</label>
                                            @if($client->is_active)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success px-3 py-2 rounded-pill" style="font-size: 0.875rem;">
                                                        <i class="fas fa-check-circle me-2"></i>Active
                                                    </span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-danger px-3 py-2 rounded-pill" style="font-size: 0.875rem;">
                                                        <i class="fas fa-times-circle me-2"></i>Inactive
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <i class="fas fa-shield-alt text-primary" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: white; border: 2px solid #e2e8f0; border-left: 4px solid #3b82f6;">
                                        <label class="form-label text-muted mb-2 fw-medium" style="font-size: 0.875rem;">
                                            <i class="fas fa-globe me-2 text-primary"></i>Country
                                        </label>
                                        <p class="mb-0 fw-semibold text-gray-800">{{ $client->country }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: white; border: 2px solid #e2e8f0; border-left: 4px solid #10b981;">
                                        <label class="form-label text-muted mb-2 fw-medium" style="font-size: 0.875rem;">
                                            <i class="fas fa-money-bill-wave me-2 text-success"></i>Preferred Currency
                                        </label>
                                        <p class="mb-0 fw-semibold text-gray-800">{{ $client->preferred_currency }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: white; border: 2px solid #e2e8f0; border-left: 4px solid #8b5cf6;">
                                        <label class="form-label text-muted mb-2 fw-medium" style="font-size: 0.875rem;">
                                            <i class="fas fa-calendar-plus me-2 text-purple"></i>Created Date
                                        </label>
                                        <p class="mb-0 fw-semibold text-gray-800">{{ $client->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: white; border: 2px solid #e2e8f0; border-left: 4px solid #f59e0b;">
                                        <label class="form-label text-muted mb-2 fw-medium" style="font-size: 0.875rem;">
                                            <i class="fas fa-calendar-edit me-2 text-warning"></i>Last Updated
                                        </label>
                                        <p class="mb-0 fw-semibold text-gray-800">{{ $client->updated_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address Card -->
                    @if($client->billing_address || $client->billing_city || $client->billing_state)
                    <div class="card mb-4 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%); border: none; padding: 1.25rem;">
                            <h5 class="card-title mb-0 text-white fw-bold">
                                <i class="fas fa-file-invoice me-2"></i>
                                Billing Address
                            </h5>
                        </div>
                        <div class="card-body" style="padding: 1.5rem; background: #f8fafc;">
                            <div class="p-3 rounded-3" style="background: white; border: 2px solid #e2e8f0; border-left: 4px solid #ff6b6b;">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        @if($client->billing_address)
                                            <p class="mb-2 fw-semibold text-gray-800">{{ $client->billing_address }}</p>
                                        @endif
                                        <p class="mb-1 text-gray-600">
                                            {{ $client->billing_city }}{{ $client->billing_city && $client->billing_state ? ', ' : '' }}{{ $client->billing_state }}
                                        </p>
                                        @if($client->billing_postal_code)
                                            <p class="mb-1 text-gray-600">{{ $client->billing_postal_code }}</p>
                                        @endif
                                        @if($client->billing_country)
                                            <p class="mb-0 fw-medium text-gray-700">{{ $client->billing_country }}</p>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <i class="fas fa-map-marker-alt text-danger" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); border: none; padding: 1.25rem;">
                            <h5 class="card-title mb-0 text-white fw-bold">
                                <i class="fas fa-bolt me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body" style="padding: 1.5rem; background: #f8fafc;">
                            <div class="d-grid gap-3">
                                <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-outline-primary rounded-3 py-2.5 fw-medium" style="border: 2px solid #3b82f6; transition: all 0.3s ease;">
                                    <i class="fas fa-edit me-2"></i>Edit Client
                                </a>
                                <button type="button" class="btn btn-outline-success rounded-3 py-2.5 fw-medium" style="border: 2px solid #10b981; transition: all 0.3s ease;" onclick="generatePDF()" id="pdfBtn">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    <span class="btn-text">Generate PDF</span>
                                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" id="pdfSpinner"></span>
                                </button>
                                <button type="button" class="btn btn-outline-info rounded-3 py-2.5 fw-medium" style="border: 2px solid #06b6d4; transition: all 0.3s ease;" onclick="copyClientInfo()">
                                    <i class="fas fa-copy me-2"></i>Copy Client Info
                                </button>
                                @if($client->email)
                                <a href="mailto:{{ $client->email }}" class="btn btn-outline-secondary rounded-3 py-2.5 fw-medium" style="border: 2px solid #6b7280; transition: all 0.3s ease;">
                                    <i class="fas fa-envelope me-2"></i>Send Email
                                </a>
                                @endif
                                @if($client->phone)
                                <a href="tel:{{ $client->phone }}" class="btn btn-outline-secondary rounded-3 py-2.5 fw-medium" style="border: 2px solid #6b7280; transition: all 0.3s ease;">
                                    <i class="fas fa-phone me-2"></i>Call Client
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-purple {
    color: #8b5cf6 !important;
}

.text-gray-800 {
    color: #1f2937 !important;
}

.text-gray-700 {
    color: #374151 !important;
}

.text-gray-600 {
    color: #4b5563 !important;
}

/* Hover effects for action buttons */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Card hover effects */
.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Individual field boxes hover */
.rounded-3:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15) !important;
    transition: all 0.3s ease;
}

/* Status badge styling */
.badge {
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Smooth transitions */
* {
    transition: all 0.3s ease;
}

/* Background gradient animations */
.bg-gradient:hover {
    filter: brightness(1.1);
}

/* Form label styling */
.form-label {
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
}

/* Icon styling */
.fas {
    transition: all 0.3s ease;
}

.card-header .fas:hover {
    transform: scale(1.1);
}

/* Custom scrollbar for overflow content */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
function generatePDF() {
    const pdfBtn = document.getElementById('pdfBtn');
    const pdfSpinner = document.getElementById('pdfSpinner');
    const btnText = pdfBtn.querySelector('.btn-text');
    const btnIcon = pdfBtn.querySelector('i');
    
    // Show loading state
    pdfBtn.disabled = true;
    pdfSpinner.classList.remove('d-none');
    btnIcon.classList.add('d-none');
    btnText.textContent = 'Generating PDF...';
    pdfBtn.classList.remove('btn-outline-success');
    pdfBtn.classList.add('btn-success');
    
    // Show progress toast
    showProgressToast('Preparing client data...', 'info');
    
    // Simulate progress steps
    setTimeout(() => {
        showProgressToast('Formatting document...', 'info');
    }, 500);
    
    setTimeout(() => {
        showProgressToast('Generating PDF...', 'info');
        
        // Start actual PDF generation
        try {
            generateClientPDF();
        } catch (error) {
            console.error('PDF Generation Error:', error);
            showProgressToast('Error generating PDF. Please try again.', 'error');
            resetPDFButton();
        }
    }, 1000);
}

function generateClientPDF() {
    // Import jsPDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // PDF Configuration
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const margin = 20;
    const contentWidth = pageWidth - (margin * 2);
    let yPosition = margin;
    
    // Colors
    const primaryColor = [30, 64, 175]; // Blue
    const secondaryColor = [100, 116, 139]; // Gray
    const textColor = [30, 41, 59]; // Dark gray
    
    try {
        // Header Section
        doc.setFillColor(...primaryColor);
        doc.rect(0, 0, pageWidth, 40, 'F');
        
        // Company Logo/Title
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(24);
        doc.setFont('helvetica', 'bold');
        doc.text('Professional Inspection Services', margin, 25);
        
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text('Client Information Report', margin, 35);
        
        // Date
        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        doc.text(`Generated: ${currentDate}`, pageWidth - margin - 80, 35);
        
        yPosition = 60;
        
        // Client Name (Title)
        doc.setTextColor(...textColor);
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.text('{{ $client->client_name }}', margin, yPosition);
        yPosition += 15;
        
        // Separator line
        doc.setDrawColor(...primaryColor);
        doc.setLineWidth(2);
        doc.line(margin, yPosition, pageWidth - margin, yPosition);
        yPosition += 20;
        
        // Basic Information Section
        yPosition = addSection(doc, 'Basic Information', yPosition, margin, contentWidth, [
            ['Client Name:', '{{ $client->client_name }}'],
            ['Client Code:', '{{ $client->client_code ?: "Not assigned" }}'],
            ['Company Type:', '{{ $client->company_type ?: "Not specified" }}'],
            ['Industry:', '{{ $client->industry ?: "Not specified" }}'],
            ['Status:', '{{ $client->is_active ? "Active" : "Inactive" }}']
        ]);
        
        // Contact Information Section
        yPosition = addSection(doc, 'Contact Information', yPosition, margin, contentWidth, [
            ['Phone:', '{{ $client->phone ?: "Not provided" }}'],
            ['Email:', '{{ $client->email ?: "Not provided" }}'],
            ['Website:', '{{ $client->website ?: "Not provided" }}'],
            ['Contact Person:', '{{ $client->contact_person ?: "Not provided" }}'],
            ['Contact Position:', '{{ $client->contact_position ?: "Not provided" }}'],
            ['Contact Phone:', '{{ $client->contact_phone ?: "Not provided" }}'],
            ['Contact Email:', '{{ $client->contact_email ?: "Not provided" }}']
        ]);
        
        // Address Information Section
        const fullAddress = '{{ $client->address ?: "Not provided" }}' + 
                          (('{{ $client->city }}' || '{{ $client->state }}' || '{{ $client->postal_code }}') ? 
                           '\n{{ $client->city ?: "" }}{{ $client->city && $client->state ? ", " : "" }}{{ $client->state ?: "" }} {{ $client->postal_code ?: "" }}' : '');
        
        yPosition = addSection(doc, 'Address Information', yPosition, margin, contentWidth, [
            ['Primary Address:', fullAddress],
            ['Country:', '{{ $client->country }}']
        ]);
        
        // Check if we need a new page
        if (yPosition > pageHeight - 60) {
            doc.addPage();
            yPosition = margin;
        }
        
        // Business Information Section
        yPosition = addSection(doc, 'Business Information', yPosition, margin, contentWidth, [
            ['Tax ID:', '{{ $client->tax_id ?: "Not provided" }}'],
            ['Registration Number:', '{{ $client->registration_number ?: "Not provided" }}'],
            ['Payment Terms:', '{{ $client->payment_terms }}'],
            ['Credit Limit:', '{{ $client->credit_limit ? $client->preferred_currency . " " . number_format($client->credit_limit, 2) : "Not set" }}'],
            ['Preferred Currency:', '{{ $client->preferred_currency }}']
        ]);
        
        // Billing Address Section (if different)
        @if($client->billing_address || $client->billing_city || $client->billing_state)
        const billingAddress = '{{ $client->billing_address ?: "" }}' + 
                              (('{{ $client->billing_city }}' || '{{ $client->billing_state }}' || '{{ $client->billing_postal_code }}') ?
                               '\n{{ $client->billing_city ?: "" }}{{ $client->billing_city && $client->billing_state ? ", " : "" }}{{ $client->billing_state ?: "" }} {{ $client->billing_postal_code ?: "" }}' : '') +
                              ('{{ $client->billing_country }}' ? '\n{{ $client->billing_country }}' : '');
        
        yPosition = addSection(doc, 'Billing Address', yPosition, margin, contentWidth, [
            ['Billing Address:', billingAddress]
        ]);
        @endif
        
        // Notes Section
        @if($client->notes)
        yPosition = addSection(doc, 'Notes', yPosition, margin, contentWidth, [
            ['Notes:', '{{ $client->notes }}']
        ]);
        @endif
        
        // Footer
        const footerY = pageHeight - 30;
        doc.setDrawColor(...secondaryColor);
        doc.setLineWidth(1);
        doc.line(margin, footerY, pageWidth - margin, footerY);
        
        doc.setTextColor(...secondaryColor);
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.text('Professional Inspection Services - Client Information Report', margin, footerY + 10);
        doc.text(`Page 1 of ${doc.internal.pages.length - 1}`, pageWidth - margin - 40, footerY + 10);
        
        // Final progress update
        showProgressToast('Finalizing document...', 'info');
        
        setTimeout(() => {
            // Save the PDF
            const fileName = `client-${('{{ $client->client_code }}' || '{{ $client->id }}').toLowerCase().replace(/[^a-z0-9]/g, '-')}-${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(fileName);
            
            // Success feedback
            showProgressToast('PDF generated successfully!', 'success');
            resetPDFButton();
        }, 500);
        
    } catch (error) {
        console.error('PDF Generation Error:', error);
        showProgressToast('Error generating PDF. Please try again.', 'error');
        resetPDFButton();
    }
}

function addSection(doc, title, yPos, margin, contentWidth, data) {
    const primaryColor = [30, 64, 175];
    const textColor = [30, 41, 59];
    const grayColor = [100, 116, 139];
    
    // Section title
    doc.setTextColor(...primaryColor);
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text(title, margin, yPos);
    yPos += 5;
    
    // Underline
    doc.setDrawColor(...primaryColor);
    doc.setLineWidth(1);
    doc.line(margin, yPos, margin + 60, yPos);
    yPos += 15;
    
    // Section data
    doc.setTextColor(...textColor);
    doc.setFontSize(10);
    
    data.forEach(([label, value]) => {
        // Label
        doc.setFont('helvetica', 'bold');
        doc.text(label, margin, yPos);
        
        // Value (handle multi-line text)
        doc.setFont('helvetica', 'normal');
        const lines = doc.splitTextToSize(value, contentWidth - 80);
        doc.text(lines, margin + 80, yPos);
        
        yPos += lines.length * 5 + 3;
    });
    
    return yPos + 10;
}

function resetPDFButton() {
    const pdfBtn = document.getElementById('pdfBtn');
    const pdfSpinner = document.getElementById('pdfSpinner');
    const btnText = pdfBtn.querySelector('.btn-text');
    const btnIcon = pdfBtn.querySelector('i');
    
    // Reset button state
    pdfBtn.disabled = false;
    pdfSpinner.classList.add('d-none');
    btnIcon.classList.remove('d-none');
    btnText.textContent = 'Generate PDF';
    pdfBtn.classList.remove('btn-success');
    pdfBtn.classList.add('btn-outline-success');
}

function showProgressToast(message, type) {
    // Remove any existing progress toasts
    const existingToasts = document.querySelectorAll('.progress-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toastElement = document.createElement('div');
    toastElement.className = `toast progress-toast align-items-center text-bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'primary'} border-0`;
    toastElement.setAttribute('role', 'alert');
    toastElement.style.cssText = 'position: relative; z-index: 1060;';
    
    const iconClass = type === 'error' ? 'fa-exclamation-triangle' : 
                     type === 'success' ? 'fa-check-circle' : 
                     'fa-info-circle';
    
    toastElement.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                ${type === 'info' ? '<div class="spinner-border spinner-border-sm ms-auto" role="status"></div>' : ''}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toastElement);
    
    const toast = new bootstrap.Toast(toastElement, {
        autohide: type !== 'info',
        delay: type === 'success' ? 3000 : type === 'error' ? 5000 : 0
    });
    
    toast.show();
    
    // Auto-remove info toasts after 10 seconds as fallback
    if (type === 'info') {
        setTimeout(() => {
            if (toastElement.parentNode) {
                toast.hide();
            }
        }, 10000);
    }
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1060';
    document.body.appendChild(container);
    return container;
}

function copyClientInfo() {
    const clientInfo = `
Client Name: {{ $client->client_name }}
Contact Person: {{ $client->contact_person ?: 'N/A' }}
Phone: {{ $client->phone ?: 'N/A' }}
Email: {{ $client->email ?: 'N/A' }}
Address: {{ $client->address ?: 'N/A' }}
City: {{ $client->city ?: 'N/A' }}, {{ $client->state ?: 'N/A' }} {{ $client->postal_code ?: '' }}
    `.trim();

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(clientInfo).then(() => {
            showAlert('Client information copied to clipboard!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = clientInfo;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showAlert('Client information copied to clipboard!', 'success');
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}
</script>
@endsection
