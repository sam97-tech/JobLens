<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $answers = Answer::all();

        return response()->json($answers);  //
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
            'voice_analysis' => 'nullable|string',
            'face_analysis' => 'nullable|string',
            'score' => 'nullable|numeric',
        ]);

        $answer = Answer::create($validated);

        return response()->json([
            'message' => 'Answer created successfully',
            'answer' => $answer
        ], 201); //
    }

    /**
     * Display the specified resource.
     */
     public function show(Answer $answer)
    {
        return response()->json($answer);
    }

   

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Answer $answer)
    {
        $validated = $request->validate([
            'application_id' => 'sometimes|required|exists:applications,id',
            'question_id' => 'sometimes|required|exists:questions,id',
            'answer' => 'sometimes|required|string',
            'voice_analysis' => 'nullable|string',
            'face_analysis' => 'nullable|string',
            'score' => 'nullable|numeric',
        ]);

        $answer->update($validated);

        return response()->json([
            'message' => 'Answer updated successfully',
            'answer' => $answer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
       public function destroy(Answer $answer)
    {
        $answer->delete();

        return response()->json([
            'message' => 'Answer deleted successfully'
        ]);
    }
}
