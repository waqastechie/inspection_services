{{-- Export Section --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-download me-2"></i>Export & Documentation
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <p class="text-muted mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Export inspection data in various formats for reporting, archiving, or sharing with clients and stakeholders.
                </p>

                {{-- Export Options --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-3">
                            <i class="fas fa-file-export me-1"></i>Export Formats
                        </h6>
                        
                        {{-- PDF Export --}}
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2 fs-4"></i>
                                    <div>
                                        <h6 class="mb-0">PDF Report</h6>
                                        <small class="text-muted">Complete inspection report with images</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="exportToPDF('standard')">
                                        <i class="fas fa-download me-1"></i>Standard
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="exportToPDF('detailed')">
                                        <i class="fas fa-download me-1"></i>Detailed
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="exportToPDF('summary')">
                                        <i class="fas fa-download me-1"></i>Summary
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Excel Export --}}
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-excel text-success me-2 fs-4"></i>
                                    <div>
                                        <h6 class="mb-0">Excel Spreadsheet</h6>
                                        <small class="text-muted">Data in spreadsheet format for analysis</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportToExcel('data')">
                                        <i class="fas fa-download me-1"></i>Data Only
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportToExcel('formatted')">
                                        <i class="fas fa-download me-1"></i>Formatted
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Word Export --}}
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-word text-primary me-2 fs-4"></i>
                                    <div>
                                        <h6 class="mb-0">Word Document</h6>
                                        <small class="text-muted">Editable document format</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportToWord()">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                            </div>
                        </div>

                        {{-- CSV Export --}}
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-csv text-info me-2 fs-4"></i>
                                    <div>
                                        <h6 class="mb-0">CSV Data</h6>
                                        <small class="text-muted">Raw data for import into other systems</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="exportToCSV()">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="mb-3">
                            <i class="fas fa-cog me-1"></i>Export Settings
                        </h6>

                        {{-- Export Configuration --}}
                        <div class="card border">
                            <div class="card-body">
                                {{-- Include Options --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Include in Export:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_images" name="export_options[]" value="images" checked>
                                        <label class="form-check-label" for="include_images">
                                            Images and Photos
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_signatures" name="export_options[]" value="signatures" checked>
                                        <label class="form-check-label" for="include_signatures">
                                            Digital Signatures
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_attachments" name="export_options[]" value="attachments" checked>
                                        <label class="form-check-label" for="include_attachments">
                                            File Attachments
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_history" name="export_options[]" value="history">
                                        <label class="form-check-label" for="include_history">
                                            Change History
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_comments" name="export_options[]" value="comments" checked>
                                        <label class="form-check-label" for="include_comments">
                                            Comments & Notes
                                        </label>
                                    </div>
                                </div>

                                {{-- Quality Settings --}}
                                <div class="mb-3">
                                    <label for="image_quality" class="form-label fw-bold">Image Quality:</label>
                                    <select class="form-select form-select-sm" id="image_quality" name="image_quality">
                                        <option value="high">High (Best Quality)</option>
                                        <option value="medium" selected>Medium (Balanced)</option>
                                        <option value="low">Low (Smaller File)</option>
                                    </select>
                                </div>

                                {{-- Page Orientation --}}
                                <div class="mb-3">
                                    <label for="page_orientation" class="form-label fw-bold">Page Orientation:</label>
                                    <select class="form-select form-select-sm" id="page_orientation" name="page_orientation">
                                        <option value="portrait" selected>Portrait</option>
                                        <option value="landscape">Landscape</option>
                                    </select>
                                </div>

                                {{-- Language --}}
                                <div class="mb-3">
                                    <label for="export_language" class="form-label fw-bold">Language:</label>
                                    <select class="form-select form-select-sm" id="export_language" name="export_language">
                                        <option value="en" selected>English</option>
                                        <option value="es">Spanish</option>
                                        <option value="fr">French</option>
                                        <option value="de">German</option>
                                    </select>
                                </div>

                                {{-- Watermark --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="add_watermark" name="add_watermark" value="1">
                                    <label class="form-check-label" for="add_watermark">
                                        Add Company Watermark
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bulk Export --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-layer-group me-1"></i>Bulk Export
                    </h6>
                    <div class="card border">
                        <div class="card-body">
                            <p class="text-muted mb-3">Export multiple inspections at once</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="bulk_export_format" class="form-label">Export Format:</label>
                                    <select class="form-select" id="bulk_export_format" name="bulk_export_format">
                                        <option value="pdf">PDF (Individual Files)</option>
                                        <option value="pdf_combined">PDF (Combined File)</option>
                                        <option value="excel">Excel Workbook</option>
                                        <option value="zip">ZIP Archive</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="bulk_date_range" class="form-label">Date Range:</label>
                                    <select class="form-select" id="bulk_date_range" name="bulk_date_range">
                                        <option value="current">Current Inspection Only</option>
                                        <option value="week">Last 7 Days</option>
                                        <option value="month">Last 30 Days</option>
                                        <option value="quarter">Last 3 Months</option>
                                        <option value="year">Last Year</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary" onclick="bulkExport()">
                                    <i class="fas fa-download me-1"></i>Start Bulk Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Email Export --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-envelope me-1"></i>Email Export
                    </h6>
                    <div class="card border">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_recipients" class="form-label">Recipients:</label>
                                    <textarea class="form-control" id="email_recipients" name="email_recipients" rows="3" 
                                              placeholder="Enter email addresses separated by commas...">{{ $inspection->client_email ?? '' }}</textarea>
                                    <div class="form-text">Separate multiple emails with commas</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_subject" class="form-label">Subject:</label>
                                    <input type="text" class="form-control" id="email_subject" name="email_subject" 
                                           value="Inspection Report - {{ $inspection->inspection_number ?? 'INS-' . date('Y-m-d') }}" 
                                           placeholder="Email subject">
                                    
                                    <label for="email_format" class="form-label mt-2">Format:</label>
                                    <select class="form-select" id="email_format" name="email_format">
                                        <option value="pdf">PDF Attachment</option>
                                        <option value="inline">Inline HTML</option>
                                        <option value="both">Both PDF and HTML</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="email_message" class="form-label">Message:</label>
                                <textarea class="form-control" id="email_message" name="email_message" rows="3" 
                                          placeholder="Optional message to include with the inspection report...">Please find attached the inspection report for your review.</textarea>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success" onclick="emailExport()">
                                    <i class="fas fa-paper-plane me-1"></i>Send Email
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Export History --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-history me-1"></i>Export History
                    </h6>
                    <div class="card border">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Format</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="exportHistoryTable">
                                        {{-- This would typically come from database --}}
                                        <tr>
                                            <td>{{ date('Y-m-d H:i') }}</td>
                                            <td><i class="fas fa-file-pdf text-danger"></i> PDF</td>
                                            <td>Standard Report</td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="downloadPrevious('pdf_123')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <small>No previous exports found</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary" onclick="quickExport('pdf')">
                        <i class="fas fa-file-pdf me-1"></i>Quick PDF
                    </button>
                    <button type="button" class="btn btn-success" onclick="quickExport('excel')">
                        <i class="fas fa-file-excel me-1"></i>Quick Excel
                    </button>
                    <button type="button" class="btn btn-info" onclick="previewReport()">
                        <i class="fas fa-eye me-1"></i>Preview
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="saveTemplate()">
                        <i class="fas fa-save me-1"></i>Save Template
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Export Progress Modal --}}
<div class="modal fade" id="exportProgressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-download me-2"></i>Exporting...
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p id="exportProgressText">Preparing export...</p>
                    <div class="progress">
                        <div class="progress-bar" id="exportProgressBar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Export Functions --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize export functionality
    initializeExportSection();
});

