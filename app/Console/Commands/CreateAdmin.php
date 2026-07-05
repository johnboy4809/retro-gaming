<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Signature('admin:create {email? : The email address for the admin account} {password? : The password for the admin account}')]
#[Description('Create a master admin user account')]
class CreateAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@retro-gaming.com';
        $password = $this->argument('password') ?? 'password';

        // Check if user already exists
        $user = User::where('email', $email)->first();

        if ($user) {
            $this->info("Admin user '{$email}' already exists. Updating password...");
            $user->password = Hash::make($password);
            $user->save();
            $this->info("Password updated successfully!");
        } else {
            $user = User::create([
                'name' => 'Master Admin',
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $this->info("Admin user created successfully!");
        }

        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $user->name],
                ['Email', $user->email],
                ['Password', $password],
            ]
        );

        return 0;
    }
}
