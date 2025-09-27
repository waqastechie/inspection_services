<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DiagnoseProblem extends Command
{
    protected $signature = 'diagnose:problem';
    protected $description = 'Diagnose current system problems';

    public function handle()
    {
        $this->info('=== SYSTEM DIAGNOSTICS ===');
        $this->newLine();
        
        // 1. Check database connection
        $this->info('1. Database Connection:');
        try {
            DB::connection()->getPdo();
            $this->info('   ✅ Database connected successfully');
        } catch (\Exception $e) {
            $this->error('   ❌ Database connection failed: ' . $e->getMessage());
        }
        
        // 2. Check critical models
        $this->info('2. Model Status:');
        try {
            $inspectionCount = \App\Models\Inspection::count();
            $this->info("   ✅ Inspections model working ({$inspectionCount} records)");
        } catch (\Exception $e) {
            $this->error('   ❌ Inspections model error: ' . $e->getMessage());
        }
        
        try {
            $personnelCount = \App\Models\Personnel::count();
            $this->info("   ✅ Personnel model working ({$personnelCount} records)");
        } catch (\Exception $e) {
            $this->error('   ❌ Personnel model error: ' . $e->getMessage());
        }
        
        try {
            $draftCount = \App\Models\InspectionDraft::count();
            $this->info("   ✅ InspectionDraft model working ({$draftCount} records)");
        } catch (\Exception $e) {
            $this->error('   ❌ InspectionDraft model error: ' . $e->getMessage());
        }
        
        // 3. Check controller files
        $this->info('3. Controller Syntax:');
        $controllerFile = app_path('Http/Controllers/InspectionController.php');
        if (file_exists($controllerFile)) {
            $output = shell_exec("php -l {$controllerFile} 2>&1");
            if (strpos($output, 'No syntax errors') !== false) {
                $this->info('   ✅ InspectionController syntax OK');
            } else {
                $this->error('   ❌ InspectionController syntax error: ' . $output);
            }
        }
        
        // 4. Check for common issues
        $this->info('4. Common Issues Check:');
        
        // Check if all required columns exist
        try {
            $columns = DB::select("SHOW COLUMNS FROM inspections");
            $columnNames = array_column($columns, 'Field');
            
            $requiredColumns = [
                'lifting_examination_inspector',
                'load_test_inspector', 
                'thorough_examination_inspector',
                'mpi_service_inspector',
                'visual_inspector'
            ];
            
            foreach ($requiredColumns as $col) {
                if (in_array($col, $columnNames)) {
                    $this->info("   ✅ Column '{$col}' exists");
                } else {
                    $this->error("   ❌ Missing column '{$col}'");
                }
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Column check failed: ' . $e->getMessage());
        }
        
        // 5. Test basic operations
        $this->info('5. Basic Operations Test:');
        try {
            $testData = [
                'client_name' => 'Test Client',
                'project_name' => 'Test Project',
                'location' => 'Test Location',
                'inspection_date' => now(),
                'inspection_number' => 'TEST-' . time(),
                'status' => 'draft'
            ];
            
            $inspection = \App\Models\Inspection::create($testData);
            $this->info('   ✅ Create inspection: SUCCESS');
            
            $inspection->delete();
            $this->info('   ✅ Delete inspection: SUCCESS');
            
        } catch (\Exception $e) {
            $this->error('   ❌ Basic operations failed: ' . $e->getMessage());
        }
        
        // 6. Check recent logs
        $this->info('6. Recent Error Logs:');
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $recentLogs = shell_exec("tail -20 {$logFile} | grep -i error");
            if ($recentLogs) {
                $this->warn('   ⚠️  Recent errors found:');
                $this->line($recentLogs);
            } else {
                $this->info('   ✅ No recent errors in logs');
            }
        }
        
        $this->newLine();
        $this->info('=== DIAGNOSIS COMPLETE ===');
        
        return 0;
    }
}
