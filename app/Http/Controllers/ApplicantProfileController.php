<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load(['resume', 'skills']);

        return view('applicant.profile', [
            'user' => $user,
            'skillNames' => $user->skills->pluck('name')->implode(', '),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'education' => 'nullable|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'about' => 'nullable|string',
        ]);

        $request->user()->update($validated);

        return back()->with('success', __('site.profile.updated_success'));
    }

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $user = $request->user();

        if ($user->resume && Storage::disk('public')->exists($user->resume->file_path)) {
            Storage::disk('public')->delete($user->resume->file_path);
        }

        $path = $request->file('resume')->store('resumes', 'public');

        $user->resume()->updateOrCreate([], ['file_path' => $path]);

        return back()->with('success', __('site.profile.resume_uploaded_success'));
    }

    public function updateSkills(Request $request)
    {
        $validated = $request->validate([
            'skills' => 'nullable|string',
        ]);

        $skillIds = collect(explode(',', $validated['skills'] ?? ''))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->map(fn ($name) => Skill::firstOrCreate(['name' => $name])->id);

        $request->user()->skills()->sync($skillIds);

        return back()->with('success', __('site.profile.skills_updated_success'));
    }
}
