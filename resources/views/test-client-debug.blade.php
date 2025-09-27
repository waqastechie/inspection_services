<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Client API Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Client API Debug Test</h5>
                    </div>
                    <div class="card-body">
                        <!-- Searchable Client Field Test -->
                        <div class="mb-4">
                            <h6>Searchable Client Field Test</h6>
                            <div class="position-relative">
                                <input 
                                    type="text" 
                                    id="clientSelect" 
                                    name="client_select" 
                                    class="form-control"
                                    placeholder="Start typing to search clients..."
                                    autocomplete="off"
                                >
                                <div id="clientDropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto; position: absolute; top: 100%; left: 0; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    <!-- Client options will be populated here -->
                                </div>
                            </div>
                        </div>

                        <!-- Client Name Field -->
                        <div class="mb-4">
                            <label for="client_name" class="form-label">Client Name (Auto-filled)</label>
                            <input type="text" name="client_name" id="client_name" class="form-control" readonly>
                        </div>

                        <!-- Debug Buttons -->
                        <div class="mb-4">
                            <button type="button" class="btn btn-primary" onclick="testClientAPI()">Test Client API</button>
                            <button type="button" class="btn btn-secondary" onclick="loadClientData()">Load Client Data</button>
                            <button type="button" class="btn btn-info" onclick="checkAuth()">Check Auth</button>
                        </div>

                        <!-- Debug Output -->
                        <div class="mb-4">
                            <h6>Debug Output:</h6>
                            <pre id="debugOutput" class="bg-dark text-light p-3" style="height: 300px; overflow-y: auto;"></pre>
                        </div>

                        <!-- User Info -->
                        <div class="mb-4">
                            <h6>Current User:</h6>
                            <p>Email: {{ auth()->user()->email ?? 'Not logged in' }}</p>
                            <p>Role: {{ auth()->user()->role ?? 'N/A' }}</p>
                            <p>Can see clients: {{ (auth()->user() && in_array(auth()->user()->role, ['admin', 'super_admin'])) ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    #clientDropdown .dropdown-item {
        padding: 10px 15px;
        border: none;
        background: white;
        color: #333;
        text-decoration: none;
        display: block;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    #clientDropdown .dropdown-item:hover,
    #clientDropdown .dropdown-item.active {
        background-color: #007bff;
        color: white;
    }
    
    #clientDropdown .dropdown-item.text-muted {
        color: #6c757d !important;
        font-style: italic;
    }
    
    #clientDropdown .dropdown-item.text-muted:hover {
        background-color: #f8f9fa;
        color: #6c757d !important;
    }
    
    #clientDropdown .dropdown-item:last-child {
        border-bottom: none;
    }
    </style>

    <script>
        // Simplified client data loading for debugging
        let clientsData = [];
        
        function log(message) {
            const output = document.getElementById('debugOutput');
            const timestamp = new Date().toLocaleTimeString();
            output.textContent += `[${timestamp}] ${message}\n`;
            output.scrollTop = output.scrollHeight;
            console.log(message);
        }
        
        function testClientAPI() {
            log("=== Testing Client API Directly ===");
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const headers = {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "Content-Type": "application/json",
            };

            if (csrfToken) {
                headers["X-CSRF-TOKEN"] = csrfToken.getAttribute("content");
                log("CSRF token found: " + csrfToken.getAttribute("content").substring(0, 10) + "...");
            } else {
                log("CSRF token NOT found!");
            }
            
            log("Making request to: /api/clients");
            
            fetch("/api/clients", {
                method: "GET",
                headers: headers,
                credentials: "same-origin",
            })
            .then((response) => {
                log(`Response status: ${response.status}`);
                log(`Response headers: ${JSON.stringify([...response.headers.entries()])}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then((text) => {
                log(`Raw response: ${text}`);
                try {
                    const data = JSON.parse(text);
                    log(`Parsed JSON: ${JSON.stringify(data, null, 2)}`);
                    return data;
                } catch (e) {
                    throw new Error("Invalid JSON response: " + text);
                }
            })
            .then((clients) => {
                if (Array.isArray(clients) && clients.length > 0) {
                    log(`SUCCESS: Found ${clients.length} clients`);
                    clientsData = clients;
                    clients.forEach((client, index) => {
                        log(`Client ${index + 1}: ${client.client_name} (${client.client_code || 'No code'})`);
                    });
                } else {
                    log("No clients found in response");
                }
            })
            .catch((error) => {
                log(`ERROR: ${error.message}`);
            });
        }
        
        function checkAuth() {
            log("=== Checking Authentication ===");
            
            fetch("/inspections/debug-auth")
            .then(response => response.json())
            .then(data => {
                log(`Auth status: ${JSON.stringify(data, null, 2)}`);
            })
            .catch(error => {
                log(`Auth check error: ${error.message}`);
            });
        }
        
        function loadClientData() {
            log("=== Loading Client Data (Full Function) ===");
            
            const clientSelect = document.getElementById("clientSelect");
            const clientDropdown = document.getElementById("clientDropdown");
            
            if (!clientSelect) {
                log("ERROR: Client select input not found");
                return;
            }
            
            log("Client select found, starting load...");
            
            // Show loading state
            clientSelect.placeholder = "Loading clients...";
            clientSelect.disabled = true;

            // Add CSRF token for Laravel
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const headers = {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "Content-Type": "application/json",
            };

            if (csrfToken) {
                headers["X-CSRF-TOKEN"] = csrfToken.getAttribute("content");
            }

            fetch("/api/clients", {
                method: "GET",
                headers: headers,
                credentials: "same-origin",
            })
            .then((response) => {
                log(`API Response status: ${response.status}`);

                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error("Authentication required - please log in");
                    } else if (response.status === 403) {
                        throw new Error("Access denied - admin privileges required");
                    } else if (response.status === 404) {
                        throw new Error("API endpoint not found");
                    } else {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                }
                return response.text();
            })
            .then((text) => {
                log(`Raw API response: ${text}`);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("Invalid JSON response: " + text);
                }
            })
            .then((clients) => {
                log(`Parsed client data: ${JSON.stringify(clients)}`);

                // Reset the input
                clientSelect.disabled = false;
                clientSelect.placeholder = "Start typing to search clients...";

                if (Array.isArray(clients) && clients.length > 0) {
                    // Store clients data for searching
                    clientsData = clients;
                    
                    log(`Successfully loaded ${clients.length} clients from database`);
                    
                    // Initialize searchable functionality
                    initializeSearchableDropdown();
                    
                } else {
                    log("No clients found in database response");
                    clientSelect.placeholder = "No clients found - Please add clients first";
                }
            })
            .catch((error) => {
                log(`Failed to load clients from database: ${error.message}`);

                // Reset input to original state
                clientSelect.disabled = false;
                clientSelect.placeholder = "Start typing to search clients...";

                // Add some fallback demo clients for testing
                const demoClients = [
                    { id: 1, client_name: "Saipem Trechville", client_code: "ST" },
                    { id: 2, client_name: "TotalEnergies Offshore", client_code: "TEO" },
                    { id: 3, client_name: "ENI Exploration", client_code: "ENI" },
                    { id: 4, client_name: "Petroci Holding", client_code: "PCH" },
                    { id: 5, client_name: "FOXTROT International", client_code: "FI" },
                ];

                log("Adding demo clients as fallback");
                clientsData = demoClients;
                initializeSearchableDropdown();
            });
        }
        
        function initializeSearchableDropdown() {
            const clientSelect = document.getElementById("clientSelect");
            const clientDropdown = document.getElementById("clientDropdown");
            
            if (!clientSelect || !clientDropdown) {
                log("ERROR: Client search elements not found");
                return;
            }
            
            log(`Initializing searchable dropdown with ${clientsData.length} clients`);
            
            let selectedIndex = -1; // Track keyboard selection
            
            // Input event for search
            clientSelect.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                log(`Searching for: "${searchTerm}"`);
                
                selectedIndex = -1; // Reset selection
                
                if (searchTerm.length < 1) {
                    clientDropdown.style.display = 'none';
                    return;
                }
                
                // Filter clients based on search term
                const filteredClients = clientsData.filter(client => 
                    client.client_name.toLowerCase().includes(searchTerm) ||
                    (client.client_code && client.client_code.toLowerCase().includes(searchTerm))
                );
                
                log(`Filtered results: ${filteredClients.length} clients`);
                
                // Populate dropdown
                populateDropdown(filteredClients);
            });
            
            // Focus event to show all clients
            clientSelect.addEventListener('focus', function() {
                if (this.value.length === 0) {
                    log("Focus event - showing all clients");
                    populateDropdown(clientsData);
                }
            });
            
            // Click outside to hide dropdown
            document.addEventListener('click', function(e) {
                if (!clientSelect.contains(e.target) && !clientDropdown.contains(e.target)) {
                    clientDropdown.style.display = 'none';
                    selectedIndex = -1;
                }
            });
            
            log("Searchable dropdown initialized successfully");
        }
        
        function populateDropdown(clients) {
            const clientDropdown = document.getElementById("clientDropdown");
            
            if (clients.length === 0) {
                clientDropdown.innerHTML = '<div class="dropdown-item text-muted">No clients found</div>';
                clientDropdown.style.display = 'block';
                return;
            }
            
            clientDropdown.innerHTML = '';
            
            clients.forEach(client => {
                const item = document.createElement('div');
                item.className = 'dropdown-item';
                
                const displayText = client.client_code ? 
                    `${client.client_name} (${client.client_code})` : 
                    client.client_name;
                    
                item.textContent = displayText;
                item.dataset.clientData = JSON.stringify(client);
                
                item.addEventListener('click', function() {
                    selectClient(client);
                });
                
                clientDropdown.appendChild(item);
            });
            
            clientDropdown.style.display = 'block';
            log(`Dropdown populated with ${clients.length} clients`);
        }
        
        function selectClient(client) {
            log(`Client selected: ${client.client_name}`);
            
            const clientSelect = document.getElementById("clientSelect");
            const clientDropdown = document.getElementById("clientDropdown");
            
            // Set the search input value
            const displayText = client.client_code ? 
                `${client.client_name} (${client.client_code})` : 
                client.client_name;
            clientSelect.value = displayText;
            
            // Hide dropdown
            clientDropdown.style.display = 'none';
            
            // Populate the client name field
            const clientNameField = document.querySelector('[name="client_name"]');
            
            if (clientNameField) {
                clientNameField.value = client.client_name;
                log(`Client name field populated with: ${client.client_name}`);
            } else {
                log("ERROR: Client name field not found!");
            }
            
            // Store selected client data
            clientSelect.dataset.selectedClient = JSON.stringify(client);
        }
        
        // Auto-load on page load
        document.addEventListener('DOMContentLoaded', function() {
            log("Page loaded - ready for testing");
        });
    </script>
</body>
</html>
