<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Skill;
use App\Services\AiMatchingService;
use Illuminate\Http\Request;

class PublicJobsController extends Controller
{
    public function index(Request $request, AiMatchingService $aiMatching)
    {
        $jobs = JobPost::where('status', 'open')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('q') . '%');
            })
            ->when($request->filled('location'), function ($query) use ($request) {
                $query->where('location', $request->input('location'));
            })
            ->when($request->filled('skill'), function ($query) use ($request) {
                $query->whereHas('skills', function ($skillQuery) use ($request) {
                    $skillQuery->where('skills.name', $request->input('skill'));
                });
            })
            ->with(['company', 'skills'])
            ->latest()
            ->get();

        $appliedJobIds = [];
        $matchScores = [];

        $user = $request->user();

        if ($user && $user->role === 'applicant') {
            $appliedJobIds = $user->applications()->pluck('job_post_id')->all();
            $matchScores = $aiMatching->scoreJobsForUser($user->load('skills'), $jobs);
        }

        return view('jobs', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'matchScores' => $matchScores,
            'search' => $request->input('q', ''),
            'locationFilter' => $request->input('location', ''),
            'skillFilter' => $request->input('skill', ''),
            'skillOptions' => Skill::orderBy('name')->pluck('name'),
        ]);
    }
}
