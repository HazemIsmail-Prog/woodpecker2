<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Models\DailySchedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        
        return view('dashboard', [
            'totalProjects' => Project::count(),
            'activeProjects' => Project::whereIn('status', ['pending', 'in_progress'])->count(),
            'totalEmployees' => Employee::where('is_active', true)->count(),
            'todaySchedules' => DailySchedule::whereDate('date', $today)->distinct('project_id')->count(),
            
            'recentProjects' => Project::latest()->take(5)->get(),
            
            'todayScheduleDetails' => DailySchedule::with('project')
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
    }
} 