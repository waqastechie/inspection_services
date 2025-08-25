<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Debug Personnel & Inspector Loading</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h1>Debug Personnel & Inspector Loading</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h3>Personnel Dropdown Test</h3>
                <select id="personnel_id" class="form-control">
                    <option value="">Loading personnel...</option>
                </select>
                <button onclick="loadPersonnelManual()" class="btn btn-primary mt-2">Load Personnel</button>
                <button onclick="initPersonnelSelect2()" class="btn btn-secondary mt-2">Initialize Select2</button>
            </div>
            
            <div class="col-md-6">
                <h3>Inspector Dropdown Test</h3>
                <select id="leadInspectorName" class="form-control">
                    <option value="">Loading inspectors...</option>
                </select>
                <textarea id="leadInspectorCertification" class="form-control mt-2" placeholder="Qualification will auto-fill"></textarea>
                <button onclick="loadInspectorManual()" class="btn btn-primary mt-2">Load Inspectors</button>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>Debug Log</h3>
            <div id="debugLog" style="background: #f8f9fa; padding: 15px; height: 300px; overflow-y: scroll; font-family: monospace; font-size: 12px;"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        function log(message) {
            const debugLog = document.getElementById('debugLog');
            const timestamp = new Date().toLocaleTimeString();
            debugLog.innerHTML += `[${timestamp}] ${message}\n`;
            debugLog.scrollTop = debugLog.scrollHeight;
            console.log(message);
        }

        async function loadPersonnelManual() {
            log("=== Starting Personnel Load ===");
            const personnelSelect = document.getElementById("personnel_id");
            
            try {
                log("Fetching from /inspections/api/personnel");
                const response = await fetch("/inspections/api/personnel", {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });
                
                log(`Response status: ${response.status}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const personnel = await response.json();
                log(`Personnel data received: ${JSON.stringify(personnel)}`);

                personnelSelect.innerHTML = '<option value="">Select personnel...</option>';

                if (Array.isArray(personnel) && personnel.length > 0) {
                    personnel.forEach((person) => {
                        const option = document.createElement("option");
                        option.value = person.id;
                        option.textContent = `${person.name} - ${person.position}`;
                        option.dataset.person = JSON.stringify(person);
                        personnelSelect.appendChild(option);
                    });
                    log(`Loaded ${personnel.length} personnel records`);
                    
                    // Initialize Select2 for personnel
                    setTimeout(() => {
                        initPersonnelSelect2();
                    }, 100);
                } else {
                    personnelSelect.innerHTML = '<option value="">No personnel found</option>';
                    log("No personnel data available");
                }

            } catch (error) {
                log(`Error loading personnel: ${error.message}`);
                personnelSelect.innerHTML = '<option value="">Error loading personnel</option>';
            }
        }
        
        function initPersonnelSelect2() {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('#personnel_id').select2('destroy'); // Destroy existing first
                $('#personnel_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Search and select personnel...',
                    allowClear: true,
                    width: '100%'
                });
                log("Personnel Select2 initialized");
            } else {
                log("jQuery or Select2 not available");
            }
        }

        async function loadInspectorManual() {
            log("=== Starting Inspector Load ===");
            const inspectorSelect = document.getElementById("leadInspectorName");
            
            try {
                log("Fetching from /inspections/api/inspectors");
                const response = await fetch("/inspections/api/inspectors", {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });
                
                log(`Response status: ${response.status}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const inspectors = await response.json();
                log(`Inspector data received: ${JSON.stringify(inspectors)}`);

                inspectorSelect.innerHTML = '<option value="">Select Inspector...</option>';

                if (Array.isArray(inspectors) && inspectors.length > 0) {
                    inspectors.forEach((inspector) => {
                        const option = document.createElement("option");
                        option.value = inspector.name;
                        option.textContent = inspector.display_name;
                        option.dataset.inspector = JSON.stringify(inspector);
                        inspectorSelect.appendChild(option);
                    });
                    log(`Loaded ${inspectors.length} inspector records`);
                    
                    // Initialize Select2
                    setTimeout(() => {
                        $('#leadInspectorName').select2({
                            theme: 'bootstrap-5',
                            placeholder: 'Search and select inspector...',
                            allowClear: true,
                            width: '100%'
                        });
                        log("Select2 initialized");
                    }, 100);
                } else {
                    inspectorSelect.innerHTML = '<option value="">No inspectors found</option>';
                    log("No inspector data available");
                }

            } catch (error) {
                log(`Error loading inspectors: ${error.message}`);
                inspectorSelect.innerHTML = '<option value="">Error loading inspectors</option>';
            }
        }

        // Auto-populate qualification when inspector selected
        document.getElementById('leadInspectorName').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const qualificationField = document.getElementById('leadInspectorCertification');
            
            if (selectedOption && selectedOption.dataset.inspector) {
                try {
                    const inspector = JSON.parse(selectedOption.dataset.inspector);
                    qualificationField.value = inspector.certification || '';
                    log(`Auto-populated qualification for: ${inspector.name}`);
                } catch (error) {
                    log(`Error parsing inspector data: ${error.message}`);
                    qualificationField.value = '';
                }
            } else {
                qualificationField.value = '';
            }
        });

        // Auto-load on page ready
        document.addEventListener('DOMContentLoaded', function() {
            log("=== Page Loaded - Auto-loading data ===");
            loadPersonnelManual();
            loadInspectorManual();
        });
    </script>
</body>
</html>
