<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    // Get all timesheets (with optional filters)
    public function index(Request $request)
    {
        $query = Timesheet::query();

        if ($request->has('task_name')) {
            $query->where('task_name', $request->task_name);
        }
        if ($request->has('date')) {
            $query->where('date', $request->date);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        return response()->json($query->get());
    }

    // Get a single timesheet by ID
    public function show($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        return response()->json($timesheet);
    }

    // Store a new timesheet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string',
            'date' => 'required|date',
            'hours' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $timesheet = Timesheet::create($validated);
        return response()->json($timesheet, 201);
    }

    // Update a timesheet by ID
    public function update(Request $request, $id)
    {
        $timesheet = Timesheet::findOrFail($id);

        $validated = $request->validate([
            'task_name' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
            'hours' => 'sometimes|required|integer',
        ]);

        $timesheet->update($validated);
        return response()->json($timesheet);
    }

    // Delete a timesheet by ID
    public function destroy($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();

        return response()->json(['message' => 'Timesheet deleted successfully']);
    }
}
