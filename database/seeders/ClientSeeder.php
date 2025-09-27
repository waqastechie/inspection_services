<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing clients (use delete instead of truncate for FK safety)
        \DB::table('clients')->delete();

        // Create sample clients
        $clients = [
            [
                'client_name' => 'Saipem Trechville',
                'client_code' => 'ST',
                'company_type' => 'Oil & Gas',
                'industry' => 'Energy',
                'address' => '123 Industrial Avenue',
                'city' => 'Trechville',
                'state' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'postal_code' => '01000',
                'phone' => '+225-01-234-567',
                'email' => 'contact@saipem-trechville.com',
                'contact_person' => 'Jean-Claude Kouame',
                'contact_position' => 'Project Manager',
                'contact_phone' => '+225-01-234-568',
                'contact_email' => 'jc.kouame@saipem-trechville.com',
                'is_active' => true,
            ],
            [
                'client_name' => 'TotalEnergies Offshore',
                'client_code' => 'TEO',
                'company_type' => 'Oil & Gas',
                'industry' => 'Energy',
                'address' => '456 Offshore Boulevard',
                'city' => 'Abidjan',
                'state' => 'Lagunes',
                'country' => 'Côte d\'Ivoire',
                'postal_code' => '01001',
                'phone' => '+225-02-345-678',
                'email' => 'operations@totalenergies-ci.com',
                'contact_person' => 'Marie Adjoua',
                'contact_position' => 'Operations Director',
                'contact_phone' => '+225-02-345-679',
                'contact_email' => 'm.adjoua@totalenergies-ci.com',
                'is_active' => true,
            ],
            [
                'client_name' => 'ENI Exploration',
                'client_code' => 'ENI',
                'company_type' => 'Oil & Gas',
                'industry' => 'Energy',
                'address' => '789 Exploration Drive',
                'city' => 'Grand-Bassam',
                'state' => 'Sud-Comoé',
                'country' => 'Côte d\'Ivoire',
                'postal_code' => '01002',
                'phone' => '+225-03-456-789',
                'email' => 'info@eni-exploration.ci',
                'contact_person' => 'Roberto Bianchi',
                'contact_position' => 'Facility Manager',
                'contact_phone' => '+225-03-456-790',
                'contact_email' => 'r.bianchi@eni-exploration.ci',
                'is_active' => true,
            ],
            [
                'client_name' => 'Petroci Holding',
                'client_code' => 'PCH',
                'company_type' => 'Oil & Gas',
                'industry' => 'Energy',
                'address' => '321 National Oil Avenue',
                'city' => 'Abidjan',
                'state' => 'Lagunes',
                'country' => 'Côte d\'Ivoire',
                'postal_code' => '01003',
                'phone' => '+225-04-567-890',
                'email' => 'contact@petroci.ci',
                'contact_person' => 'Kofi Asante',
                'contact_position' => 'Technical Director',
                'contact_phone' => '+225-04-567-891',
                'contact_email' => 'k.asante@petroci.ci',
                'is_active' => true,
            ],
            [
                'client_name' => 'FOXTROT International',
                'client_code' => 'FI',
                'company_type' => 'Oil & Gas',
                'industry' => 'Energy',
                'address' => '555 International Plaza',
                'city' => 'Abidjan',
                'state' => 'Lagunes',
                'country' => 'Côte d\'Ivoire',
                'postal_code' => '01004',
                'phone' => '+225-05-678-901',
                'email' => 'info@foxtrot-international.com',
                'contact_person' => 'Ahmed Ben Salah',
                'contact_position' => 'Site Manager',
                'contact_phone' => '+225-05-678-902',
                'contact_email' => 'a.bensalah@foxtrot-international.com',
                'is_active' => true,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        if (isset($this->command)) {
            $this->command->info('Created ' . count($clients) . ' sample clients.');
        }
    }
}
