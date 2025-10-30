<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::where('is_admin', true)->delete();
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin1@flowtodo.com'],
            [
                'name' => 'Admin 1',
                'is_admin' => true,
                // demais campos padrão para criação
                'password' => Hash::make('password'),
                'bio' => 'Bio do admin 1',
                'birthday' => '1990-01-01',
                'email_verified_at' => now(),
                'profile_photo_path' => 'profile-photos/admin1.jpg', 
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin2@flowtodo.com'],
            [
                'name' => 'Admin 2',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'bio' => 'Bio do admin 2',
                'birthday' => '1990-01-01',
                'email_verified_at' => now(),
                'profile_photo_path' => 'profile-photos/admin2.jpg', 
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin3@flowtodo.com'],
            [
                'name' => 'Admin 3',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'bio' => 'Bio do admin 3',
                'birthday' => '1990-01-01',
                'email_verified_at' => now(),
                'profile_photo_path' => 'profile-photos/admin3.jpg', 
            ]
        );

        User::factory(5)
            ->has(Task::factory()->count(10))
            ->create([
                'is_admin' => false,
            ]);

    }
}
