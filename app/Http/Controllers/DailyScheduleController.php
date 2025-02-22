<?php

namespace App\Http\Controllers;

use App\Models\DailySchedule;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;
use PDF;

class DailyScheduleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');
        
        // Get existing schedule for the date
        $dailySchedules = DailySchedule::with(['project', 'employee'])
            ->whereDate('date', $date)
            ->get()
            ->groupBy('project_id');

        // Get active employees and ongoing projects
        $employees = Employee::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $projects = Project::orderBy('name')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($dailySchedules);
        }

        return view('daily_schedules.index', compact('employees', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'assignments' => 'required|array',
            'assignments.*.project_id' => 'required|exists:projects,id',
            'assignments.*.employee_ids' => 'nullable|array',
            'assignments.*.employee_ids.*' => 'nullable|exists:employees,id'
        ]);

        // Delete existing schedules for this date
        DailySchedule::where('date', $validated['date'])->delete();

        // Create new schedules
        foreach ($validated['assignments'] as $assignment) {
            if (empty($assignment['employee_ids'])) {
                // Create schedule without employees
                DailySchedule::create([
                    'date' => $validated['date'],
                    'project_id' => $assignment['project_id'],
                    'employee_id' => null
                ]);
            } else {
                // Create schedule for each employee
                foreach ($assignment['employee_ids'] as $employeeId) {
                    DailySchedule::create([
                        'date' => $validated['date'],
                        'project_id' => $assignment['project_id'],
                        'employee_id' => $employeeId
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Schedule saved successfully']);
    }

    public function destroy(Request $request)
    {

        $validated = $request->validate([   
            'date' => 'required|date'
        ]);

        DailySchedule::whereDate('date', $validated['date'])->delete();
        return response()->json(['message' => 'Schedule deleted successfully']);
    }

    public function downloadPdf(Request $request)
    {
        $date = $request->date ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');
        
        $dailySchedules = DailySchedule::with(['project', 'employee'])
            ->whereDate('date', $date)
            ->get()
            ->groupBy('project_id');

        $formattedDate = date('l, F j, Y', strtotime($date));
        
        $pdf = PDF::loadView('daily_schedules.pdf', [
            'dailySchedules' => $dailySchedules,
            'date' => $formattedDate
        ])->setPaper('a4', 'landscape');

        return $pdf->download("daily-schedule-{$date}.pdf");
    }
} 