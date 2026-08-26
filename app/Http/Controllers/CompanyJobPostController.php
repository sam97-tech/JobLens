<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use App\Models\Skill;
use App\Services\AiMatchingService;
use Illuminate\Http\Request;

class CompanyJobPostController extends Controller
{
    public function index(Request $request)
    {
        $jobPosts = $request->user()->company->jobPosts()
            ->withCount('applications')
            ->latest()
            ->get();

        return view('company.jobposts.index', [
            'jobPosts' => $jobPosts,
        ]);
    }

    public function create()
    {
        return view('company.jobposts.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateJobPost($request);

        $jobPost = $request->user()->company->jobPosts()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'location' => $validated['location'] ?? null,
            'salary' => $validated['salary'] ?? null,
            'status' => $validated['status'],
        ]);

        $this->syncSkills($jobPost, $validated['skills'] ?? '');

        return redirect()->route('company.jobposts.index')
            ->with('success', __('site.jobposts.created_success'));
    }

    public function edit(Request $request, JobPost $jobPost)
    {
        $this->authorizeOwnership($request, $jobPost);

        $jobPost->load('skills');

        return view('company.jobposts.edit', [
            'jobPost' => $jobPost,
            'skillNames' => $jobPost->skills->pluck('name')->implode(', '),
        ]);
    }

    public function update(Request $request, JobPost $jobPost)
    {
        $this->authorizeOwnership($request, $jobPost);

        $validated = $this->validateJobPost($request);

        $jobPost->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'location' => $validated['location'] ?? null,
            'salary' => $validated['salary'] ?? null,
            'status' => $validated['status'],
        ]);

        $this->syncSkills($jobPost, $validated['skills'] ?? '');

        return redirect()->route('company.jobposts.index')
            ->with('success', __('site.jobposts.updated_success'));
    }

    public function destroy(Request $request, JobPost $jobPost)
    {
        $this->authorizeOwnership($request, $jobPost);

        $jobPost->delete();

        return back()->with('success', __('site.jobposts.deleted_success'));
    }

    public function applicants(Request $request, JobPost $jobPost, AiMatchingService $aiMatching)
    {
        $this->authorizeOwnership($request, $jobPost);

        $applications = $jobPost->applications()
            ->with(['user.resume', 'user.skills'])
            ->latest()
            ->get();

        $jobPost->load('skills');

        $scores = $aiMatching->scoreCandidatesForJob(
            $jobPost,
            $applications->pluck('user')->filter()
        );

        foreach ($applications as $application) {
            $application->match_score = $application->user
                ? ($scores[$application->user->id] ?? null)
                : null;
        }

        return view('company.jobposts.applicants', [
            'jobPost' => $jobPost,
            'applications' => $applications->sortByDesc('match_score')->values(),
        ]);
    }

    public function updateApplicationStatus(Request $request, Application $application)
    {
        abort_unless(
            $application->jobPost->company_id === $request->user()->company->id,
            403
        );

        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $application->update(['status' => $validated['status']]);

        return back()->with('success', __('site.jobposts.status_updated_success'));
    }

    private function validateJobPost(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|in:' . implode(',', config('locations')),
            'salary' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed',
            'skills' => 'nullable|string',
        ]);
    }

    private function syncSkills(JobPost $jobPost, string $skillsInput): void
    {
        $skillIds = collect(explode(',', $skillsInput))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->map(fn ($name) => Skill::firstOrCreate(['name' => $name])->id);

        $jobPost->skills()->sync($skillIds);
    }

    private function authorizeOwnership(Request $request, JobPost $jobPost): void
    {
        abort_unless($jobPost->company_id === $request->user()->company->id, 403);
    }
}