function initializeExportSection() {
    // Set default values
    const inspectionId = {{ $inspection->id ?? 'null' }};
    
    // Update email subject with inspection number
    const emailSubject = document.getElementById('email_subject');
    if (emailSubject && !emailSubject.value.includes('INS-')) {
        emailSubject.value = `Inspection Report - INS-${inspectionId || new Date().toISOString().slice(0, 10)}`;
    }
}

function exportToPDF(type = 'standard') {
    const inspectionId = {{ $inspection->id ?? 'null' }};
    if (!inspectionId) {
        alert('Please save the inspection before exporting.');
        return;
    }
    
    showExportProgress('Generating PDF report...');
    
    const options = getExportOptions();
    const url = `/inspections/${inspectionId}/export/pdf?type=${type}&${new URLSearchParams(options).toString()}`;
    
    // Simulate progress
    simulateProgress(() => {
        window.open(url, '_blank');
        hideExportProgress();
    });
}

function exportToExcel(type = 'data') {
    const inspectionId = {{ $inspection->id ?? 'null' }};
    if (!inspectionId) {
        alert('Please save the inspection before exporting.');
        return;
    }
    
    showExportProgress('Generating Excel spreadsheet...');
    
    const options = getExportOptions();
    const url = `/inspections/${inspectionId}/export/excel?type=${type}&${new URLSearchParams(options).toString()}`;
    
    simulateProgress(() => {
        window.open(url, '_blank');
        hideExportProgress();
    });
}

function exportToWord() {
    const inspectionId = {{ $inspection->id ?? 'null' }};
    if (!inspectionId) {
        alert('Please save the inspection before exporting.');
        return;
    }
    
    showExportProgress('Generating Word document...');
    
    const options = getExportOptions();
    const url = `/inspections/${inspectionId}/export/word?${new URLSearchParams(options).toString()}`;
    
    simulateProgress(() => {
        window.open(url, '_blank');
        hideExportProgress();
    });
}

function exportToCSV() {
    const inspectionId = {{ $inspection->id ?? 'null' }};
    if (!inspectionId) {
        alert('Please save the inspection before exporting.');
        return;
    }
    
    showExportProgress('Generating CSV data...');
    
    const options = getExportOptions();
    const url = `/inspections/${inspectionId}/export/csv?${new URLSearchParams(options).toString()}`;
    
    simulateProgress(() => {
        window.open(url, '_blank');
        hideExportProgress();
    });
}

