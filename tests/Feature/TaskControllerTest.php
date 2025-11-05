<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

// Testa se a lista de tarefas é exibida corretamente
it('can show list of tasks', function () {
    Task::factory()->for($this->user)->count(5)->create();

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
    ];

    $response = post(route('tasks.store'), $taskData);

    $response->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', [
        'title' => $taskData['title'],
        'user_id' => $this->user->id,
    ]);
});

// Testa se uma tarefa específica é exibida
it('can show a specific task', function () {
    $task = Task::factory()->for($this->user)->create();

    $response = get(route('tasks.show', $task));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Show')
        ->has('task')
    );
});

// Testa se a página de edição de tarefa é exibida
it('can show edit task page', function () {
    $task = Task::factory()->for($this->user)->create();

    $response = get(route('tasks.edit', $task));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Edit')
        ->has('task')
    );
});

// Testa se uma tarefa pode ser atualizada
it('can update a task', function () {
    $task = Task::factory()->for($this->user)->create();

    $updatedData = [
        'title' => 'Tarefa atualizada',
        'status' => 'em_progresso',
    ];

    $response = put(route('tasks.update', $task), $updatedData);

    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => $updatedData['title'],
        'status' => $updatedData['status'],
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
    Task::factory()->for($this->user)->create([
        'title' => 'Tarefa de teste',
        'status' => 'pendente',
        'priority' => 'alta',
        'due_date' => now()->format('Y-m-d'),
    ]);

    $response = get(route('tasks.index', [
        'name' => 'teste',
        'status' => 'pendente',
        'priority' => 'alta',
        'due_date' => now()->format('Y-m-d'),
    ]));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Tasks/Index')
        ->has('tasks.data', 1)
    );
});
