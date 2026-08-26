@extends('layouts.app')

@section('content')

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<span class="section-badge">{{ __('site.contact.badge') }}</span>

<h1 class="fw-bold">{{ __('site.contact.title') }}</h1>

<p class="text-muted">
{{ __('site.contact.subtitle') }}
</p>

</div>

<div class="row">

<!-- Contact Form -->

<div class="col-lg-8">

<div class="card shadow border-0">

<div class="card-body p-4">

<form>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">{{ __('site.contact.full_name') }}</label>

<input type="text"
class="form-control"
placeholder="{{ __('site.contact.full_name_placeholder') }}">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">{{ __('site.contact.email_address') }}</label>

<input type="email"
class="form-control"
placeholder="{{ __('site.contact.email_placeholder') }}">

</div>

</div>

<div class="mb-3">

<label class="form-label">{{ __('site.contact.subject') }}</label>

<input type="text"
class="form-control"
placeholder="{{ __('site.contact.subject_placeholder') }}">

</div>

<div class="mb-3">

<label class="form-label">{{ __('site.contact.message') }}</label>

<textarea
class="form-control"
rows="6"
placeholder="{{ __('site.contact.message_placeholder') }}"></textarea>

</div>

<button class="btn btn-primary px-5">

<i class="fa-solid fa-paper-plane me-2"></i>

{{ __('site.contact.send_message') }}

</button>

</form>

</div>

</div>

</div>

<!-- Contact Info -->

<div class="col-lg-4">

<div class="card shadow border-0">

<div class="card-body p-4">

<h4 class="mb-4">{{ __('site.contact.info_title') }}</h4>

<div class="d-flex align-items-center gap-3 mb-4">
<div class="icon-badge icon-badge-sm grad-1">
<i class="fa-solid fa-envelope"></i>
</div>
<span dir="ltr">info@joblenshub.com</span>
</div>

<div class="d-flex align-items-center gap-3 mb-4">
<div class="icon-badge icon-badge-sm grad-2">
<i class="fa-solid fa-phone"></i>
</div>
<span dir="ltr">+963 986 967 533</span>
</div>

<div class="d-flex align-items-center gap-3 mb-4">
<div class="icon-badge icon-badge-sm grad-3">
<i class="fa-solid fa-location-dot"></i>
</div>
<span>{{ __('site.contact.address') }}</span>
</div>

<div class="d-flex align-items-center gap-3 mb-4">
<div class="icon-badge icon-badge-sm grad-4">
<i class="fa-solid fa-clock"></i>
</div>
<span>{{ __('site.footer.hours') }}</span>
</div>

<hr>

<h5 class="mb-3">{{ __('site.contact.follow_us') }}</h5>

<a href="#" class="me-3 text-primary">

<i class="fab fa-facebook fa-2x"></i>

</a>

<a href="#" class="me-3 text-primary">

<i class="fab fa-linkedin fa-2x"></i>

</a>

<a href="#" class="me-3 text-danger">

<i class="fab fa-instagram fa-2x"></i>

</a>

<a href="#" class="text-info">

<i class="fab fa-twitter fa-2x"></i>

</a>

</div>

</div>

</div>

</div>

</div>

</section>

@endsection