function bulkExport() {
    const format = document.getElementById('bulk_export_format').value;
    const dateRange = document.getElementById('bulk_date_range').value;
    
    showExportProgress('Preparing bulk export...');
    
    const options = getExportOptions();
    options.format = format;
    options.date_range = dateRange;
    
    const url = `/inspections/bulk-export?${new URLSearchParams(options).toString()}`;
    
    simulateProgress(() => {
        window.open(url, '_blank');
        hideExportProgress();
    });
}

function emailExport() {
    const inspectionId = {{ $inspection->id ?? 'null' }};
    if (!inspectionId) {
        alert('Please save the inspection before emailing.');
        return;
    }
    
    const recipients = document.getElementById('email_recipients').value.trim();
    if (!recipients) {
        alert('Please enter at least one email recipient.');
        return;
    }
    
    showExportProgress('Sending email...');
    
    const emailData = {
        recipients: recipients,
        subject: document.getElementById('email_subject').value,
        message: document.getElementById('email_message').value,
        format: document.getElementById('email_format').value,
        ...getExportOptions()
    };
    
    // Simulate email sending
    fetch(`/inspections/${inspectionId}/email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify(emailData)
    })
    .then(response => response.json())
    .then(data => {
        hideExportProgress();
        if (data.success) {
            alert('Email sent successfully!');
            addToExportHistory('Email', 'Sent', new Date());
        } else {
            alert('Failed to send email: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        hideExportProgress();
        alert('Error sending email: ' + error.message);
    });
}

function quickExport(format) {
    switch(format) {
        case 'pdf':
            exportToPDF('standard');
            break;
        case 'excel':
            exportToExcel('data');
            break;
        default:
            alert('Unsupported format: ' + format);
    }
}

function previewReport() {
    const inspectionId = {{ $inspection->id ?? 'null' }};
    if (!inspectionId) {
        alert('Please save the inspection before previewing.');
        return;
    }
    
    const url = `/inspections/${inspectionId}/preview`;
    window.open(url, '_blank');
}

function saveTemplate() {
    const options = getExportOptions();
    localStorage.setItem('inspection_export_template', JSON.stringify(options));
    alert('Export template saved successfully!');
}

function getExportOptions() {
    const options = {};
    
    // Get checked export options
    const exportOptions = document.querySelectorAll('input[name="export_options[]"]:checked');
    options.include = Array.from(exportOptions).map(cb => cb.value);
    
    // Get other settings
    options.image_quality = document.getElementById('image_quality').value;
    options.page_orientation = document.getElementById('page_orientation').value;
    options.language = document.getElementById('export_language').value;
    options.watermark = document.getElementById('add_watermark').checked;
    
    return options;
}

function showExportProgress(message) {
    document.getElementById('exportProgressText').textContent = message;
    document.getElementById('exportProgressBar').style.width = '0%';
    new bootstrap.Modal(document.getElementById('exportProgressModal')).show();
}

function hideExportProgress() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportProgressModal'));
    if (modal) {
        modal.hide();
    }
}

function simulateProgress(callback) {
    let progress = 0;
    const progressBar = document.getElementById('exportProgressBar');
    const progressText = document.getElementById('exportProgressText');
    
    const interval = setInterval(() => {
        progress += Math.random() * 20;
        if (progress >= 100) {
            progress = 100;
            progressBar.style.width = '100%';
            progressText.textContent = 'Export completed!';
            clearInterval(interval);
            setTimeout(callback, 500);
        } else {
            progressBar.style.width = progress + '%';
        }
    }, 200);
}

function addToExportHistory(format, type, date) {
    const table = document.getElementById('exportHistoryTable');
    const row = table.insertRow(0);
    
    row.innerHTML = `
        <td>${date.toLocaleString()}</td>
        <td><i class="fas fa-file-${format.toLowerCase()} text-primary"></i> ${format}</td>
        <td>${type}</td>
        <td><span class="badge bg-success">Completed</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary" onclick="downloadPrevious('${format.toLowerCase()}_${Date.now()}')">
                <i class="fas fa-download"></i>
            </button>
        </td>
    `;
    
    // Remove "No previous exports" message if it exists
    const noExportsRow = table.querySelector('td[colspan="5"]');
    if (noExportsRow) {
        noExportsRow.parentElement.remove();
    }
}

function downloadPrevious(exportId) {
    alert('Download functionality would be implemented here for export ID: ' + exportId);
}

// Load saved template on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedTemplate = localStorage.getItem('inspection_export_template');
    if (savedTemplate) {
        try {
            const options = JSON.parse(savedTemplate);
            
            // Apply saved options
            if (options.include) {
                options.include.forEach(value => {
                    const checkbox = document.querySelector(`input[name="export_options[]"][value="${value}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
            if (options.image_quality) {
                document.getElementById('image_quality').value = options.image_quality;
            }
            
            if (options.page_orientation) {
                document.getElementById('page_orientation').value = options.page_orientation;
            }
            
            if (options.language) {
                document.getElementById('export_language').value = options.language;
            }
            
            if (options.watermark !== undefined) {
                document.getElementById('add_watermark').checked = options.watermark;
            }
        } catch (e) {
            console.error('Error loading export template:', e);
        }
    }
});
</script>