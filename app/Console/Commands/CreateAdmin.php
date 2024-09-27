<?php

namespace App\Console\Commands;

use App\Models\User;
use App\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin {email}';
    protected $description = 'Creates a new admin user';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        if(app()->isProduction()) {
            if(!$this->confirm('Are you sure you want to run this in production?')) {
                $this->line('Bye!');
                return;
            }
        }

        if(User::whereEmail($this->argument('email'))->exists()) {
            $this->fail('Email already exists.');
        }

        $password = Str::random(8);

        User::create([
            'email' => $this->argument('email'),
            'name' => 'Admin',
            'password' => $password,
            'role' => UserRole::ADMIN
        ]);

        $this->line("Password: {$password}");
    }
}
