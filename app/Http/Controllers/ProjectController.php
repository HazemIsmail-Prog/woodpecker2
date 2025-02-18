<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{

    public function index(Request $request)
    {
        $projects = Project::latest()->with('schedule')->get();

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
                'contract_date' => 'nullable|date',
                'phone' => 'nullable|string|max:20',
                'location' => 'nullable|string|max:255',
                'quotation_number' => 'nullable|string|max:255',
                'delivery_date' => 'nullable|date',
                'installation_date' => 'nullable|date',
                'type_of_work' => 'nullable|string|max:255',
                'duration' => 'nullable|integer',
                'value' => 'nullable|numeric|min:0',
                'status' => 'nullable|in:pending,in_progress,completed,cancelled'
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
            'contract_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'quotation_number' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'type_of_work' => 'nullable|string|max:255',
            'duration' => 'nullable|integer',
            'value' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled'
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