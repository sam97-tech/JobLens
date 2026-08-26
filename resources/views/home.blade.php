@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="hero py-5 text-white">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6">

                <span class="hero-badge">
                    <span class="dot"></span>
                    {{ __('site.home.badge') }}
                </span>

                <h1 class="display-3 fw-bold">
                    {{ __('site.home.title_line1') }}
                    <span class="text-gradient">{{ __('site.home.title_line2') }}</span>
                </h1>

                <p class="lead my-4">
                    {{ __('site.home.description') }}
                </p>

                <a href="/jobs" class="btn btn-primary btn-lg me-3">
                    {{ __('site.home.find_jobs') }}
                </a>

                @if (!auth()->check() || auth()->user()->role === 'company')
                    <a href="{{ auth()->check() ? route('company.jobposts.create') : route('register') }}"
                       class="btn btn-glass btn-lg">
                        {{ __('site.home.post_job') }}
                    </a>
                @endif

                <div class="row mt-5 stats">

                    <div class="col-3 text-center">
                        <h3>500+</h3>
                        <small>{{ __('site.home.stat_companies') }}</small>
                    </div>

                    <div class="col-3 text-center">
                        <h3>10K+</h3>
                        <small>{{ __('site.home.stat_seekers') }}</small>
                    </div>

                    <div class="col-3 text-center">
                        <h3>2K+</h3>
                        <small>{{ __('site.home.stat_jobs') }}</small>
                    </div>

                    <div class="col-3 text-center">
                        <h3>95%</h3>
                        <small>{{ __('site.home.stat_satisfaction') }}</small>
                    </div>

                </div>

            </div>

            <div class="col-lg-6 text-center">

                <div class="hero-image-wrap">
                    <img src="{{ asset('images/dashboard.png') }}"
                    class="img-fluid rounded shadow">
                </div>

            </div>

        </div>
    </div>
</section>

<!-- How It Works -->

<section id="how-it-works" class="py-5 bg-light">

<div class="container">

<span class="section-badge">{{ __('site.home.process_badge') }}</span>

<h2 class="text-center fw-bold">
{{ __('site.home.how_it_works_title') }}
</h2>

<p class="section-subtitle">
{{ __('site.home.how_it_works_subtitle') }}
</p>

<div class="row text-center">

<div class="col-md-3">

<div class="icon-badge grad-1">
<i class="fa-solid fa-file-arrow-up"></i>
</div>

<h5>{{ __('site.home.step1_title') }}</h5>

<p>{{ __('site.home.step1_desc') }}</p>

</div>

<div class="col-md-3">

<div class="icon-badge grad-2">
<i class="fa-solid fa-robot"></i>
</div>

<h5>{{ __('site.home.step2_title') }}</h5>

<p>{{ __('site.home.step2_desc') }}</p>

</div>

<div class="col-md-3">

<div class="icon-badge grad-3">
<i class="fa-solid fa-chart-column"></i>
</div>

<h5>{{ __('site.home.step3_title') }}</h5>

<p>{{ __('site.home.step3_desc') }}</p>

</div>

<div class="col-md-3">

<div class="icon-badge grad-4">
<i class="fa-solid fa-user-check"></i>
</div>

<h5>{{ __('site.home.step4_title') }}</h5>

<p>{{ __('site.home.step4_desc') }}</p>

</div>

</div>

</div>

</section>

<!-- Why Choose -->

<section class="py-5">

<div class="container">

<span class="section-badge">{{ __('site.home.why_badge') }}</span>

<h2 class="text-center fw-bold">
{{ __('site.home.why_title') }}
</h2>

<p class="section-subtitle">
{{ __('site.home.why_subtitle') }}
</p>

<div class="row">

<div class="col-md-3">

<div class="card shadow border-0 p-4 text-center">

<div class="icon-badge grad-1">
<i class="fa-solid fa-shield-halved"></i>
</div>

<h5>{{ __('site.home.why1_title') }}</h5>

<p>{{ __('site.home.why1_desc') }}</p>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0 p-4 text-center">

<div class="icon-badge grad-2">
<i class="fa-solid fa-clock"></i>
</div>

<h5>{{ __('site.home.why2_title') }}</h5>

<p>{{ __('site.home.why2_desc') }}</p>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0 p-4 text-center">

<div class="icon-badge grad-3">
<i class="fa-solid fa-bullseye"></i>
</div>

<h5>{{ __('site.home.why3_title') }}</h5>

<p>{{ __('site.home.why3_desc') }}</p>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0 p-4 text-center">

<div class="icon-badge grad-4">
<i class="fa-solid fa-lock"></i>
</div>

<h5>{{ __('site.home.why4_title') }}</h5>

<p>{{ __('site.home.why4_desc') }}</p>

</div>

</div>

</div>

</div>

</section>

@endsection
