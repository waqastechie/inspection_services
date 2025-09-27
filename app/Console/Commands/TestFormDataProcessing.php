<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestFormDataProcessing extends Command
{
    protected $signature = 'test:form-data';
    protected $description = 'Test how form data should be processed';

    public function handle()
    {
        $this->info('=== TESTING FORM DATA PROCESSING ===');
        $this->newLine();
        
        // Simulate the data format that comes from the frontend
        $simulatedRequest = [
            'client_name' => 'Test Client',
            'project_name' => 'Test Project', 
            'location' => 'Test Location',
            'inspection_date' => '2025-09-06',
            
            // New format - complex data
            'selected_services' => [
                '{"type":"lifting","name":"Lifting Equipment Inspection","description":"Complete lifting equipment inspection"}',
                '{"type":"mpi","name":"MPI Service","description":"Magnetic particle inspection"}'
            ],
            
            'equipment_assignments' => [
                [
                    'name' => 'Crane Inspection Equipment',
                    'type' => 'lifting',
                    'brand' => 'ACME Tools',
                    'serial' => 'SN123456',
                    'condition' => 'good',
                    'services' => ['lifting', 'visual']
                ],
                [
                    'name' => 'MPI Equipment',
                    'type' => 'magnetic_particle',
                    'brand' => 'MagnaTest',
                    'condition' => 'excellent',
                    'services' => ['mpi']
                ]
            ],
            
            'personnel_assignments' => [
                [
                    'name' => 'John Inspector',
                    'role' => 'Lead Inspector',
                    'certification' => 'Level II',
                    'services' => ['lifting', 'visual']
                ]
            ],
            
            'consumable_assignments' => [
                [
                    'name' => 'Magnetic Particles',
                    'type' => 'mpi_consumable',
                    'quantity' => '2',
                    'unit' => 'kg',
                    'services' => ['mpi']
                ]
            ]
        ];
        
        $this->info('Simulated Request Data:');
        $this->newLine();
        
        // Test services processing
        if (isset($simulatedRequest['selected_services'])) {
            $this->info('🔧 SELECTED SERVICES:');
            foreach ($simulatedRequest['selected_services'] as $index => $serviceData) {
                $service = json_decode($serviceData, true);
                $this->line("  Service {$index}: {$service['type']} - {$service['name']}");
            }
            $this->newLine();
        }
        
        // Test equipment processing
        if (isset($simulatedRequest['equipment_assignments'])) {
            $this->info('🛠️  EQUIPMENT ASSIGNMENTS:');
            foreach ($simulatedRequest['equipment_assignments'] as $index => $equipment) {
                $this->line("  Equipment {$index}: {$equipment['name']} ({$equipment['type']})");
                $this->line("    Brand: {$equipment['brand']}, Condition: {$equipment['condition']}");
                $this->line("    Services: " . implode(', ', $equipment['services']));
            }
            $this->newLine();
        }
        
        // Test personnel processing
        if (isset($simulatedRequest['personnel_assignments'])) {
            $this->info('👥 PERSONNEL ASSIGNMENTS:');
            foreach ($simulatedRequest['personnel_assignments'] as $index => $person) {
                $this->line("  Person {$index}: {$person['name']} - {$person['role']}");
                $this->line("    Certification: {$person['certification']}");
                $this->line("    Services: " . implode(', ', $person['services']));
            }
            $this->newLine();
        }
        
        // Test consumables processing
        if (isset($simulatedRequest['consumable_assignments'])) {
            $this->info('📦 CONSUMABLE ASSIGNMENTS:');
            foreach ($simulatedRequest['consumable_assignments'] as $index => $consumable) {
                $this->line("  Consumable {$index}: {$consumable['name']} ({$consumable['type']})");
                $this->line("    Quantity: {$consumable['quantity']} {$consumable['unit']}");
                $this->line("    Services: " . implode(', ', $consumable['services']));
            }
            $this->newLine();
        }
        
        $this->info('✅ Form data processing test completed');
        $this->info('💡 The controller should now handle this complex data format');
        
        return 0;
    }
}
