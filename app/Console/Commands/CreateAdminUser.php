<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Delete ALL users with admin email (in case there are duplicates)
        $deletedCount = User::where('email', 'admin@doraedu.com')->delete();
        $this->info("Deleted {$deletedCount} existing admin users");

        // Also delete any users with plain text passwords
        $plainTextUsers = User::where('password', '12345678')->delete();
        $this->info("Deleted {$plainTextUsers} users with plain text passwords");

        // Create admin user with proper password hashing
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@doraedu.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@doraedu.com');
        $this->info('Password: password');
        $this->info('Role: admin');
        $this->info('Password Hash: ' . $admin->password);
        
        // Verify the user was created correctly
        $createdUser = User::where('email', 'admin@doraedu.com')->first();
        if ($createdUser) {
            $this->info('Verification: User found in database');
            $this->info('Password starts with: ' . substr($createdUser->password, 0, 10) . '...');
        } else {
            $this->error('ERROR: User not found after creation!');
        }
    }
}