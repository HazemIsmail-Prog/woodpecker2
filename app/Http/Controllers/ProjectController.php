<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProjectsImport;
use App\Exports\ProjectsExport;
use App\Models\Employee;

class ProjectController extends Controller
{

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $perPage = $request->input('per_page', 10); 
            $sortBy = $request->input('sort_by', 'id');
            $sortDirection = $request->input('sort_direction', 'desc');
            $status = $request->input('status', '');
            $search = $request->input('search', '');
            $schedulesFilter = $request->input('schedules_filter', '');
            $projects = Project::query()
            ->with('schedules')
            ->with('dailySchedules')
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('quotation_number', 'like', '%' . $search . '%')
                ->orWhere('type_of_work', 'like', '%' . $search . '%')
                ->orWhere('location', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->when($schedulesFilter, function ($query) use ($schedulesFilter) {
                if ($schedulesFilter === 'true') {
                    return $query->whereHas('schedules');
                } elseif ($schedulesFilter === 'false') {
                    return $query->whereDoesntHave('schedules');
                }
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
            return response()->json($projects);
        }
        $employees = Employee::get(['id','type']);
        return view('projects.index', compact('employees'));
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
                'value' => 'nullable|numeric|min:0',
                'status' => 'nullable|in:pending,in_progress,completed,cancelled,site_on_hold,site_not_ready',
                'notes' => 'nullable|string|max:255',
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
            'value' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled,site_on_hold,site_not_ready',
            'notes' => 'nullable|string|max:255',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function import(Request $request)
    {

        // dd($request->file('file'));
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProjectsImport, $request->file('file'));
            
            return response()->json([
                'message' => 'Projects imported successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error importing projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplate()
    {
        return response()->download(
            storage_path('app/templates/projects_template.xlsx'),
            'projects_template.xlsx'
        );
    }
} 