<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        $upcomingTasks = Cache::remember("dashboard_upcomingTasks_{$userId}", 300, function () use ($user) {
            return $user->tasks()
                ->where('status', 'pendente')
                ->where(function ($query) {
                    $query->whereDate('due_date', '>=', now()->toDateString())
                        ->orWhereNull('due_date');
                })
                ->orderBy('due_date')
                //->limit(3)
                ->get(['id', 'title', 'description', 'status', 'priority', 'due_date', 'task_type_id']);
        });


        $inProgressTasks = $user->tasks()
            ->where('status', 'em_progresso')
            ->get(['id', 'title', 'description', 'status', 'priority', 'due_date', 'task_type_id']);

        return Inertia::render('Dashboard', [
            'upcomingTasks' => $upcomingTasks,
            'inProgressTasks' => $inProgressTasks,
        ]);
    }
}
