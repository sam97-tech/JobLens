<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $users = User::all();

        return response()->json($users); //
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,company,applicant',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'education' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'about' => 'nullable|string',
        ]);

        
        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201); //
    }

    /**
     * Display the specified resource.
     */
      public function show(User $user)
    {
        return response()->json($user);
    }

    

    /**
     * Update the specified resource in storage.
     */
      public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required|string|max:20',
            'password' => 'nullable|string|min:8',
            'role' => 'sometimes|required|in:admin,company,applicant',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'education' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'about' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
      public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
