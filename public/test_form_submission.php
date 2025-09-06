<!DOCTYPE html>
<html>
<head>
    <title>🧪 Inspection Form Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🧪 Inspection Data Saving Test</h1>
    <p>This form tests if all inspection data is being saved correctly.</p>

    <form action="/inspections/store" method="POST" id="testForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        <!-- Basic Information -->
        <h3>📋 Basic Information</h3>
        <div class="form-group">
            <label>Client Name *</label>
            <input type="text" name="client_name" value="Test Client Form" required>
        </div>
        
        <div class="form-group">
            <label>Project Name *</label>
            <input type="text" name="project_name" value="Test Project Form" required>
        </div>
        
        <div class="form-group">
            <label>Location *</label>
            <input type="text" name="location" value="Test Location Form" required>
        </div>
        
        <div class="form-group">
            <label>Area of Examination</label>
            <input type="text" name="area_of_examination" value="Test Area Form">
        </div>
        
        <div class="form-group">
            <label>Services Performed</label>
            <textarea name="services_performed">Test Services Form</textarea>
        </div>
        
        <!-- Contract Information -->
        <h3>📄 Contract Information</h3>
        <div class="form-group">
            <label>Contract</label>
            <input type="text" name="contract" value="TEST-CONTRACT-FORM">
        </div>
        
        <div class="form-group">
            <label>Work Order</label>
            <input type="text" name="work_order" value="WO-FORM-001">
        </div>
        
        <div class="form-group">
            <label>Purchase Order</label>
            <input type="text" name="purchase_order" value="PO-FORM-001">
        </div>
        
        <div class="form-group">
            <label>Client Job Reference</label>
            <input type="text" name="client_job_reference" value="CJR-FORM-001">
        </div>
        
        <div class="form-group">
            <label>Job Reference</label>
            <input type="text" name="job_ref" value="JR-FORM-001">
        </div>
        
        <!-- Inspection Details -->
        <h3>🔍 Inspection Details</h3>
        <div class="form-group">
            <label>Inspection Date *</label>
            <input type="date" name="inspection_date" value="<?= date('Y-m-d') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Lead Inspector Name *</label>
            <input type="text" name="lead_inspector_name" value="Test Inspector Form" required>
        </div>
        
        <div class="form-group">
            <label>Lead Inspector Certification *</label>
            <input type="text" name="lead_inspector_certification" value="Test Certification Form" required>
        </div>
        
        <div class="form-group">
            <label>Equipment Type *</label>
            <input type="text" name="equipment_type" value="Test Equipment Form" required>
        </div>
        
        <!-- Additional Fields -->
        <h3>📝 Additional Information</h3>
        <div class="form-group">
            <label>Standards</label>
            <input type="text" name="standards" value="Test Standards Form">
        </div>
        
        <div class="form-group">
            <label>Local Procedure Number</label>
            <input type="text" name="local_procedure_number" value="LPN-FORM-001">
        </div>
        
        <div class="form-group">
            <label>Drawing Number</label>
            <input type="text" name="drawing_number" value="DWG-FORM-001">
        </div>
        
        <div class="form-group">
            <label>Test Restrictions</label>
            <textarea name="test_restrictions">Test restrictions from form</textarea>
        </div>
        
        <div class="form-group">
            <label>Surface Condition</label>
            <textarea name="surface_condition">Good surface condition from form</textarea>
        </div>
        
        <div class="form-group">
            <label>Inspector Comments</label>
            <textarea name="inspector_comments">Test comments from form submission</textarea>
        </div>
        
        <div class="form-group">
            <label>Next Inspection Date</label>
            <input type="date" name="next_inspection_date" value="<?= date('Y-m-d', strtotime('+1 year')) ?>">
        </div>
        
        <!-- Service Inspectors -->
        <h3>👥 Service Inspectors</h3>
        <div class="form-group">
            <label>Lifting Examination Inspector</label>
            <select name="lifting_examination_inspector">
                <option value="">Select Inspector</option>
                <?php
                // Simple PHP to get personnel options
                try {
                    require_once 'vendor/autoload.php';
                    $app = require_once 'bootstrap/app.php';
                    $personnel = App\Models\Personnel::all();
                    foreach ($personnel as $person) {
                        echo "<option value='{$person->id}'>{$person->first_name} {$person->last_name}</option>";
                    }
                } catch (Exception $e) {
                    echo "<option value='1'>Test Inspector</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Load Test Inspector</label>
            <select name="load_test_inspector">
                <option value="">Select Inspector</option>
                <?php
                try {
                    foreach ($personnel as $person) {
                        echo "<option value='{$person->id}'>{$person->first_name} {$person->last_name}</option>";
                    }
                } catch (Exception $e) {
                    echo "<option value='1'>Test Inspector</option>";
                }
                ?>
            </select>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit">🚀 Submit Test Inspection</button>
        </div>
    </form>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            console.log('Form submitted with data:', new FormData(this));
        });
    </script>
</body>
</html>
