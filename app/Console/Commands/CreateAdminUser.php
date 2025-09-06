<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=admin@inspectionservices.com} {--password=admin123} {--name=Super Administrator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update admin user for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        $this->info('🔍 Checking for existing admin user...');
        
        // Check if admin user exists
        $adminUser = User::where('email', $email)->first();
        
        if ($adminUser) {
            $this->info("✅ Admin user found: {$adminUser->name} ({$adminUser->email})");
            
            if ($this->confirm('Update existing admin user password?', true)) {
                $adminUser->update([
                    'password' => Hash::make($password),
                    'is_active' => true,
                    'role' => 'super_admin',
                ]);
                
                $this->info('✅ Admin user password updated successfully!');
            }
        } else {
            $this->info('❌ Admin user not found. Creating new admin user...');
            
            $adminUser = User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'is_active' => true,
                'department' => 'Administration',
                'certification' => 'System Administrator',
            ]);
            
            $this->info('✅ Admin user created successfully!');
        }
        
        $this->newLine();
        $this->info('📋 LOGIN CREDENTIALS:');
        $this->line("Email: {$email}");
        $this->line("Password: {$password}");
        $this->line("Role: {$adminUser->role}");
        $this->newLine();
        $this->info('🎉 You can now login to the application!');
        
        return Command::SUCCESS;
    }
}
