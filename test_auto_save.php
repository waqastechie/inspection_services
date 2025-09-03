<?php
session_start();

// Simple test page to verify auto-save functionality
?>
<!DOCTYPE html>
<html>
<head>
    <title>Auto-Save Test</title>
    <meta name="csrf-token" content="test-token">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .status {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .info { background-color: #d1ecf1; color: #0c5460; }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Auto-Save Test</h1>
    <p>This page tests the enhanced auto-save functionality with database storage.</p>
    
    <div id="auto-save-status" class="status info">Ready</div>
    
    <form id="testForm">
        <div class="form-group">
            <label for="client_name">Client Name:</label>
            <input type="text" id="client_name" name="client_name" placeholder="Enter client name...">
        </div>
        
        <div class="form-group">
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" placeholder="Enter project name...">
        </div>
        
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Enter location...">
        </div>
        
        <div class="form-group">
            <label for="inspection_date">Inspection Date:</label>
            <input type="date" id="inspection_date" name="inspection_date">
        </div>
        
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" rows="4" placeholder="Enter notes..."></textarea>
        </div>
        
        <button type="button" onclick="manualSave()">Manual Save</button>
        <button type="button" onclick="testRestore()">Test Restore</button>
        <button type="button" onclick="clearData()">Clear Data</button>
    </form>
    
    <div id="draft-info" style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">
        <strong>Draft Info:</strong>
        <div>Draft ID: <span id="current-draft-id">None</span></div>
        <div>Last Saved: <span id="last-saved-time">Never</span></div>
    </div>

    <script>
        // Simple auto-save variables
        let currentDraftId = null;
        let autoSaveTimeout = null;
        
        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Test page loaded');
            
            // Add event listeners for auto-save
            const form = document.getElementById('testForm');
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                input.addEventListener('input', debounceAutoSave);
                input.addEventListener('change', debounceAutoSave);
            });
            
            // Try to restore data on load
            restoreData();
        });
        
        // Debounced auto-save
        function debounceAutoSave() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(autoSave, 2000);
            updateStatus('Typing...', 'info');
        }
        
        // Auto-save function
        async function autoSave() {
            const formData = getFormData();
            
            if (Object.keys(formData).length === 0) {
                updateStatus('No data to save', 'info');
                return;
            }
            
            updateStatus('Saving...', 'info');
            
            try {
                const response = await fetch('/inspections/save-draft', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        draft_id: currentDraftId,
                        form_data: formData,
                        selected_services: [],
                        personnel_assignments: [],
                        equipment_assignments: [],
                        consumable_assignments: [],
                        uploaded_images: [],
                        service_sections_data: {}
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    currentDraftId = result.draft_id;
                    updateStatus('Saved successfully', 'success');
                    updateDraftInfo();
                } else {
                    updateStatus('Save failed: ' + result.message, 'error');
                }
                
            } catch (error) {
                updateStatus('Save error: ' + error.message, 'error');
                console.error('Auto-save error:', error);
            }
        }
        
        // Manual save
        async function manualSave() {
            await autoSave();
        }
        
        // Test restore
        async function testRestore() {
            if (!currentDraftId) {
                updateStatus('No draft ID to restore', 'error');
                return;
            }
            
            try {
                const response = await fetch(`/inspections/get-draft/${currentDraftId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    updateStatus('Draft not found', 'error');
                    return;
                }
                
                const result = await response.json();
                
                if (result.success && result.draft) {
                    restoreFormData(result.draft.form_data);
                    updateStatus('Data restored successfully', 'success');
                } else {
                    updateStatus('Restore failed: ' + result.message, 'error');
                }
                
            } catch (error) {
                updateStatus('Restore error: ' + error.message, 'error');
                console.error('Restore error:', error);
            }
        }
        
        // Clear data
        async function clearData() {
            if (currentDraftId) {
                try {
                    await fetch(`/inspections/delete-draft/${currentDraftId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                } catch (error) {
                    console.error('Delete error:', error);
                }
            }
            
            // Clear form
            document.getElementById('testForm').reset();
            currentDraftId = null;
            updateStatus('Data cleared', 'info');
            updateDraftInfo();
        }
        
        // Get form data
        function getFormData() {
            const form = document.getElementById('testForm');
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (value.trim() !== '') {
                    data[key] = value;
                }
            }
            
            return data;
        }
        
        // Restore form data
        function restoreFormData(data) {
            if (!data) return;
            
            Object.keys(data).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    field.value = data[key];
                }
            });
        }
        
        // Restore data on page load
        async function restoreData() {
            // Check localStorage for draft ID
            const savedDraftId = localStorage.getItem('test_draft_id');
            if (savedDraftId) {
                currentDraftId = savedDraftId;
                await testRestore();
                updateDraftInfo();
            }
        }
        
        // Update status display
        function updateStatus(message, type) {
            const statusDiv = document.getElementById('auto-save-status');
            statusDiv.textContent = message;
            statusDiv.className = `status ${type}`;
        }
        
        // Update draft info
        function updateDraftInfo() {
            document.getElementById('current-draft-id').textContent = currentDraftId || 'None';
            document.getElementById('last-saved-time').textContent = new Date().toLocaleString();
            
            // Save draft ID to localStorage for persistence
            if (currentDraftId) {
                localStorage.setItem('test_draft_id', currentDraftId);
            } else {
                localStorage.removeItem('test_draft_id');
            }
        }
    </script>
</body>
</html>
