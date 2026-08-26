@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ __('site.dashboard.applicant_welcome', ['name' => $user->name]) }}</h2>
            <p class="text-muted mb-0">{{ __('site.dashboard.applicant_subtitle') }}</p>
        </div>

        <a href="{{ route('applicant.profile.edit') }}" class="btn btn-primary">
            <i class="fa-solid fa-pen me-1"></i>
            {{ __('site.dashboard.edit_profile') }}
        </a>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow border-0 p-4">
                <h5>{{ __('site.dashboard.profile_title') }}</h5>
                <p class="mb-0">{{ __('site.dashboard.experience_label', ['years' => $user->experience_years]) }}</p>
                <p class="mb-0">{{ __('site.dashboard.education_label', ['education' => $user->education ?? __('site.dashboard.not_set')]) }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 p-4">
                <h5>{{ __('site.dashboard.resume_title') }}</h5>
                <p class="mb-0">{{ $user->resume ? __('site.dashboard.resume_uploaded') : __('site.dashboard.resume_not_uploaded') }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 p-4">
                <h5>{{ __('site.dashboard.applications_title') }}</h5>
                <p class="mb-2">{{ __('site.dashboard.applications_count', ['count' => $user->applications->count()]) }}</p>
                <a href="{{ route('applicant.applications.index') }}" class="small">
                    {{ __('site.jobs.my_applications_title') }}
                    <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow border-0 p-4">
                <h5 class="mb-3">{{ __('site.profile.skills_title') }}</h5>

                @if ($user->skills->isEmpty())
                    <p class="text-muted mb-0">{{ __('site.dashboard.no_skills') }}</p>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($user->skills as $skill)
                            <span class="badge-soft badge-soft-purple">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow border-0 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-wand-magic-sparkles text-gradient me-2"></i>
                        {{ __('site.dashboard.recommended_jobs_title') }}
                    </h5>
                </div>

                @if ($recommendedJobs->isEmpty())
                    <p class="text-muted mb-0">{{ __('site.dashboard.no_recommended_jobs') }}</p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($recommendedJobs as $job)
                            <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-3 px-0">
                                <div>
                                    <h6 class="mb-1">{{ $job->title }}</h6>
                                    <p class="text-muted mb-0 small">{{ $job->company->company_name ?? '' }}</p>
                                </div>

                                @if (!is_null($job->match_score))
                                    <span class="badge-soft badge-soft-green">
                                        {{ __('site.dashboard.match_score', ['score' => number_format($job->match_score, 0)]) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
