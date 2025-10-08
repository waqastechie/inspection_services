<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Basic Information
            'inspection_number' => 'required|string|max:255|unique:inspections,inspection_number',
            'client_name' => 'required|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'area_of_examination' => 'nullable|string|max:255',
            'services_performed' => 'nullable|array',
            'services_performed.*' => 'string|max:255',
            'contract' => 'nullable|string|max:255',
            'work_order' => 'nullable|string|max:255',
            'purchase_order' => 'nullable|string|max:255',
            'client_job_reference' => 'nullable|string|max:255',
            'job_ref' => 'nullable|string|max:255',
            'standards' => 'nullable|string|max:255',
            'local_procedure_number' => 'nullable|string|max:255',
            'drawing_number' => 'nullable|string|max:255',
            'test_restrictions' => 'nullable|string',
            'surface_condition' => 'nullable|string|max:255',
            'inspection_date' => 'required|date',
            'weather_conditions' => 'nullable|string|max:255',
            'temperature' => 'nullable|numeric|min:-50|max:100',
            'humidity' => 'nullable|numeric|min:0|max:100',
            
            // Equipment Under Test
            'equipment_type' => 'required|string|max:255',
            'equipment_description' => 'required|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'asset_tag' => 'nullable|string|max:255',
            'manufacture_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit' => 'nullable|string|in:kg,tons,lbs,tonnes',
            
            // Certification & Standards
            'applicable_standard' => 'nullable|string|max:255',
            'inspection_class' => 'nullable|string|max:255',
            'certification_body' => 'nullable|string|max:255',
            'previous_certificate_number' => 'nullable|string|max:255',
            'last_inspection_date' => 'nullable|date|before_or_equal:today',
            'next_inspection_due' => 'nullable|date|after:today',
            'next_inspection_date' => 'nullable|date|after:inspection_date',
            
            // Test Results
            'overall_result' => 'required|in:pass,fail,conditional_pass',
            'defects_found' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'limitations' => 'nullable|string',
            
            // Inspector Information
            'lead_inspector_name' => 'required|string|max:255',
            'lead_inspector_certification' => 'nullable|string|max:255',
            'inspector_signature' => 'nullable|string',
            'report_date' => 'required|date',
            
            // Service Inspector Assignments
            'lifting_examination_inspector' => 'nullable|string|max:255',
            'load_test_inspector' => 'nullable|string|max:255',
            'thorough_examination_inspector' => 'nullable|string|max:255',
            'mpi_service_inspector' => 'nullable|string|max:255',
            'visual_inspector' => 'nullable|string|max:255',
            
            // Load Test Specific Fields
            'load_test_duration' => 'nullable|string|max:255',
            'load_test_two_points_diagonal' => 'nullable|string|max:255',
            'load_test_four_points' => 'nullable|string|max:255',
            'load_test_deflection' => 'nullable|string|max:255',
            'load_test_deformation' => 'nullable|string|max:255',
            'load_test_distance_from_ground' => 'nullable|string|max:255',
            'load_test_result' => 'nullable|string|in:Pass,Fail',
            'load_test_notes' => 'nullable|string',

            // Additional Notes
            'general_notes' => 'nullable|string',
            'inspector_comments' => 'nullable|string',
            'service_notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            
            // Images
            'inspection_images' => 'nullable|array',
            'inspection_images.*' => 'image|mimes:jpg,jpeg,png,gif|max:10240', // 10MB max
            
            'status' => 'nullable|string|in:draft,pending,completed,reviewed',
            
            // Personnel assignments
            'personnel_assignments' => 'nullable|array',
            'personnel_assignments.*.personnel_id' => 'required|exists:personnel,id',
            'personnel_assignments.*.role' => 'required|string|max:255',
            
            // Equipment assignments
            'equipment_assignments' => 'nullable|array',
            'equipment_assignments.*.equipment_id' => 'required|exists:equipment,id',
            'equipment_assignments.*.quantity' => 'required|integer|min:1',
            
            // Consumable assignments
            'consumable_assignments' => 'nullable|array',
            'consumable_assignments.*.consumable_id' => 'required|exists:consumables,id',
            'consumable_assignments.*.quantity_used' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'inspection_number.required' => 'The report number is required.',
            'inspection_number.unique' => 'This report number already exists.',
            'client_name.required' => 'The client name is required.',
            'location.required' => 'The serial number is required.',
            'equipment_type.required' => 'The equipment type is required.',
            'equipment_description.required' => 'The equipment description is required.',
            'inspection_date.required' => 'The inspection date is required.',
            'inspection_date.date' => 'Please enter a valid inspection date.',
            'overall_result.required' => 'The overall result is required.',
            'overall_result.in' => 'Please select a valid overall result.',
            'load_test_result.in' => 'Please select a valid load test result.',
            'lead_inspector_name.required' => 'The lead inspector name is required.',
            'report_date.required' => 'The report date is required.',
            'next_inspection_date.after' => 'The next inspection date must be after the current inspection date.',
            'last_inspection_date.before_or_equal' => 'The last inspection date cannot be in the future.',
            'temperature.min' => 'Temperature must be at least -50°C.',
            'temperature.max' => 'Temperature cannot exceed 100°C.',
            'humidity.min' => 'Humidity must be at least 0%.',
            'humidity.max' => 'Humidity cannot exceed 100%.',
            'inspection_images.*.image' => 'Each file must be an image.',
            'inspection_images.*.max' => 'Each image must not exceed 10MB.',
            'attachments.*.max' => 'Each attachment must not exceed 10MB.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'inspection_number' => 'report number',
            'client_name' => 'client name',
            'location' => 'serial number',
            'equipment_type' => 'equipment type',
            'equipment_description' => 'equipment description',
            'inspection_date' => 'inspection date',
            'overall_result' => 'overall result',
            'load_test_duration' => 'load test duration',
            'load_test_two_points_diagonal' => 'load test two points diagonal',
            'load_test_four_points' => 'load test four points',
            'load_test_deflection' => 'load test deflection',
            'load_test_deformation' => 'load test deformation',
            'load_test_distance_from_ground' => 'load test distance from ground',
            'load_test_result' => 'load test result',
            'load_test_notes' => 'load test notes',
            'lead_inspector_name' => 'lead inspector name',
            'report_date' => 'report date',
            'next_inspection_date' => 'next inspection date',
            'last_inspection_date' => 'last inspection date',
        ];
    }
}
