<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveInspectionEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = $this->input('equipment_data');

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['equipment_data' => $decoded]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'inspection_id' => ['required', 'exists:inspections,id'],
            'equipment_data' => ['nullable', 'array'],
            'equipment_data.*.equipment_type_id' => ['nullable', 'exists:equipment_types,id'],
            'equipment_data.*.parent_equipment_id' => ['nullable', 'integer'],
            'equipment_data.*.category' => ['nullable', 'string', 'max:50'],
            'equipment_data.*.equipment_type' => ['nullable', 'string', 'max:255'],
            'equipment_data.*.serial_number' => ['nullable', 'string', 'max:255'],
            'equipment_data.*.description' => ['nullable', 'string'],
            'equipment_data.*.reason_for_examination' => ['nullable', 'string', 'max:255'],
            'equipment_data.*.model' => ['nullable', 'string', 'max:255'],
            'equipment_data.*.swl' => ['nullable', 'numeric'],
            'equipment_data.*.test_load_applied' => ['nullable', 'numeric'],
            'equipment_data.*.date_of_manufacture' => ['nullable', 'date'],
            'equipment_data.*.date_of_last_examination' => ['nullable', 'date'],
            'equipment_data.*.date_of_next_examination' => ['nullable', 'date'],
            'equipment_data.*.status' => ['nullable', 'string', 'max:50'],
            'equipment_data.*.remarks' => ['nullable', 'string'],
            'equipment_data.*.condition' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'inspection_id.required' => 'Inspection reference is required.',
            'inspection_id.exists' => 'The specified inspection could not be found.',
            'equipment_data.array' => 'Equipment data must be provided as an array.',
        ];
    }
}
