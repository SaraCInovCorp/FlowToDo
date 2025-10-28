<?php

use App\Models\User;
use App\Models\Task;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('um usuário pode ter várias tasks', function () {
    $user = User::factory()->create();
    $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->tasks)->toHaveCount(3)
        ->and($user->tasks->first())->toBeInstanceOf(Task::class)
        ->and($user->tasks->contains($tasks->first()))->toBeTrue();
});

it('um usuário pode ter várias activities', function () {
    $user = User::factory()->create();

    Activity::create([
        'log_name' => 'default',
        'description' => 'Teste',
        'subject_type' => User::class,
        'subject_id' => $user->id,
        'causer_type' => User::class,
        'causer_id' => $user->id,
        'properties' => [],
    ]);

    expect($user->activities->first())->toBeInstanceOf(Activity::class)
        ->and($user->activities->count())->toBeGreaterThanOrEqual(1);
});

