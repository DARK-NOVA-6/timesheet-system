<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Get all users (with optional filters)
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('first_name')) {
            $query->where('first_name', $request->first_name);
        }
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->has('date_of_birth')) {
            $query->where('date_of_birth', $request->date_of_birth);
        }

        return response()->json($query->get());
    }

    // Get a single user by ID
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Store a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json($user, 201);
    }

    // Update a user by ID
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'date_of_birth' => 'sometimes|required|date',
            'gender' => 'sometimes|required|in:male,female',
            'email' => 'sometimes|required|string|email',
        ]);

        $user->update($validated);
        return response()->json($user);
    }

    // Delete a user by ID (and their related timesheets)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->timesheets()->delete();  // Delete related timesheets
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
