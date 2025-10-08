<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'lead_inspector_name' => ['nullable', 'string', 'max:255'],
            'lead_inspector_certification' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
            'load_test_duration' => ['nullable', 'string', 'max:255'],
            'load_test_two_points_diagonal' => ['nullable', 'string', 'max:255'],
            'load_test_four_points' => ['nullable', 'string', 'max:255'],
            'load_test_deflection' => ['nullable', 'string', 'max:255'],
            'load_test_deformation' => ['nullable', 'string', 'max:255'],
            'load_test_distance_from_ground' => ['nullable', 'string', 'max:255'],
            'load_test_result' => ['nullable', 'string', 'in:Pass,Fail'],
            'load_test_notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Please select the client for this inspection.',
            'client_id.exists' => 'The selected client is invalid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'client_id' => 'client',
            'project_name' => 'project name',
            'load_test_duration' => 'load test duration',
            'load_test_two_points_diagonal' => 'load test two points diagonal',
            'load_test_four_points' => 'load test four points',
            'load_test_deflection' => 'load test deflection',
            'load_test_deformation' => 'load test deformation',
            'load_test_distance_from_ground' => 'load test distance from ground',
            'load_test_result' => 'load test result',
            'load_test_notes' => 'load test notes',
            'lead_inspector_name' => 'lead inspector name',
            'lead_inspector_certification' => 'lead inspector certification',
        ];
    }
}
