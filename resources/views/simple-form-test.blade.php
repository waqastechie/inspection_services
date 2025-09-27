<!DOCTYPE html>
<html>
<head>
    <title>Simple Form Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Simple Inspection Form Test</h2>
    
    @if(session('success'))
        <div style="color: green; background: #e8f5e8; padding: 10px; margin: 10px 0;">
            SUCCESS: {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="color: red; background: #ffe8e8; padding: 10px; margin: 10px 0;">
            ERROR: {{ session('error') }}
        </div>
    @endif
    
    @if ($errors->any())
        <div style="color: red; background: #ffe8e8; padding: 10px; margin: 10px 0;">
            <strong>Validation Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('inspections.store') }}">
        @csrf
        
        <p><label>Client Name: <input type="text" name="client_name" value="Test Client" required></label></p>
        <p><label>Project Name: <input type="text" name="project_name" value="Test Project" required></label></p>
        <p><label>Location: <input type="text" name="location" value="Test Location" required></label></p>
        <p><label>Inspection Date: <input type="date" name="inspection_date" value="2025-01-15" required></label></p>
        <p><label>Lead Inspector: <input type="text" name="lead_inspector_name" value="Test Inspector"></label></p>
        <p><label>Inspector Cert: <input type="text" name="lead_inspector_certification" value="Test Cert"></label></p>
        
        <input type="hidden" name="selected_services" value='[{"type":"lifting-examination","name":"Lifting Examination","description":"Lifting Examination inspection service"}]'>
        <input type="hidden" name="service_sections_data" value='{"lifting-examination":{"lifting_examination_inspector":"1","first_examination":"yes","equipment_installation_details":"test","safe_to_operate":"yes","equipment_defect":"no","defect_description":"","existing_danger":"","potential_danger":"","defect_timeline":"","repair_details":"","test_details":"test details"}}'>
        <input type="hidden" name="personnel_assignments" value="[]">
        <input type="hidden" name="equipment_assignments" value="[]">
        <input type="hidden" name="consumable_assignments" value="[]">
        
        <p><input type="submit" value="Submit Test Form"></p>
    </form>
</body>
</html>
