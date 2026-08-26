@extends('layouts.app')

@section('content')

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<span class="section-badge">{{ __('site.jobs.badge') }}</span>

<h1 class="fw-bold">{{ __('site.jobs.title') }}</h1>

<p class="text-muted">
{{ __('site.jobs.subtitle') }}
</p>

</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- Search -->

<div class="card shadow border-0 p-4 mb-5">

<form action="{{ route('jobs.index') }}" method="GET" class="row g-3">

<div class="col-md-4">

<input type="text"
name="q"
value="{{ $search }}"
class="form-control"
placeholder="{{ __('site.jobs.search_placeholder') }}">

</div>

<div class="col-md-3">

<select name="location" class="form-select">
    <option value="">{{ __('site.jobs.all_locations') }}</option>
    @foreach (config('locations') as $locationSlug)
        <option value="{{ $locationSlug }}" {{ $locationFilter === $locationSlug ? 'selected' : '' }}>
            {{ __('site.locations.' . $locationSlug) }}
        </option>
    @endforeach
</select>

</div>

<div class="col-md-3">

<select name="skill" class="form-select">
    <option value="">{{ __('site.jobs.all_skills') }}</option>
    @foreach ($skillOptions as $skillName)
        <option value="{{ $skillName }}" {{ $skillFilter === $skillName ? 'selected' : '' }}>
            {{ $skillName }}
        </option>
    @endforeach
</select>

</div>

<div class="col-md-2">

<button type="submit" class="btn btn-primary w-100">

<i class="fa fa-search"></i>

{{ __('site.jobs.search') }}

</button>

</div>

</form>

</div>

<div class="row">

<!-- Categories -->

<div class="col-lg-3">

<div class="card shadow-sm border-0">

<div class="card-header bg-white">

<h5>{{ __('site.jobs.categories_title') }}</h5>

</div>

<div class="list-group list-group-flush">

<a href="#" class="list-group-item list-group-item-action">
{{ __('site.jobs.category_technology') }}
</a>

<a href="#" class="list-group-item list-group-item-action">
{{ __('site.jobs.category_design') }}
</a>

<a href="#" class="list-group-item list-group-item-action">
{{ __('site.jobs.category_marketing') }}
</a>

<a href="#" class="list-group-item list-group-item-action">
{{ __('site.jobs.category_sales') }}
</a>

<a href="#" class="list-group-item list-group-item-action">
{{ __('site.jobs.category_finance') }}
</a>

<a href="#" class="list-group-item list-group-item-action">
{{ __('site.jobs.category_hr') }}
</a>

</div>

</div>

</div>

<!-- Jobs -->

<div class="col-lg-9">

@if ($jobs->isEmpty())

    <div class="card shadow-sm border-0 p-4 text-center">
        <p class="text-muted mb-0">{{ __('site.jobs.no_jobs') }}</p>
    </div>

@else

    @foreach ($jobs as $job)

    <div class="card shadow-sm border-0 mb-3">

    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

    <div>

    <h5 class="mb-1">{{ $job->title }}</h5>

    <p class="text-muted mb-2">
    {{ $job->company->company_name ?? '' }}
    </p>

    @if ($job->location)
        <span class="badge-soft badge-soft-blue me-2">
            <i class="fa-solid fa-location-dot me-1"></i>{{ __('site.locations.' . $job->location) }}
        </span>
    @endif

    @if ($job->salary)
        <span class="badge-soft badge-soft-purple me-2">
            <i class="fa-solid fa-sack-dollar me-1"></i>{{ $job->salary }}
        </span>
    @endif

    @if (isset($matchScores[$job->id]))
        <span class="badge-soft badge-soft-green me-2">
            {{ __('site.dashboard.match_score', ['score' => number_format($matchScores[$job->id], 0)]) }}
        </span>
    @endif

    @if ($job->skills->isNotEmpty())
        <div class="mt-2 d-flex flex-wrap gap-2">
            @foreach ($job->skills as $skill)
                <span class="badge-soft badge-soft-gray">{{ $skill->name }}</span>
            @endforeach
        </div>
    @endif

    </div>

    <div>
        @auth
            @if (auth()->user()->role === 'applicant')
                @if (in_array($job->id, $appliedJobIds))
                    <span class="badge-soft badge-soft-green">
                        <i class="fa-solid fa-check me-1"></i>{{ __('site.jobs.applied') }}
                    </span>
                @else
                    <form action="{{ route('jobs.apply', $job) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            {{ __('site.jobs.apply') }}
                        </button>
                    </form>
                @endif
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                {{ __('site.jobs.apply') }}
            </a>
        @endauth
    </div>

    </div>

    </div>

    @endforeach

@endif

</div>

</div>

</div>

</section>

@endsection
