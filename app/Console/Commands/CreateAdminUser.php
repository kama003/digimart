<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=} {--name=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new admin user...');
        $this->newLine();

        // Get user input
        $email = $this->option('email') ?: $this->ask('Email address');
        $name = $this->option('name') ?: $this->ask('Full name');
        $password = $this->option('password') ?: $this->secret('Password (min 8 characters)');

        // Validate input
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  - ' . $error);
            }
            return 1;
        }

        // Create admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
            'balance' => 0,
            'is_banned' => false,
        ]);

        $this->newLine();
        $this->info('✓ Admin user created successfully!');
        $this->newLine();
        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $user->name],
                ['Email', $user->email],
                ['Role', $user->role->value],
            ]
        );

        return 0;
    }
}
