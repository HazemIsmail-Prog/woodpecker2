<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request, $month = null, $year = null)
    {
        if ($request->wantsJson()) {
            $unPlacedSchedules = Schedule::query()
                ->with('project')
                ->whereNull('start_date')
                ->orWhereNull('end_date')
                ->join('projects', 'schedules.project_id', '=', 'projects.id')
                ->select('schedules.*')
                ->orderBy('projects.installation_date', 'asc')
                ->get();

            $schedules = Schedule::query()
                ->with('project')
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->orWhereMonth('end_date', $month)
                ->whereYear('end_date', $year)
                ->get();

            return response()->json([
                'unPlacedSchedules' => $unPlacedSchedules,
                'schedules' => $schedules,
            ]);
        }

        return view('schedules.index');
    }

    public function update(Request $request, Schedule $schedule)
    {

        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'row' => 'nullable|integer|min:1',
            'duration' => 'required|integer|min:1',
            'color' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $schedule->update([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration' => $validated['duration'],
            'row' => $validated['row'],
            'color' => $validated['color'],
            'notes' => $validated['notes'],
        ]);

        return response()->json($schedule);
    }
    

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'duration' => 'required|integer|min:1',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $schedule = Schedule::create([
            'project_id' => $project->id,
            'duration' => $validated['duration'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        return response()->json($schedule);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json(['message' => 'Schedule removed successfully']);
    }
} 