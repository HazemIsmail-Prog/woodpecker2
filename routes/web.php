<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DailyScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $today = now()->format('Y-m-d');
    
    return view('dashboard', [
        'totalProjects' => App\Models\Project::count(),
        'activeProjects' => App\Models\Project::whereIn('status', ['pending', 'in_progress'])->count(),
        'totalEmployees' => App\Models\Employee::where('is_active', true)->count(),
        'todaySchedules' => App\Models\DailySchedule::whereDate('date', $today)->count(),
        
        'recentProjects' => App\Models\Project::latest()->take(5)->get(),
        
        'todayScheduleDetails' => App\Models\DailySchedule::with('project')
            ->whereDate('date', $today)
            ->get()
            ->groupBy('project_id')
            ->map(function($schedules) {
                $first = $schedules->first();
                return (object)[
                    'project' => $first->project,
                    'supervisors_count' => $schedules->where('employee.type', 'supervisor')->count(),
                    'technicians_count' => $schedules->where('employee.type', 'technician')->count(),
                    'engineers_count' => $schedules->where('employee.type', 'engineer')->count(),
                ];
            })
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::post('/projects/import', [ProjectController::class, 'import'])->name('projects.import');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    
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

    Route::get('/daily-schedules/pdf', [DailyScheduleController::class, 'downloadPdf'])
        ->name('daily-schedules.pdf');

    Route::resource('employees', EmployeeController::class);

    Route::get('/projects/template', [ProjectController::class, 'downloadTemplate'])->name('projects.template');
});

require __DIR__.'/auth.php';
