<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\Request;

class ApplicantApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = $request->user()->applications()
            ->with('jobPost.company')
            ->latest()
            ->get();

        return view('applicant.applications', [
            'applications' => $applications,
        ]);
    }

    public function store(Request $request, JobPost $jobPost)
    {
        if ($jobPost->status !== 'open') {
            return back()->with('error', __('site.jobs.job_closed_error'));
        }

        $alreadyApplied = Application::where('user_id', $request->user()->id)
            ->where('job_post_id', $jobPost->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', __('site.jobs.already_applied_error'));
        }

        Application::create([
            'user_id' => $request->user()->id,
            'job_post_id' => $jobPost->id,
            'status' => 'pending',
        ]);

        return back()->with('success', __('site.jobs.application_success'));
    }
}
