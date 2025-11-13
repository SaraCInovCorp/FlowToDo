<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\TaskType;
use App\Helpers\ActivityLogger;

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
            'name' => [
            'required',
            'string',
            'unique:task_types,name,NULL,id,user_id,' . Auth::id()
        ],
            'description' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        $taskType = TaskType::create($data);

        ActivityLogger::log('created', $taskType, 'Criou tipo de tarefa', [
            'attributes' => $taskType->toArray(),
        ]);

        return redirect()->route('task-types.index')->with('success', 'Tipo criado com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskType $task_type)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'unique:task_types,name,' . $task_type->id . ',id,user_id,' . Auth::id(),
            ],

            'description' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        $old = $task_type->getOriginal();
        $task_type->update($data);
        $changes = $task_type->getChanges();

        ActivityLogger::log('updated', $task_type, 'Atualizou tipo de tarefa', [
            'old' => $old,
            'attributes' => $task_type->toArray(),
            'changes' => $changes,
        ]);

        return redirect()->route('task-types.index')->with('success', 'Tipo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task_type = TaskType::findOrFail($id);

        ActivityLogger::log('deleted', $task_type, 'Removeu tipo de tarefa', [
            'attributes' => $task_type->toArray(),
        ]);

        $task_type->delete();

        return redirect()->route('task-types.index')->with('success', 'Tipo removido com sucesso.');
    }

    public function toggleAtivo(TaskType $task_type)
    {
        $old = $task_type->ativo;
        $task_type->ativo = !$task_type->ativo;
        $task_type->save();

        ActivityLogger::log('updated', $task_type, 'Alterou status ativo do tipo', [
            'changes' => ['ativo' => ['old' => $old, 'new' => $task_type->ativo]],
        ]);

        return response()->json(['ativo' => $task_type->ativo]);
    }
}
