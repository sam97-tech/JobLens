<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::all();

        return response()->json($questions);  //
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
            'question' => 'required|string',
            'difficulty' => 'required|in:Easy,Medium,Hard',
        ]);

        $question = Question::create($validated);

        return response()->json([
            'message' => 'Question created successfully',
            'question' => $question
        ], 201);  //
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return response()->json($question);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'job_post_id' => 'sometimes|required|exists:job_posts,id',
            'question' => 'sometimes|required|string',
            'difficulty' => 'sometimes|required|in:Easy,Medium,Hard',
        ]);

        $question->update($validated);

        return response()->json([
            'message' => 'Question updated successfully',
            'question' => $question
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
      public function destroy(Question $question)
    {
        $question->delete();

        return response()->json([
            'message' => 'Question deleted successfully'
        ]);
    }
}
