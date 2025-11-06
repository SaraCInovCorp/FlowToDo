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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tasks', TaskController::class);

    Route::middleware('can:viewAny,Spatie\Activitylog\Models\Activity')->prefix('admin')->group(function () {
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
    });

});


require __DIR__.'/settings.php';
