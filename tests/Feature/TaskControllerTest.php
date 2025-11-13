<?php

use App\Models\Task;
use App\Models\User;
use App\Models\TaskType;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\patch;
use function Pest\Laravel\delete;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->taskType = TaskType::factory()->for($this->user)->create(['ativo' => true]);
    actingAs($this->user);
});

// Testa se a lista de tarefas é exibida corretamente
it('can show list of tasks', function () {
    Task::factory()
        ->for($this->user)
        ->for($this->taskType, 'taskType')
        ->count(5)
        ->create();

    $response = get(route('tasks.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Index')
        ->has('tasks.data', 5)
    );
});

// Testa se a página de criação de tarefa é exibida
it('can show create task page', function () {
    $response = get(route('tasks.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Create')
    );
});

// Testa se uma nova tarefa pode ser criada
it('can create a new task', function () {
    $taskData = [
        'title' => 'Nova tarefa',
        'description' => 'Descrição da tarefa',
        'status' => 'pendente',
        'priority' => 'media',
        'due_date' => now()->addDays(7)->format('Y-m-d'),
        'task_type_id' => $this->taskType->id, 
    ];

    $response = post(route('tasks.store'), $taskData);

    $response->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', [
        'title' => $taskData['title'],
        'user_id' => $this->user->id,
        'task_type_id' => $this->taskType->id,
    ]);
});

// Testa se uma tarefa específica é exibida
it('can show a specific task', function () {
    $task = Task::factory()
        ->for($this->user)
        ->for($this->taskType, 'taskType')
        ->create();

    $response = get(route('tasks.show', $task));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Show')
        ->has('task')
    );
});

// Testa se a página de edição de tarefa é exibida
it('can show edit task page', function () {
    $task = Task::factory()
        ->for($this->user)
        ->for($this->taskType, 'taskType')
        ->create();

    $response = get(route('tasks.edit', $task));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Edit')
        ->has('task')
    );
});

// Testa se uma tarefa pode ser atualizada
it('can update a task', function () {
    $task = Task::factory()
        ->for($this->user)
        ->for($this->taskType, 'taskType')
        ->create();

    $updatedData = [
        'title' => 'Tarefa atualizada',
        'status' => 'em_progresso',
        'task_type_id' => $this->taskType->id,
    ];

    $response = put(route('tasks.update', $task), $updatedData);

    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => $updatedData['title'],
        'status' => $updatedData['status'],
        'task_type_id' => $this->taskType->id,
    ]);
});

// Testa se uma tarefa pode ser removida
it('can delete a task', function () {
    $task = Task::factory()->for($this->user)->create();

    $response = delete(route('tasks.destroy', $task));

    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

// Testa filtros de busca
it('can filter tasks by name, status, priority and due date', function () {
    $task = Task::factory()->for($this->user)->for($this->taskType, 'taskType')->create([
        'title' => 'Tarefa de teste',
        'status' => 'pendente',
        'priority' => 'alta',
        'due_date' => now()->format('Y-m-d'),
        'task_type_id' => $this->taskType->id,
    ]);

    $response = get(route('tasks.index', [
        'name' => 'teste',
        'status' => 'pendente',
        'priority' => 'alta',
        'due_date' => now()->format('Y-m-d'),
        'task_type_id' => $this->taskType->id,
    ]));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Index')
        ->has('tasks.data', 1)
    );
});

/**
 * Testa se a lista de tipos é exibida
 */
it('can show list of task types', function () {
    TaskType::factory()->for($this->user)->count(3)->create();

    $response = get(route('task-types.index'));
    $response->assertOk();
    $response->assertInertia(fn($page) => $page
        ->component('TaskTypes/Index')
        ->has('taskTypes', 4)
    );
});

/**
 * Testa criar novo tipo
 */
it('can create a new task type', function () {
    $data = [
        'name' => 'Urgente',
        'description' => 'Para tarefas urgentes',
    ];

    $response = post(route('task-types.store'), $data);

    $response->assertRedirect(route('task-types.index'));
    $this->assertDatabaseHas('task_types', [
        'name' => $data['name'],
        'user_id' => $this->user->id,
    ]);
});

/**
 * Testa editar tipo de tarefa
 */
it('can edit task type', function () {
    $taskType = TaskType::factory()->for($this->user)->create([
        'name' => 'Antigo',
        'description' => 'Descricao antiga',
    ]);

    $update = [
        'name' => 'Novo nome',
        'description' => 'Descricao nova',
    ];

    $response = put(route('task-types.update', $taskType->id), $update);

    $response->assertRedirect(route('task-types.index'));
    $this->assertDatabaseHas('task_types', [
        'id' => $taskType->id,
        'name' => $update['name'],
        'description' => $update['description'],
    ]);
});

/**
 * Testa ativar/desativar tipo de tarefa
 */
it('can toggle ativo status for task type', function () {
    $taskType = TaskType::factory()->for($this->user)->create([
        'ativo' => true,
    ]);

    // Toggle para false (0)
    $response = patch(route('task-types.toggle-ativo', $taskType->id));
    $response->assertOk();
    $taskType = $taskType->fresh();
    expect($taskType->ativo)->toBeFalsy();

    // Toggle para true (1)
    $response = patch(route('task-types.toggle-ativo', $taskType->id));
    $response->assertOk();
    $taskType = $taskType->fresh();
    expect($taskType->ativo)->toBeTruthy();

});

/**
 * Testa remover tipo de tarefa
 */
it('can delete a task type', function () {
    $taskType = TaskType::factory()->for($this->user)->create();

    $response = delete(route('task-types.destroy', $taskType->id));

    $response->assertRedirect(route('task-types.index'));
    $this->assertDatabaseMissing('task_types', ['id' => $taskType->id]);
});
