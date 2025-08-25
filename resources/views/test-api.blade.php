<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Test</title>
</head>
<body>
    <h1>API Test Page</h1>
    
    <h2>Personnel API Test</h2>
    <button onclick="testPersonnelAPI()">Test Personnel API</button>
    <div id="personnelResult"></div>
    
    <h2>Inspector API Test</h2>
    <button onclick="testInspectorAPI()">Test Inspector API</button>
    <div id="inspectorResult"></div>

    <script>
        async function testPersonnelAPI() {
            try {
                const response = await fetch('/inspections/api/personnel', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                document.getElementById('personnelResult').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('personnelResult').innerHTML = 'Error: ' + error.message;
            }
        }

        async function testInspectorAPI() {
            try {
                const response = await fetch('/inspections/api/inspectors', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                document.getElementById('inspectorResult').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('inspectorResult').innerHTML = 'Error: ' + error.message;
            }
        }
    </script>
</body>
</html>
