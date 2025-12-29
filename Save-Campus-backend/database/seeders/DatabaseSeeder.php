<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Staff Users
        User::create([
            'name' => 'John Smith',
            'email' => 'staff1@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'staff2@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Michael Brown',
            'email' => 'staff3@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        // Create Student Users
        User::create([
            'name' => 'Emma Wilson',
            'email' => 'student1@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'James Davis',
            'email' => 'student2@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Olivia Martinez',
            'email' => 'student3@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'William Garcia',
            'email' => 'student4@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Sophia Rodriguez',
            'email' => 'student5@savecampus.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
    }
}
