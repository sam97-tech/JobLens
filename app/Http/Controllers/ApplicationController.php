<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;


class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $applications = Application::with(['user', 'jobPost'])
            ->latest()
            ->get();

        return response()->json($applications);  //
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_post_id' => 'required|exists:job_posts,id',
            'cv_path' => 'nullable|string',
            'status' => 'nullable|in:pending,accepted,rejected',
        ]);

        $application = Application::create($validated);

        return response()->json($application, 201); //
    }

    /**
     * Display the specified resource.
     */
     public function show(Application $application)
    {
        return response()->json(
            $application->load(['user', 'jobPost', 'answers', 'interview'])
        );
    }

    

    /**
     * Update the specified resource in storage.
     */
      public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'job_post_id' => 'sometimes|exists:job_posts,id',
            'cv_path' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:pending,accepted,rejected',
        ]);

        $application->update($validated);

        return response()->json($application);
    }

    /**
     * Remove the specified resource from storage.
     */
       public function destroy(Application $application)
    {
        $application->delete();

        return response()->json([
            'message' => 'Application deleted successfully'
        ]);
    }
}
