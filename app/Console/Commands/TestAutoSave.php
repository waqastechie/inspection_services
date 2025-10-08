<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestAutoSave extends Command
{
    protected $signature = 'test:autosave';
    protected $description = 'Test auto-save functionality';

    public function handle()
    {
        $this->info('=== TESTING AUTO-SAVE FUNCTIONALITY ===');
        
        try {
            // Simulate the data that might be sent from the frontend
            $testData = [
                'form_data' => [
                    'client_name' => 'Test Client',
                    'project_name' => 'Test Project',
                    'location' => 'Test Location'
                ],
                'selected_services' => [
                    ['type' => 'visual', 'name' => 'Visual Inspection']
                ],
                'personnel_assignments' => [],
                'equipment_assignments' => [],
                'consumable_assignments' => [],
                'uploaded_images' => [],
                'service_sections_data' => []
            ];
            
            $this->info('Creating test draft...');
            
            $draft = new \App\Models\InspectionDraft();
            $draft->draft_id = 'TEST_' . time();
            $draft->user_session = 'test_session';
            $draft->ip_address = '127.0.0.1';
            
            // Test the assignment that was causing the error
            $draft->form_data = $testData['form_data']; // This should be JSON
            $draft->selected_services = $testData['selected_services'];
            $draft->personnel_assignments = $testData['personnel_assignments'];
            $draft->equipment_assignments = $testData['equipment_assignments'];
            $draft->consumable_assignments = $testData['consumable_assignments'];
            $draft->uploaded_images = $testData['uploaded_images'];
            $draft->service_sections_data = $testData['service_sections_data'];
            $draft->last_saved_at = now();
            
            $draft->save();
            
            $this->info('✅ Auto-save test successful! Draft ID: ' . $draft->draft_id);
            
            // Clean up
            $draft->delete();
            $this->info('✅ Test cleanup completed');
            
        } catch (\Exception $e) {
            $this->error('❌ Auto-save test failed: ' . $e->getMessage());
            $this->error('Error details: ' . $e->getFile() . ':' . $e->getLine());
        }
        
        return 0;
    }
}
