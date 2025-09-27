<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestRealFormSubmission extends Command
{
    protected $signature = 'test:real-form';
    protected $description = 'Test a realistic form submission to see what would be saved';

    public function handle()
    {
        $this->info('=== TESTING REALISTIC FORM SUBMISSION ===');
        $this->newLine();

        // Simulate a typical form submission with mixed data
        $formData = [
            'client_name' => 'Test Client Company',
            'project_name' => 'Factory Inspection Project',
            'location' => '123 Industrial Ave, Houston, TX',
            'area_of_examination' => 'Main Production Area',
            'services_performed' => 'Visual Inspection, Load Testing',
            'contract' => 'CONTRACT-2025-001',
            'work_order' => 'WO-12345',
            'purchase_order' => '',  // User left this empty
            'client_job_reference' => 'REF-789',
            'job_ref' => '',  // Empty string
            'standards' => 'ASME B30.1',
            'local_procedure_number' => 'LPN-001',
            'drawing_number' => '',  // Empty
            'test_restrictions' => 'None',
            'surface_condition' => '   ',  // Whitespace only
            'inspection_date' => '2025-01-15',
            'lead_inspector_name' => 'John Smith',
            'lead_inspector_certification' => 'Level II Inspector',
            'weather_conditions' => 'Sunny, 75°F',
            'temperature' => '',  // Empty
            'humidity' => '',  // Empty
            'equipment_type' => 'Crane',
            'equipment_description' => '50 Ton Overhead Crane',
            'manufacturer' => '',  // Empty
            'model' => '',  // Empty
            'serial_number' => 'SN123456',
            'asset_tag' => '',  // Empty
            'manufacture_year' => '',  // Empty
            'capacity' => '50',
            'capacity_unit' => 'tons',
            'inspector_comments' => 'Equipment in good working condition',
            'recommendations' => 'Continue regular maintenance schedule',
            'defects_found' => '',  // No defects
            'overall_result' => 'pass',
            'next_inspection_date' => '2026-01-15',
            'general_notes' => '   ',  // Whitespace
            'lifting_examination_inspector' => '1',
            'load_test_inspector' => '',  // Not selected
            'thorough_examination_inspector' => '2',
            'mpi_service_inspector' => '',  // Not selected
            'visual_inspector' => '3',
        ];

        $request = new Request($formData);
        
        $this->info('📝 Simulating form field processing...');
        $this->newLine();
        
        $savedWithFilled = 0;
        $savedWithHas = 0;
        $totalFields = count($formData);
        
        foreach ($formData as $field => $value) {
            $filled = $request->filled($field);
            $hasAndNotNull = $request->has($field) && $request->input($field) !== null;
            
            if ($filled) $savedWithFilled++;
            if ($hasAndNotNull) $savedWithHas++;
            
            $status = '';
            if ($filled && $hasAndNotNull) {
                $status = '✅ Both save';
            } elseif (!$filled && $hasAndNotNull) {
                $status = '💡 Only new method saves';
            } elseif ($filled && !$hasAndNotNull) {
                $status = '⚠️  Only old method saves';
            } else {
                $status = '❌ Neither saves';
            }
            
            $displayValue = $value === '' ? "''" : ($value === '   ' ? "'   '" : "'{$value}'");
            $this->line("{$field}: {$displayValue} - {$status}");
        }
        
        $this->newLine();
        $this->info('📊 SUMMARY:');
        $this->line("Total form fields: {$totalFields}");
        $this->line("Fields saved with filled(): {$savedWithFilled} ({$this->percentage($savedWithFilled, $totalFields)}%)");
        $this->line("Fields saved with has()+!==null: {$savedWithHas} ({$this->percentage($savedWithHas, $totalFields)}%)");
        $this->line("Improvement: +" . ($savedWithHas - $savedWithFilled) . " fields ({$this->percentage($savedWithHas - $savedWithFilled, $totalFields)}% more data)");
        
        if ($savedWithHas > $savedWithFilled) {
            $this->info('✅ The new method captures more form data!');
        }
        
        return 0;
    }
    
    private function percentage($part, $total)
    {
        return $total > 0 ? round(($part / $total) * 100, 1) : 0;
    }
}
