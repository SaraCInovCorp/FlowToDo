<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ActivityLogController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('tasks', TaskController::class);

    Route::prefix('admin')
        ->middleware('can:viewAny,App\Models\ActivityLog')
        ->group(function () {
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])
                ->name('admin.activity-logs.index');
        });
});



require __DIR__.'/settings.php';
