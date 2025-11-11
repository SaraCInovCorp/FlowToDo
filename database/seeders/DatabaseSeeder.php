<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskType;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

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

        User::factory(5)->create(['is_admin' => false])->each(function ($user) {

            // Cria tipos de tarefas apenas para esse usuário
            $taskTypes = TaskType::factory(3)->create(['user_id' => $user->id]);

            $taskTypesIds = $taskTypes->pluck('id')->toArray();

            // Cria tarefas associadas ao usuário e aos seus tipos de tarefa
            Task::factory(10)->make()->each(function ($task) use ($user, $taskTypesIds) {
                $task->user_id = $user->id;
                if (!empty($taskTypesIds)) {
                    $task->task_type_id = $taskTypesIds[array_rand($taskTypesIds)];
                } else {
                    $task->task_type_id = null;
                }
                $task->save();
            });
        });

        // Cria logs de atividade para cada usuário referente a uma tarefa aleatória sua
        $users = User::all();
        foreach ($users as $user) {
            $task = $user->tasks()->inRandomOrder()->first();
            if ($task) {
                ActivityLog::factory()->create([
                    'causer_id' => $user->id,
                    'subject_type' => Task::class,
                    'subject_id' => $task->id,
                    'event' => 'created',
                ]);
            }
        }
    }

}
