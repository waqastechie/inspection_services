<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inspection;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TestFormFilledVsHas extends Command
{
    protected $signature = 'test:form-conditions';
    protected $description = 'Test the difference between filled() and has() methods';

    public function handle()
    {
        $this->info('=== TESTING FORM FIELD CONDITIONS ===');
        $this->newLine();

        // Create a mock request with various field types
        $testData = [
            'field_with_value' => 'Some Value',
            'field_empty_string' => '',
            'field_whitespace' => '   ',
            'field_zero' => '0',
            'field_false' => '0',
            'field_null' => null,
            'field_missing' => null,  // This won't be in the request
            'lifting_examination_inspector' => '1',
            'load_test_inspector' => '',
            'manufacturer' => '   ',
            'model' => null,
        ];
        
        // Remove the null fields to simulate missing form fields
        unset($testData['field_null']);
        unset($testData['field_missing']);
        unset($testData['model']);
        
        $request = new Request($testData);
        
        $this->info('🧪 Testing different field conditions:');
        $this->newLine();
        
        $fieldsToTest = [
            'field_with_value', 'field_empty_string', 'field_whitespace', 
            'field_zero', 'field_false', 'field_null', 'field_missing',
            'lifting_examination_inspector', 'load_test_inspector', 
            'manufacturer', 'model'
        ];
        
        foreach ($fieldsToTest as $field) {
            $value = $request->input($field);
            $has = $request->has($field);
            $filled = $request->filled($field);
            $notNull = $request->input($field) !== null;
            
            $this->line("Field: {$field}");
            $this->line("  Value: '" . ($value ?? 'NULL') . "'");
            $this->line("  has(): " . ($has ? 'TRUE' : 'FALSE'));
            $this->line("  filled(): " . ($filled ? 'TRUE' : 'FALSE'));
            $this->line("  !== null: " . ($notNull ? 'TRUE' : 'FALSE'));
            
            // Our new condition
            $ourCondition = $has && $notNull;
            $this->line("  Our condition (has && !== null): " . ($ourCondition ? 'TRUE' : 'FALSE'));
            
            if ($filled && !$ourCondition) {
                $this->error("  ⚠️  filled() is TRUE but our condition is FALSE");
            } elseif (!$filled && $ourCondition) {
                $this->warn("  💡 filled() is FALSE but our condition is TRUE (this might save more data)");
            } elseif ($filled === $ourCondition) {
                $this->info("  ✅ Both conditions match");
            }
            
            $this->newLine();
        }
        
        $this->info('📊 ANALYSIS:');
        $this->line('- filled() is stricter and rejects empty strings and whitespace');
        $this->line('- has() + !== null is more permissive and allows empty strings');
        $this->line('- For form data, has() + !== null might capture more user input');
        $this->line('- This could explain why some fields were not saving');
        
        return 0;
    }
}
