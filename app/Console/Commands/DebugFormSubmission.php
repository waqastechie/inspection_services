<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DebugFormSubmission extends Command
{
    protected $signature = 'debug:form-submission';
    protected $description = 'Add debugging to form submission to see what data is being sent';

    public function handle()
    {
        $this->info('=== FORM SUBMISSION DEBUGGING SETUP ===');
        $this->newLine();
        
        // Add debugging to the controller store method
        $controllerPath = app_path('Http/Controllers/InspectionController.php');
        $content = file_get_contents($controllerPath);
        
        // Check if debugging is already added
        if (strpos($content, 'DEBUG_FORM_SUBMISSION') !== false) {
            $this->info('✅ Debugging already enabled in controller');
        } else {
            $this->info('📝 Adding debugging to controller...');
            
            // Add debug code after validation
            $debugCode = '
        // DEBUG_FORM_SUBMISSION - Log all request data
        Log::info("=== INSPECTION FORM SUBMISSION DEBUG ===");
        Log::info("Request all data:", $request->all());
        
        $fieldsToCheck = [
            "area_of_examination", "services_performed", "contract", "work_order",
            "lifting_examination_inspector", "load_test_inspector", "manufacturer", 
            "model", "serial_number", "temperature", "humidity"
        ];
        
        foreach ($fieldsToCheck as $field) {
            $value = $request->input($field);
            $filled = $request->filled($field);
            $has = $request->has($field);
            Log::info("Field: {$field} | Value: \'{$value}\' | Filled: " . ($filled ? "YES" : "NO") . " | Has: " . ($has ? "YES" : "NO"));
        }
        Log::info("=== END DEBUG ===");
        // END DEBUG_FORM_SUBMISSION
';
            
            // Insert after validation, before try block
            $searchFor = 'if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {';
                
            $replaceWith = 'if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }' . $debugCode . '

        try {';
            
            $newContent = str_replace($searchFor, $replaceWith, $content);
            
            if ($newContent !== $content) {
                file_put_contents($controllerPath, $newContent);
                $this->info('✅ Debugging code added to controller');
            } else {
                $this->error('❌ Could not add debugging code - pattern not found');
                return 1;
            }
        }
        
        $this->newLine();
        $this->info('🎯 DEBUGGING IS NOW ACTIVE');
        $this->info('📝 Next steps:');
        $this->line('1. Submit an inspection form');
        $this->line('2. Check the Laravel logs: storage/logs/laravel.log');
        $this->line('3. Look for "INSPECTION FORM SUBMISSION DEBUG" entries');
        $this->line('4. Run: php artisan debug:remove-debugging (when done)');
        
        return 0;
    }
}
