<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'name');
            $sortDirection = $request->input('sort_direction', 'asc');
            $type = $request->input('type', '');
            $search = $request->input('search', '');

            $employees = Employee::query()
                ->when($type, function ($query) use ($type) {
                    return $query->where('type', $type);
                })
                ->when($search, function ($query) use ($search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                })
                ->orderBy($sortBy, $sortDirection)
                ->paginate($perPage);

            return response()->json($employees);
        }
        return view('employees.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:engineer,supervisor,technician',
                'is_active' => 'boolean'
            ]);

            $employee = Employee::create($validated);

            return response()->json($employee);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:engineer,supervisor,technician',
            'is_active' => 'boolean'
        ]);

        $employee->update($validated);

        return response()->json($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully']);
    }
} 