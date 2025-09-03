<!DOCTYPE html>
<html>
<head>
    <title>AJAX Form Submission Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .feature-list {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .feature-list ul {
            margin: 0;
            padding-left: 20px;
        }
        .feature-list li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1><i class="fas fa-rocket me-2"></i>AJAX Form Submission Test</h1>
        <p class="lead">This page demonstrates the enhanced form submission with loader and no page refresh.</p>
        
        <div class="feature-list">
            <h5><i class="fas fa-star me-2"></i>New Features:</h5>
            <ul>
                <li><strong>No Page Refresh:</strong> Form submits via AJAX without refreshing the page</li>
                <li><strong>Beautiful Loader:</strong> Professional loading overlay shows during submission</li>
                <li><strong>Progress Feedback:</strong> Real-time progress indicator and status messages</li>
                <li><strong>Error Handling:</strong> Graceful error display with detailed messages</li>
                <li><strong>Success Animation:</strong> Smooth success notification with auto-redirect</li>
                <li><strong>Safe Submission:</strong> Data is safely stored before any navigation</li>
            </ul>
        </div>
        
        <form id="liftingInspectionForm" method="POST" action="{{ route('inspections.store') }}" class="needs-validation" novalidate>
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="client_name" class="form-label">Client Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="client_name" name="client_name" 
                           value="AJAX Test Client" required>
                    <div class="invalid-feedback">Please provide a client name.</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="project_name" name="project_name" 
                           value="AJAX Submission Test Project" required>
                    <div class="invalid-feedback">Please provide a project name.</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="location" name="location" 
                           value="Test Location - AJAX Demo" required>
                    <div class="invalid-feedback">Please provide a location.</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="inspection_date" class="form-label">Inspection Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="inspection_date" name="inspection_date" 
                           value="{{ date('Y-m-d') }}" required>
                    <div class="invalid-feedback">Please provide an inspection date.</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="lead_inspector_name" class="form-label">Lead Inspector Name</label>
                    <input type="text" class="form-control" id="lead_inspector_name" name="lead_inspector_name" 
                           value="AJAX Test Inspector">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="equipment_type" class="form-label">Equipment Type</label>
                    <input type="text" class="form-control" id="equipment_type" name="equipment_type" 
                           value="AJAX Test Equipment">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="equipment_description" class="form-label">Equipment Description</label>
                <textarea class="form-control" id="equipment_description" name="equipment_description" rows="3">This is a test equipment description for AJAX form submission testing. The form should submit without refreshing the page and show a professional loader.</textarea>
            </div>
            
            <div class="mb-4">
                <label for="general_notes" class="form-label">General Notes</label>
                <textarea class="form-control" id="general_notes" name="general_notes" rows="3">Testing the enhanced AJAX form submission with loader and progress feedback. This should demonstrate the new user experience where the form doesn't refresh and provides real-time feedback.</textarea>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <small><i class="fas fa-info-circle me-1"></i>Click submit to see the enhanced submission experience</small>
                </div>
                <button type="submit" id="saveReportBtn" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Test AJAX Submission
                </button>
            </div>
        </form>
        
        <div class="mt-4 p-3 bg-light rounded">
            <h6><i class="fas fa-lightbulb me-2"></i>What to Expect:</h6>
            <ol class="mb-0">
                <li>Click the "Test AJAX Submission" button</li>
                <li>A professional loader overlay will appear</li>
                <li>The form will submit without page refresh</li>
                <li>You'll see a success notification</li>
                <li>The page will redirect to the inspection details after 2 seconds</li>
            </ol>
        </div>
    </div>
    
    <!-- Include the enhanced form handler -->
    <script src="{{ asset('js/inspection-form.js') }}"></script>
    
    <script>
        // Simple form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
