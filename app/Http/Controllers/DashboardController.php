<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $upcomingTasks = $user->tasks()
            ->where('status', 'pendente')
            ->orderBy('due_date')
            ->limit(3)
            ->get(['id', 'title', 'due_date', 'status']);

        $inProgressTasks = $user->tasks()
            ->where('status', 'em_progresso')
            ->get(['id', 'title', 'status']);

        return Inertia::render('Dashboard', [
            'upcomingTasks' => $upcomingTasks,
            'inProgressTasks' => $inProgressTasks,
        ]);
    }
}
