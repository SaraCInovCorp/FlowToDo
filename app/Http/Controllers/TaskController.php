<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Task;
use Inertia\Inertia;
use App\Helpers\ActivityLogger;
use App\Models\TaskType;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->tasks()->with('taskType', 'user');

        if ($request->filled('name')) {
            $query->where('title', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('status') && $request->status !== 'todas') {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority') && $request->priority !== 'todas') {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }
        if ($request->filled('task_type_id') && $request->task_type_id !== 'todas') {
            $query->where('task_type_id', $request->task_type_id);
        }

        $tasks = $query->orderBy('due_date')
            ->paginate(6)
            ->withQueryString();

        $taskTypes = TaskType::where('user_id', auth()->id())->where('ativo', true)->orderBy('name')->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'taskTypes' => $taskTypes,
        ]);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taskTypes = TaskType::where('user_id', auth()->id())->where('ativo', true)->orderBy('name')->get();
        return Inertia::render('Tasks/Create', ['taskTypes' => $taskTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendente,em_progresso,concluida,cancelada,arquivada',
            'priority' => 'required|in:alta,media,baixa',
            'due_date' => 'nullable|date',
            'task_type_id' => 'required|exists:task_types,id', 
        ]);


        $validated['user_id'] = auth()->id();

        $task = Task::create($validated);

        ActivityLogger::log('created', $task, 'Criou a tarefa', [
            'attributes' => $task->toArray()
        ]);

        return to_route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return Inertia::render('Tasks/Show', [
            'task' => $task->load('taskType'), // adicione o load aqui
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $taskTypes = TaskType::where('user_id', auth()->id())->where('ativo', true)->orderBy('name')->get();
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'taskTypes' => $taskTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
             'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:pendente,em_progresso,concluida,cancelada,arquivada',
            'priority' => 'sometimes|required|in:alta,media,baixa',
            'due_date' => 'nullable|date',
            'task_type_id' => 'required|exists:task_types,id',
        ]);

        $task->update($validated);

        $changes = $task->getChanges();

        ActivityLogger::log('updated', $task, 'Atualizou a tarefa', [
            'changes' => $changes,
        ]);
        \Cache::forget("dashboard_upcomingTasks_{$task->user_id}");


        return to_route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        ActivityLogger::log('deleted', $task, 'Removeu a tarefa', [
            'attributes' => $task->toArray()
        ]);

        return to_route('tasks.index')->with('success', 'Tarefa removida.');
    }
}
