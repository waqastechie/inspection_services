<!DOCTYPE html>
<html>
<head>
    <title>Client API Test</title>
</head>
<body>
    <h1>Client API Test</h1>
    <button onclick="testClientAPI()">Test Client API</button>
    <div id="results"></div>

    <script>
        function testClientAPI() {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = 'Loading...';
            
            console.log('Testing client API...');
            
            fetch('/api/clients')
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response OK:', response.ok);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data);
                    
                    if (Array.isArray(data)) {
                        resultsDiv.innerHTML = `
                            <h2>Success! Found ${data.length} clients:</h2>
                            <ul>
                                ${data.map(client => `<li>${client.client_name} (${client.client_code || 'No code'})</li>`).join('')}
                            </ul>
                            <h3>Raw Data:</h3>
                            <pre>${JSON.stringify(data, null, 2)}</pre>
                        `;
                    } else {
                        resultsDiv.innerHTML = `
                            <h2>Unexpected Response:</h2>
                            <pre>${JSON.stringify(data, null, 2)}</pre>
                        `;
                    }
                })
                .catch(error => {
                    console.error('API Error:', error);
                    resultsDiv.innerHTML = `
                        <h2>Error:</h2>
                        <p style="color: red;">${error.message}</p>
                    `;
                });
        }
    </script>
</body>
</html>
