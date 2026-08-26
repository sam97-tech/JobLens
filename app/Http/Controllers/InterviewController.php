<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interviews = Interview::all();

        return response()->json($interviews); //
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'interview_date' => 'required|date',
            'score' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $interview = Interview::create($validated);

        return response()->json([
            'message' => 'Interview created successfully',
            'interview' => $interview
        ], 201);  //
    }

    /**
     * Display the specified resource.
     */
      public function show(Interview $interview)
    {
        return response()->json($interview);
    }

    
    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'application_id' => 'sometimes|required|exists:applications,id',
            'interview_date' => 'sometimes|required|date',
            'score' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $interview->update($validated);

        return response()->json([
            'message' => 'Interview updated successfully',
            'interview' => $interview
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        $interview->delete();

        return response()->json([
            'message' => 'Interview deleted successfully'
        ]);
    }
}
