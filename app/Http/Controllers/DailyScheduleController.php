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
        $date = $request->query('date', now()->addDay()->format('Y-m-d'));

        // Get existing schedule for the date
        $dailySchedules = DailySchedule::query()
            ->whereDate('date', $date)
            ->get();

        // Get active employees and ongoing projects
        $employees = Employee::where('is_active', true)->get();
        $projects = Project::get();

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
            'assignments.*.employee_ids.*' => 'exists:employees,id'
        ]);

        DailySchedule::whereDate('date', $validated['date'])->delete();
        $data = [];
        foreach ($validated['assignments'] as $assignment) {
            $data[] = [
                'date' => $validated['date'],
                'project_id' => $assignment['project_id'],
                'employee_ids' => json_encode($assignment['employee_ids'])
            ];
        }
        // dd($data);
        DailySchedule::insert($data);

        return response()->json(['message' => 'Schedule saved successfully']);
    }

    public function downloadPdf(Request $request)
    {
        // format date like this Saterday, Febuary 22, 2025
        $date = $request->query('date', now()->format('Y-m-d'));
        $dateText = date('l, F d, Y', strtotime($date));

        $dailySchedules = DailySchedule::with(['project'])
            ->whereDate('date', $date)
            ->get()
            ->map(function($schedule) {
                $schedule->employee = Employee::whereIn('id', $schedule->employee_ids)->get();
                return $schedule;
            });

        $pdf = PDF::loadView('daily_schedules.pdf', [
            'dailySchedules' => $dailySchedules,
            'date' => $dateText
        ])->setPaper('a4', 'landscape');

        return $pdf->download("daily-schedule-{$date}.pdf");
    }

    public function destroy(Request $request)
    {
        $date = $request->query('date');
        
        DailySchedule::whereDate('date', $date)->delete();
        
        return response()->json(['message' => 'Schedule deleted successfully']);
    }
} 