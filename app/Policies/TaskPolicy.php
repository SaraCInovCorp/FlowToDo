<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Admin pode tudo.
     * Usuário só pode acessar suas próprias tarefas.
     */
    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Pode ver qualquer tarefa própria.
     */
    public function view(User $user, Task $task)
    {
        return $task->user_id === $user->id;
    }

    /**
     * Pode criar qualquer tarefa (associada ao próprio usuário).
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Pode atualizar somente tarefa própria.
     */
    public function update(User $user, Task $task)
    {
        return $task->user_id === $user->id;
    }

    /**
     * Pode deletar somente tarefa própria.
     */
    public function delete(User $user, Task $task)
    {
        return $task->user_id === $user->id;
    }
}
