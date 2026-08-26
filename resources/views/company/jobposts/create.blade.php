@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4">{{ __('site.jobposts.create_title') }}</h2>

    <div class="card shadow border-0 p-4">
        <form method="POST" action="{{ route('company.jobposts.store') }}">
            @csrf
            @include('company.jobposts._form')
        </form>
    </div>

</div>
@endsection
