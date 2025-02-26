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
        


        // Get projects with schedules in current month
        $currentMonth = now()->format('Y-m');
        // $projectsWithProgress = $this->getProjectsWithProgress($currentMonth);
        
        return view('dashboard', [
            'totalProjects' => Project::count(),
            'activeProjects' => Project::whereIn('status', ['pending', 'in_progress'])->count(),
            'totalEmployees' => Employee::where('is_active', true)->count(),
            'todaySchedules' => DailySchedule::whereDate('date', $today)->distinct('project_id')->count(),
            
            'recentProjects' => Project::latest()->take(5)->get(),
            
            // 'projectsWithProgress' => $projectsWithProgress,
            'currentMonth' => $currentMonth,
            
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

    // public function getMonthlyProgress(Request $request)
    // {
    //     $month = $request->query('month', now()->format('Y-m'));
    //     $projects = $this->getProjectsWithProgress($month);
        
    //     return response()->json([
    //         'projects' => $projects
    //     ]);
    // }

    // private function getProjectsWithProgress($month)
    // {
    //     \Log::info('Fetching progress for month: ' . $month);
        
    //     // Load projects with ALL their schedules, not just the ones in the selected month
    //     $projects = Project::with(['schedules'])
    //         ->whereHas('schedules', function($query) use ($month) {
    //             $query->where(function($q) use ($month) {
    //                 $q->whereRaw("strftime('%Y-%m', start_date) = ?", [$month])
    //                   ->orWhereRaw("strftime('%Y-%m', end_date) = ?", [$month])
    //                   ->orWhere(function($q) use ($month) {
    //                       $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d');
    //                       $monthEnd = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('Y-m-d');
    //                       $q->where('start_date', '<=', $monthEnd)
    //                         ->where('end_date', '>=', $monthStart);
    //                   });
    //             });
    //         })
    //         ->get();
        
    //     \Log::info('Found projects: ' . $projects->count());
        
    //     $today = strtotime(now()->startOfDay());
    //     $currentMonth = \Carbon\Carbon::createFromFormat('Y-m', $month);
    //     $previousMonth = $currentMonth->copy()->subMonth();
        
    //     $result = $projects->map(function($project) use ($month, $today, $previousMonth) {
    //         $currentMonthProgress = 0;
    //         $previousMonthProgress = 0;
            
    //         if ($project->value) {
    //             $currentMonthStart = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
    //             $currentMonthEnd = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();
    //             $previousMonthStart = $previousMonth->startOfMonth();
    //             $previousMonthEnd = $previousMonth->endOfMonth();
                
    //             // Calculate total duration of ALL schedules for the project
    //             $totalScheduleDays = 0;
    //             foreach ($project->schedules as $schedule) {
    //                 if ($schedule->start_date && $schedule->end_date) {
    //                     $scheduleStartDate = strtotime($schedule->start_date);
    //                     $scheduleEndDate = strtotime($schedule->end_date);
    //                     $scheduleDays = ceil(($scheduleEndDate - $scheduleStartDate) / (60 * 60 * 24)) + 1;
    //                     $totalScheduleDays += $scheduleDays;
    //                 }
    //             }
                
    //             \Log::info('Project total duration calculation', [
    //                 'project' => $project->name,
    //                 'total_schedules' => $project->schedules->count(),
    //                 'total_days' => $totalScheduleDays,
    //                 'project_value' => $project->value,
    //                 'selected_month' => $month
    //             ]);
                
    //             if ($totalScheduleDays > 0) {
    //                 $valuePerDay = $project->value / $totalScheduleDays;
                    
    //                 // Filter schedules for the selected month
    //                 $monthSchedules = $project->schedules->filter(function($schedule) use ($month, $currentMonthStart, $currentMonthEnd) {
    //                     if (!$schedule->start_date || !$schedule->end_date) return false;
    //                     $scheduleStart = \Carbon\Carbon::parse($schedule->start_date);
    //                     $scheduleEnd = \Carbon\Carbon::parse($schedule->end_date);
    //                     return $scheduleStart->lte($currentMonthEnd) && $scheduleEnd->gte($currentMonthStart);
    //                 });
                    
    //                 foreach ($monthSchedules as $schedule) {
    //                     $scheduleStartDate = strtotime($schedule->start_date);
                        
    //                     // Skip if schedule hasn't started yet
    //                     if ($scheduleStartDate > $today) {
    //                         continue;
    //                     }
                        
    //                     // Calculate current month progress
    //                     $startDate = max($scheduleStartDate, strtotime($currentMonthStart));
    //                     $endDate = min(
    //                         strtotime($schedule->end_date),
    //                         $today,
    //                         strtotime($currentMonthEnd)
    //                     );
                        
    //                     if ($endDate >= $startDate) {
    //                         $diffDays = ceil(($endDate - $startDate) / (60 * 60 * 24)) + 1;
    //                         $currentMonthProgress += $valuePerDay * $diffDays;
                            
    //                         \Log::info('Current month schedule calculation', [
    //                             'project' => $project->name,
    //                             'schedule_id' => $schedule->id,
    //                             'start_date' => date('Y-m-d', $startDate),
    //                             'end_date' => date('Y-m-d', $endDate),
    //                             'diff_days' => $diffDays,
    //                             'value_per_day' => $valuePerDay,
    //                             'progress_value' => $valuePerDay * $diffDays
    //                         ]);
    //                     }
    //                 }
                    
    //                 // Filter schedules for the previous month
    //                 $previousMonthSchedules = $project->schedules->filter(function($schedule) use ($previousMonthStart, $previousMonthEnd) {
    //                     if (!$schedule->start_date || !$schedule->end_date) return false;
    //                     $scheduleStart = \Carbon\Carbon::parse($schedule->start_date);
    //                     $scheduleEnd = \Carbon\Carbon::parse($schedule->end_date);
    //                     return $scheduleStart->lte($previousMonthEnd) && $scheduleEnd->gte($previousMonthStart);
    //                 });
                    
    //                 // For previous month, use either the month end or today, whichever is earlier
    //                 $previousMonthEndDate = min(
    //                     strtotime($previousMonthEnd),
    //                     $today
    //                 );
                    
    //                 foreach ($previousMonthSchedules as $schedule) {
    //                     $scheduleStartDate = strtotime($schedule->start_date);
                        
    //                     // Skip if schedule hadn't started by the end of previous month
    //                     if ($scheduleStartDate > $previousMonthEndDate) {
    //                         continue;
    //                     }
                        
    //                     // Calculate previous month progress
    //                     $prevStartDate = max($scheduleStartDate, strtotime($previousMonthStart));
    //                     $prevEndDate = min(
    //                         strtotime($schedule->end_date),
    //                         $previousMonthEndDate
    //                     );
                        
    //                     if ($prevEndDate >= $prevStartDate) {
    //                         $prevDiffDays = ceil(($prevEndDate - $prevStartDate) / (60 * 60 * 24)) + 1;
    //                         $previousMonthProgress += $valuePerDay * $prevDiffDays;
                            
    //                         \Log::info('Previous month schedule calculation', [
    //                             'project' => $project->name,
    //                             'schedule_id' => $schedule->id,
    //                             'start_date' => date('Y-m-d', $prevStartDate),
    //                             'end_date' => date('Y-m-d', $prevEndDate),
    //                             'diff_days' => $prevDiffDays,
    //                             'value_per_day' => $valuePerDay,
    //                             'progress_value' => $valuePerDay * $prevDiffDays,
    //                             'previous_month' => $previousMonth->format('Y-m')
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
            
    //         return [
    //             'id' => $project->id,
    //             'name' => $project->name,
    //             'total_value' => $project->value,
    //             'progress_value' => round($currentMonthProgress, 2),
    //             'previous_progress_value' => round($previousMonthProgress, 2),
    //             'status' => $project->status,
    //             'has_previous_progress' => $previousMonthProgress > 0
    //         ];
    //     })
    //     ->sortByDesc('progress_value')
    //     ->values();
        
    //     \Log::info('Calculated progress values for projects', ['count' => count($result)]);
        
    //     return $result;
    // }
} 