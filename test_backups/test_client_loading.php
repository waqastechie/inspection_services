<!DOCTYPE html>
<html>
<head>
    <title>Test Client Loading</title>
    <meta name="csrf-token" content="test-token">
    <script>
        window.APP_BASE_URL = '';
    </script>
</head>
<body>
    <h1>Test Client Loading</h1>
    
    <div>
        <label>Select Client:</label>
        <select id="client_id" name="client_id">
            <option value="">Loading clients...</option>
        </select>
    </div>
    
    <div id="output" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>
    
    <script>
        // Build absolute URL respecting base path
        function apiUrl(path) {
            const base = typeof window.APP_BASE_URL === "string" && window.APP_BASE_URL.length
                ? window.APP_BASE_URL.replace(/\/$/, "")
                : "";
            const clean = String(path || "").replace(/^\/+/, "");
            return `${base}/${clean}`;
        }
        
        function log(message) {
            const output = document.getElementById('output');
            output.innerHTML += '<div>' + new Date().toLocaleTimeString() + ': ' + message + '</div>';
            console.log(message);
        }
        
        function loadClients() {
            log("Starting to load clients...");
            log("API URL: " + apiUrl("/api/clients"));
            
            const clientSelect = document.getElementById('client_id');
            
            fetch(apiUrl("/api/clients"), {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                },
                credentials: "same-origin",
            })
            .then(response => {
                log("Response status: " + response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                log("Response data: " + JSON.stringify(data));
                
                if (data.success && Array.isArray(data.clients)) {
                    clientSelect.innerHTML = '<option value="">Choose a client...</option>';
                    
                    data.clients.forEach(client => {
                        const option = document.createElement('option');
                        option.value = client.id;
                        option.textContent = client.display_name || client.client_name;
                        clientSelect.appendChild(option);
                    });
                    
                    log(`Successfully loaded ${data.clients.length} clients`);
                } else {
                    log("Invalid response format or no clients found");
                    clientSelect.innerHTML = '<option value="">No clients found</option>';
                }
            })
            .catch(error => {
                log("Error loading clients: " + error.message);
                clientSelect.innerHTML = '<option value="">Error loading clients</option>';
            });
        }
        
        // Load clients when page loads
        document.addEventListener('DOMContentLoaded', loadClients);
    </script>
</body>
</html>