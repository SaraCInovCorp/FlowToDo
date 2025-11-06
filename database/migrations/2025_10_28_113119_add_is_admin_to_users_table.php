<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   use HandlesAuthorization;

    /**
     * Determine se o usuário pode ver qualquer log.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin === true;
    }
};
