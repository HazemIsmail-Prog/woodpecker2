<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DailyScheduleController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/progress', [DashboardController::class, 'getMonthlyProgress'])->name('dashboard.progress');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/projects/import', [ProjectController::class, 'import'])->name('projects.import');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/schedules/export/pdf', [ScheduleController::class, 'exportPdf'])->name('schedules.export.pdf');
    Route::get('/schedules/{month?}/{year?}', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules/{project}', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    Route::get('/daily-schedules', [DailyScheduleController::class, 'index'])->name('daily-schedules.index');
    Route::post('/daily-schedules', [DailyScheduleController::class, 'store'])->name('daily-schedules.store');
    Route::delete('/daily-schedules', [DailyScheduleController::class, 'destroy'])->name('daily-schedules.destroy');

    Route::get('/daily-schedules/latest', [DailyScheduleController::class, 'latest'])->name('daily-schedules.latest');

    Route::get('/daily-schedules/pdf', [DailyScheduleController::class, 'downloadPdf'])
        ->name('daily-schedules.pdf');

    Route::resource('employees', EmployeeController::class);

    Route::get('/projects/template', [ProjectController::class, 'downloadTemplate'])->name('projects.template');
});

require __DIR__.'/auth.php';
