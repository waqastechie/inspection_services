<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JavaScript Error Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; background: #ffe6e6; padding: 10px; margin: 10px 0; border: 1px solid #ff9999; }
        .success { color: green; background: #e6ffe6; padding: 10px; margin: 10px 0; border: 1px solid #99ff99; }
        pre { background: #f5f5f5; padding: 15px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>JavaScript Error Debug Test</h1>
    
    <div id="status">Testing...</div>
    
    <h3>Function Availability Test:</h3>
    <div id="functionTest"></div>
    
    <h3>Console Output:</h3>
    <pre id="consoleOutput"></pre>
    
    <script>
        // Capture console output
        const originalLog = console.log;
        const originalError = console.error;
        const output = document.getElementById('consoleOutput');
        
        function addToOutput(type, message) {
            const timestamp = new Date().toLocaleTimeString();
            output.textContent += `[${timestamp}] ${type}: ${message}\n`;
        }
        
        console.log = function(...args) {
            addToOutput('LOG', args.join(' '));
            originalLog.apply(console, args);
        };
        
        console.error = function(...args) {
            addToOutput('ERROR', args.join(' '));
            originalError.apply(console, args);
        };
        
        // Test basic functionality
        try {
            console.log("Starting JavaScript test...");
            document.getElementById('status').innerHTML = '<div class="success">JavaScript is working</div>';
            
            // Test if the external script loads
            const testFunctions = [
                'initializeForm',
                'initializeFormValidation', 
                'loadPersonnelData',
                'loadEquipmentData',
                'loadConsumableData',
                'loadClientData',
                'initializeServiceCheckboxes',
                'initializeDynamicSections',
                'updateFormProgress'
            ];
            
            let results = '<ul>';
            testFunctions.forEach(funcName => {
                const exists = typeof window[funcName] === 'function';
                const status = exists ? '✅' : '❌';
                const color = exists ? 'green' : 'red';
                results += `<li style="color: ${color};">${status} ${funcName}: ${typeof window[funcName]}</li>`;
            });
            results += '</ul>';
            
            document.getElementById('functionTest').innerHTML = results;
            
        } catch (error) {
            console.error("JavaScript test failed:", error);
            document.getElementById('status').innerHTML = '<div class="error">JavaScript Error: ' + error.message + '</div>';
        }
    </script>
    
    <!-- Load the problematic script -->
    <script src="{{ asset('js/inspection-form-simple.js') }}"></script>
    
    <script>
        // Test after script loads
        setTimeout(() => {
            console.log("Post-load test...");
            
            // Re-test function availability
            const testFunctions = [
                'initializeForm',
                'initializeFormValidation', 
                'loadPersonnelData',
                'loadEquipmentData',
                'loadConsumableData',
                'loadClientData',
                'initializeServiceCheckboxes',
                'initializeDynamicSections',
                'updateFormProgress'
            ];
            
            let results = '<h4>After Script Load:</h4><ul>';
            testFunctions.forEach(funcName => {
                const exists = typeof window[funcName] === 'function';
                const status = exists ? '✅' : '❌';
                const color = exists ? 'green' : 'red';
                results += `<li style="color: ${color};">${status} ${funcName}: ${typeof window[funcName]}</li>`;
            });
            results += '</ul>';
            
            document.getElementById('functionTest').innerHTML += results;
            
            // Try to call initializeForm manually
            try {
                if (typeof initializeForm === 'function') {
                    console.log("Attempting to call initializeForm...");
                    // Don't actually call it, just test if it exists
                    console.log("initializeForm is callable");
                } else {
                    console.error("initializeForm is not a function");
                }
            } catch (error) {
                console.error("Error testing initializeForm:", error);
            }
            
        }, 1000);
    </script>
</body>
</html>
