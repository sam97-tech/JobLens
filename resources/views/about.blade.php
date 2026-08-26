@extends('layouts.app')

@section('content')

<!-- About Section -->
<section class="py-5 bg-light">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<span class="section-badge text-start">{{ __('site.about.badge') }}</span>

<h1 class="fw-bold mb-4">
{{ __('site.about.title') }}
</h1>

<p class="text-muted">
{{ __('site.about.p1') }}
</p>

<p class="text-muted">
{{ __('site.about.p2') }}
</p>

</div>

<div class="col-lg-6 text-center">

<img src="{{ asset('images/about.png') }}"
class="img-fluid"
alt="About">

</div>

</div>

</div>

</section>

<!-- Vision Mission Values -->

<section class="py-5">

<div class="container">

<span class="section-badge">{{ __('site.about.foundation_badge') }}</span>

<h2 class="text-center fw-bold mb-5">
{{ __('site.about.foundation_title') }}
</h2>

<div class="row">

<!-- Vision -->

<div class="col-md-4">

<div class="card shadow border-0 h-100">

<div class="card-body text-center p-4">

<div class="icon-badge grad-1">
<i class="fa-solid fa-eye"></i>
</div>

<h4>{{ __('site.about.vision_title') }}</h4>

<p>
{{ __('site.about.vision_desc') }}
</p>

</div>

</div>

</div>

<!-- Mission -->

<div class="col-md-4">

<div class="card shadow border-0 h-100">

<div class="card-body text-center p-4">

<div class="icon-badge grad-2">
<i class="fa-solid fa-bullseye"></i>
</div>

<h4>{{ __('site.about.mission_title') }}</h4>

<p>
{{ __('site.about.mission_desc') }}
</p>

</div>

</div>

</div>

<!-- Values -->

<div class="col-md-4">

<div class="card shadow border-0 h-100">

<div class="card-body text-center p-4">

<div class="icon-badge grad-3">
<i class="fa-solid fa-gem"></i>
</div>

<h4>{{ __('site.about.values_title') }}</h4>

<ul class="list-unstyled">

<li>✔️ {{ __('site.about.value_fairness') }}</li>

<li>✔️ {{ __('site.about.value_innovation') }}</li>

<li>✔️ {{ __('site.about.value_transparency') }}</li>

<li>✔️ {{ __('site.about.value_equal_opportunities') }}</li>

<li>✔️ {{ __('site.about.value_efficiency') }}</li>

</ul>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- Why AI -->

<section class="py-5 bg-light">

<div class="container">

<span class="section-badge">{{ __('site.about.why_ai_badge') }}</span>

<h2 class="text-center fw-bold mb-5">
{{ __('site.about.why_ai_title') }}
</h2>

<div class="row text-center">

<div class="col-md-3">

<div class="icon-badge grad-1">
<i class="fa-solid fa-scale-balanced"></i>
</div>

<h5>{{ __('site.about.why_ai1_title') }}</h5>

<p>
{{ __('site.about.why_ai1_desc') }}
</p>

</div>

<div class="col-md-3">

<div class="icon-badge grad-2">
<i class="fa-solid fa-chart-line"></i>
</div>

<h5>{{ __('site.about.why_ai2_title') }}</h5>

<p>
{{ __('site.about.why_ai2_desc') }}
</p>

</div>

<div class="col-md-3">

<div class="icon-badge grad-3">
<i class="fa-solid fa-user-check"></i>
</div>

<h5>{{ __('site.about.why_ai3_title') }}</h5>

<p>
{{ __('site.about.why_ai3_desc') }}
</p>

</div>

<div class="col-md-3">

<div class="icon-badge grad-4">
<i class="fa-solid fa-award"></i>
</div>

<h5>{{ __('site.about.why_ai4_title') }}</h5>

<p>
{{ __('site.about.why_ai4_desc') }}
</p>

</div>

</div>

</div>

</section>

@endsection
