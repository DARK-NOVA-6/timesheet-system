<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Get all projects (with optional filters)
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('name')) {
            $query->where('name', $request->name);
        }
        if ($request->has('department')) {
            $query->where('department', $request->department);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());
    }

    // Get a single project by ID
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    // Store a new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'department' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:ongoing,completed,on-hold',
        ]);

        $project = Project::create($validated);
        return response()->json($project, 201);
    }

    // Update a project by ID
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'department' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:ongoing,completed,on-hold',
        ]);

        $project->update($validated);
        return response()->json($project);
    }

    // Delete a project by ID (and its related timesheets)
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->timesheets()->delete();  // Delete related timesheets
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
