@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
<div class="container" style="max-width: 460px;">

    <div class="card auth-card shadow border-0 p-4 p-md-5">

        <div class="auth-icon">
            <i class="fa-solid fa-right-to-bracket"></i>
        </div>

        <h2 class="fw-bold mb-4 text-center">{{ __('site.auth.login_title') }}</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('site.auth.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('site.auth.password') }}</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">{{ __('site.auth.login_btn') }}</button>
        </form>

        <p class="text-center mt-4 mb-0">
            {{ __('site.auth.no_account') }} <a href="{{ route('register') }}">{{ __('site.auth.sign_up') }}</a>
        </p>

    </div>

</div>
</div>
@endsection
