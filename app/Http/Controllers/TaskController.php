<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Task;
use Inertia\Inertia;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->tasks();

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

        $tasks = $query->orderBy('due_date')
            ->paginate(6)
            ->withQueryString();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tasks/Create');
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
        ]);

        $validated['user_id'] = auth()->id();

        Task::create($validated);

        return to_route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendente,em_progresso,concluida,cancelada,arquivada',
            'priority' => 'required|in:alta,media,baixa',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return to_route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return to_route('tasks.index')->with('success', 'Tarefa removida.');
    }
}
