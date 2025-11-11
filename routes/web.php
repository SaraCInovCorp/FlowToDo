<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\TaskTypeController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas para tarefas CRUD
    Route::resource('tasks', TaskController::class);

    // Rotas para tipos de tarefas CRUD, exceto show (se preferir)
    Route::resource('task-types', TaskTypeController::class)->except(['show']);

    // Rota específica para ativar/desativar tipos de tarefa via AJAX
    Route::patch('task-types/{task_type}/toggle-ativo', [TaskTypeController::class, 'toggleAtivo'])
        ->name('task-types.toggle-ativo');

    Route::prefix('admin')
        ->middleware('can:viewAny,App\Models\ActivityLog')
        ->group(function () {
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])
                ->name('admin.activity-logs.index');
        });
});



require __DIR__.'/settings.php';
