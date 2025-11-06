<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return $user->is_admin == true || $user->is_admin == 1;
    }

    public function view(User $user, ActivityLog $log): bool
    {
        return $user->is_admin == true || $user->is_admin == 1;
    }

}
