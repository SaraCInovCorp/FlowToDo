<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\ActivityLogPolicy;
use App\Models\ActivityLog;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskType;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);

         Inertia::share([
            'taskTypes' => function () {
                if (Auth::check()) {
                    return TaskType::where('user_id', Auth::id())
                        ->where('ativo', true)
                        ->orderBy('name')
                        ->get();
                }
                return [];
            },
        ]);
    }
}
