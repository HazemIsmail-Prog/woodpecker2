<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{

    public function schedules(Request $request,$month = null)
    {
        if ($request->wantsJson()) {
                $projects = Project::query()
                    ->whereNotNull('duration')
                    ->get();

                    $scheduledProjects = Project::query()
                    ->whereNotNull('start_date')
                    ->whereNotNull('end_date')
                    ->get() ;

            return response()->json([
                'projects' => $projects,
                'scheduledProjects' => $scheduledProjects,
            ]);
        }

        return view('projects.schedules');
    }

    public function setSchedule(Request $request, Project $project)
    {
        $project->update([
            'row' => $request->row,
            'color' => $request->color,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $request->duration,
        ]);
        return response()->json($project);
    }

    public function index(Request $request)
    {
        $projects = Project::latest()->get();

        // Return JSON for AJAX requests
        if ($request->wantsJson()) {
            return response()->json($projects);
        }

        // Return view for regular requests
        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contract_date' => 'required|date',
                'phone' => 'nullable|string|max:20',
                'location' => 'nullable|string|max:255',
                'quotation_number' => 'required|string|max:255',
                'delivery_date' => 'required|date',
                'installation_date' => 'nullable|date',
                'type_of_work' => 'nullable|string|max:255',
                'duration' => 'nullable|integer',
                'value' => 'nullable|numeric|min:0',
                'status' => 'required|in:pending,in_progress,completed,cancelled'
            ]);

            $project = Project::create($validated);

            return response()->json($project);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contract_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'quotation_number' => 'required|string|max:255',
            'delivery_date' => 'required|date',
            'installation_date' => 'nullable|date',
            'type_of_work' => 'nullable|string|max:255',
            'duration' => 'nullable|integer',
            'value' => 'nullable|numeric|min:0',
            'status' => 'required'
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }
} 