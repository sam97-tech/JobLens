<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resumes = Resume::with('user')->get();

        return response()->json($resumes); //
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'file_path' => 'required|string|max:255',
            'extracted_text' => 'nullable|string',
        ]);

        $resume = Resume::create($validated);

        return response()->json($resume, 201);  //
    }

    /**
     * Display the specified resource.
     */
    public function show(Resume $resume)
    {
        $resume->load('user');

        return response()->json($resume);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resume $resume)
    {
        $validated = $request->validate([
            'file_path' => 'sometimes|required|string|max:255',
            'extracted_text' => 'nullable|string',
        ]);

        $resume->update($validated);

        return response()->json($resume);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resume $resume)
    {
        $resume->delete();

        return response()->json([
            'message' => 'Resume deleted successfully'
        ]);
    }
}
