<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $jobPosts = JobPost::with('company', 'skills')->get();

        return response()->json($jobPosts); //
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:255',
            'status' => 'nullable|in:open,closed',
        ]);

        $jobPost = JobPost::create($validated);

        return response()->json($jobPost, 201); //
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPost $jobPost)
    {
        $jobPost->load('company', 'skills', 'applications');

        return response()->json($jobPost);
    }


   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobPost $jobPost)
    {
        $validated = $request->validate([
            'company_id' => 'sometimes|required|exists:companies,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:255',
            'status' => 'nullable|in:open,closed',
        ]);

        $jobPost->update($validated);

        return response()->json($jobPost);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPost $jobPost)
    {
        $jobPost->delete();

        return response()->json([
            'message' => 'Job post deleted successfully'
        ]);
    }
}
