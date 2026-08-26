@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-1">
        <div>
            <h2 class="fw-bold mb-1">{{ __('site.dashboard.company_welcome', ['name' => $company?->company_name ?? auth()->user()->name]) }}</h2>
            <p class="text-muted mb-0">{{ __('site.dashboard.company_subtitle') }}</p>
        </div>

        <a href="{{ route('company.jobposts.index') }}" class="btn btn-primary">
            <i class="fa-solid fa-briefcase me-1"></i>
            {{ __('site.dashboard.manage_jobs') }}
        </a>
    </div>

    <div class="row g-4 mt-3">

        <div class="col-md-6">
            <div class="card shadow border-0 p-4">
                <h5>{{ __('site.dashboard.job_posts_title') }}</h5>
                <p class="mb-0">{{ __('site.dashboard.job_posts_count', ['count' => $company?->jobPosts->count() ?? 0]) }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow border-0 p-4">
                <h5>{{ __('site.dashboard.company_profile_title') }}</h5>
                <p class="mb-0">{{ $company?->address ?? __('site.dashboard.no_address') }}</p>
            </div>
        </div>

    </div>

</div>
@endsection
