@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
<div class="container" style="max-width: 560px;">

    <div class="card auth-card shadow border-0 p-4 p-md-5">

        <div class="auth-icon">
            <i class="fa-solid fa-user-plus"></i>
        </div>

        <h2 class="fw-bold mb-4 text-center">{{ __('site.auth.register_title') }}</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('site.auth.i_am') }}</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="role" id="role_applicant"
                           value="applicant" autocomplete="off"
                           {{ old('role', 'applicant') == 'applicant' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="role_applicant">{{ __('site.auth.job_seeker') }}</label>

                    <input type="radio" class="btn-check" name="role" id="role_company"
                           value="company" autocomplete="off"
                           {{ old('role') == 'company' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="role_company">{{ __('site.auth.company') }}</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('site.auth.full_name') }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('site.auth.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('site.auth.phone') }}</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div id="company_fields" class="border rounded p-3 mb-3 bg-light" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">{{ __('site.auth.company_name') }}</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('site.auth.company_address') }}</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>
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

            <div class="mb-3">
                <label class="form-label">{{ __('site.auth.confirm_password') }}</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">{{ __('site.auth.create_account_btn') }}</button>
        </form>

        <p class="text-center mt-4 mb-0">
            {{ __('site.auth.already_account') }} <a href="{{ route('login') }}">{{ __('site.auth.login_link') }}</a>
        </p>

    </div>

</div>
</div>

<script>
    const companyRadio = document.getElementById('role_company');
    const applicantRadio = document.getElementById('role_applicant');
    const companyFields = document.getElementById('company_fields');

    function toggleCompanyFields() {
        companyFields.style.display = companyRadio.checked ? 'block' : 'none';
    }

    companyRadio.addEventListener('change', toggleCompanyFields);
    applicantRadio.addEventListener('change', toggleCompanyFields);
    toggleCompanyFields();
</script>
@endsection
