<?php

namespace App\Services;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiMatchingService
{
    /**
     * Score a set of real job posts against an applicant's real profile.
     *
     * @param  Collection<int, JobPost>  $jobPosts
     * @return array<int, float> job_post_id => score (0-100)
     */
    public function scoreJobsForUser(User $user, Collection $jobPosts): array
    {
        if ($jobPosts->isEmpty()) {
            return [];
        }

        $payload = [
            'candidate' => [
                'skills' => $user->skills->pluck('name')->implode(', '),
                'education' => (string) $user->education,
                'experience_years' => (string) $user->experience_years,
                'about' => (string) $user->about,
            ],
            'jobs' => $jobPosts->map(fn (JobPost $job) => [
                'id' => $job->id,
                'title' => $job->title,
                'description' => (string) $job->description,
                'requirements' => (string) $job->requirements,
                'skills' => $job->skills->pluck('name')->all(),
            ])->values()->all(),
        ];

        try {
            $response = Http::timeout(10)->post(
                rtrim(config('services.ai_matching.url'), '/') . '/match',
                $payload
            );

            if (! $response->successful()) {
                Log::warning('AI matching service returned an error', [
                    'status' => $response->status(),
                ]);

                return [];
            }

            return collect($response->json('results', []))
                ->pluck('score', 'job_id')
                ->all();
        } catch (\Throwable $e) {
            Log::warning('AI matching service unreachable: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Score a set of real applicants (users) against a single real job post.
     *
     * @param  Collection<int, User>  $users
     * @return array<int, float> user_id => score (0-100)
     */
    public function scoreCandidatesForJob(JobPost $jobPost, Collection $users): array
    {
        $users = $users->values();

        if ($users->isEmpty()) {
            return [];
        }

        $payload = [
            'job' => [
                'id' => $jobPost->id,
                'title' => $jobPost->title,
                'description' => (string) $jobPost->description,
                'requirements' => (string) $jobPost->requirements,
                'skills' => $jobPost->skills->pluck('name')->all(),
            ],
            'candidates' => $users->map(fn (User $user) => [
                'id' => $user->id,
                'skills' => $user->skills->pluck('name')->implode(', '),
                'education' => (string) $user->education,
                'experience_years' => (string) $user->experience_years,
                'about' => (string) $user->about,
            ])->values()->all(),
        ];

        try {
            $response = Http::timeout(10)->post(
                rtrim(config('services.ai_matching.url'), '/') . '/match-candidates',
                $payload
            );

            if (! $response->successful()) {
                Log::warning('AI matching service returned an error', [
                    'status' => $response->status(),
                ]);

                return [];
            }

            return collect($response->json('results', []))
                ->pluck('score', 'candidate_id')
                ->all();
        } catch (\Throwable $e) {
            Log::warning('AI matching service unreachable: ' . $e->getMessage());

            return [];
        }
    }
}
