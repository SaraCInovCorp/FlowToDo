<?php

namespace App\Policies;

use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a listagem de logs.
     */
    public function viewAny(User $user): bool
    {
        \Log::info('Policy chamada para usuário: ' . $user->id);
        return $user->is_admin == true || $user->is_admin == 1;
    }

    /**
     * (Opcional) Permite ver logs individuais.
     */
    public function view(User $user, Activity $activity): bool
    {
        return $user->is_admin == true || $user->is_admin == 1;
    }
}
