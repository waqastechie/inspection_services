<!DOCTYPE html>
<html>
<head>
    <title>Form Submission Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
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
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
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
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <h1>Form Submission Test</h1>
    <p>This page tests the form submission to verify the SQL error is fixed.</p>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('inspections.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="client_name">Client Name (Required):</label>
            <input type="text" id="client_name" name="client_name" value="{{ old('client_name', 'Test Client') }}" required>
        </div>
        
        <div class="form-group">
            <label for="project_name">Project Name (Required):</label>
            <input type="text" id="project_name" name="project_name" value="{{ old('project_name', 'Test Project') }}" required>
        </div>
        
        <div class="form-group">
            <label for="location">Location (Required):</label>
            <input type="text" id="location" name="location" value="{{ old('location', 'Test Location') }}" required>
        </div>
        
        <div class="form-group">
            <label for="inspection_date">Inspection Date (Required):</label>
            <input type="date" id="inspection_date" name="inspection_date" value="{{ old('inspection_date', '2025-08-31') }}" required>
        </div>
        
        <div class="form-group">
            <label for="lead_inspector_name">Lead Inspector Name:</label>
            <input type="text" id="lead_inspector_name" name="lead_inspector_name" value="{{ old('lead_inspector_name', 'Test Inspector') }}">
        </div>
        
        <div class="form-group">
            <label for="equipment_type">Equipment Type:</label>
            <input type="text" id="equipment_type" name="equipment_type" value="{{ old('equipment_type', 'Test Equipment') }}">
        </div>
        
        <div class="form-group">
            <label for="equipment_description">Equipment Description:</label>
            <input type="text" id="equipment_description" name="equipment_description" value="{{ old('equipment_description', 'Test Equipment Description') }}">
        </div>
        
        <button type="submit">Submit Test Form</button>
    </form>
    
    <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 4px;">
        <h3>Test Instructions:</h3>
        <ol>
            <li>Fill in the required fields (or use the defaults)</li>
            <li>Click "Submit Test Form"</li>
            <li>Check if the form submits successfully without SQL errors</li>
            <li>The system should create an inspection record with proper default values</li>
        </ol>
    </div>
</body>
</html>
