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
            $projects = Project::query()
                ->whereNotNull('duration')
                ->whereDoesntHave('schedule')
                ->get();

            $scheduledProjects = Schedule::query()
                ->with('project')
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->orWhereMonth('end_date', $month)
                ->whereYear('end_date', $year)
                ->get();

            return response()->json([
                'projects' => $projects,
                'scheduledProjects' => $scheduledProjects,
            ]);
        }

        return view('schedules.index');
    }

    public function update(Request $request, Schedule $schedule)
    {

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'row' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1'
        ]);

        $schedule->update([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'row' => $validated['row'],
        ]);

        $schedule->project->update([
            'duration' => $validated['duration']
        ]);

        return response()->json($schedule->load('project'));
    }
    

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'row' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1'
        ]);

        $project->schedule()->updateOrCreate([
            'project_id' => $project->id
        ], [
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'row' => $validated['row'],
        ]); 

        $project->update([
            'duration' => $validated['duration']
        ]);

        return response()->json($project);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json(['message' => 'Schedule removed successfully']);
    }
} 