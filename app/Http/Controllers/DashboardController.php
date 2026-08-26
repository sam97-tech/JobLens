<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Services\AiMatchingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Redirect the authenticated user to their role's dashboard
    public function index(Request $request)
    {
        return match ($request->user()->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'company' => redirect()->route('dashboard.company'),
            default => redirect()->route('dashboard.applicant'),
        };
    }

    public function admin()
    {
        return view('dashboard.admin');
    }

    public function company(Request $request)
    {
        return view('dashboard.company', [
            'company' => $request->user()->company,
        ]);
    }

    public function applicant(Request $request, AiMatchingService $aiMatching)
    {
        $user = $request->user()->load('skills');

        $openJobs = JobPost::where('status', 'open')
            ->with(['company', 'skills'])
            ->latest()
            ->get();

        $scores = $aiMatching->scoreJobsForUser($user, $openJobs);

        $recommendedJobs = $openJobs
            ->map(function (JobPost $job) use ($scores) {
                $job->match_score = $scores[$job->id] ?? null;

                return $job;
            })
            ->sortByDesc('match_score')
            ->take(5)
            ->values();

        return view('dashboard.applicant', [
            'user' => $user,
            'recommendedJobs' => $recommendedJobs,
        ]);
    }
}
