<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\TaskType;

class TaskTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $taskTypes = TaskType::where('user_id', $userId)->orderBy('name')->get();
        return Inertia::render('TaskTypes/Index', ['taskTypes' => $taskTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:task_types,name',
            'description' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        TaskType::create($data);

        return redirect()->route('task-types.index')->with('success', 'Tipo criado com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskType $task_type)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:task_types,name,' . $task_type->id,
            'description' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        $task_type->update($data);

        return redirect()->route('task-types.index')->with('success', 'Tipo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task_type->delete();

        return redirect()->route('task-types.index')->with('success', 'Tipo removido com sucesso.');
    }

    public function toggleAtivo(TaskType $task_type)
    {
        $task_type->ativo = !$task_type->ativo;
        $task_type->save();

        return response()->json(['ativo' => $task_type->ativo]);
    }
}
