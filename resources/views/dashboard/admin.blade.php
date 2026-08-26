@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4">{{ __('site.dashboard.admin_title') }}</h2>
    <p class="text-muted">{{ __('site.dashboard.admin_welcome', ['name' => auth()->user()->name]) }}</p>

</div>
@endsection
