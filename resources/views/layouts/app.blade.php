<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JobLens</title>

    <!-- Bootstrap -->
    @if (app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!--================ Navbar ================-->

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
    <img src="{{ asset('images/logo-icon.png') }}"
         alt="JobLens Logo"
         class="logo-icon me-2">

    <span class="fw-bold fs-3 brand-word">
        <span class="job">Job</span><span class="lens">Lens</span>
    </span>
</a>

        <button class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">{{ __('site.nav.home') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/jobs">{{ __('site.nav.jobs') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/about">{{ __('site.nav.about') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#how-it-works">{{ __('site.nav.how_it_works') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/contact">{{ __('site.nav.contact') }}</a>
                </li>

            </ul>

            <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
               class="btn btn-outline-primary btn-sm me-2 lang-switch">
                <i class="fa-solid fa-globe me-1"></i>
                {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
            </a>

            @auth
                @if (auth()->user()->role === 'applicant')
                    <a href="{{ route('applicant.profile.edit') }}" class="btn btn-outline-primary me-2">
                        {{ __('site.nav.my_profile') }}
                    </a>
                    <a href="{{ route('applicant.applications.index') }}" class="btn btn-outline-primary me-2">
                        {{ __('site.nav.my_applications') }}
                    </a>
                @elseif (auth()->user()->role === 'company')
                    <a href="{{ route('company.jobposts.index') }}" class="btn btn-outline-primary me-2">
                        {{ __('site.nav.manage_jobs') }}
                    </a>
                @endif

                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">
                    {{ __('site.nav.dashboard') }}
                </a>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ __('site.nav.logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                    {{ __('site.nav.login') }}
                </a>

                <a href="{{ route('register') }}" class="btn btn-primary">
                    {{ __('site.nav.sign_up') }}
                </a>
            @endauth

        </div>

    </div>
</nav>

<main class="page-content">

@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success">{{ session('success') }}</div>
    </div>
@endif

@yield('content')

</main>

<footer class="footer text-white mt-5">

    <div class="container">

        <div class="row">

            <div class="col-md-4">

                <h3>JobLens</h3>

                <p>
                    {{ __('site.footer.tagline') }}
                </p>

            </div>

            <div class="col-md-2">

                <h5>{{ __('site.footer.quick_links') }}</h5>

                <ul class="list-unstyled">

                    <li><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>

                    <li><a href="/jobs">{{ __('site.nav.jobs') }}</a></li>

                    <li><a href="/about">{{ __('site.nav.about') }}</a></li>

                    <li><a href="/contact">{{ __('site.nav.contact') }}</a></li>

                </ul>

            </div>

            <div class="col-md-3">

                <h5>{{ __('site.footer.contact') }}</h5>

                <p>{{ __('site.footer.address') }}</p>

                <p><span dir="ltr">info@joblenshub.com</span></p>

                <p><span dir="ltr">+963 986 967 533</span></p>

            </div>

            <div class="col-md-3">

                <h5>{{ __('site.footer.follow_us') }}</h5>

                <i class="fab fa-facebook fa-2x me-2"></i>

                <i class="fab fa-instagram fa-2x me-2"></i>

                <i class="fab fa-linkedin fa-2x me-2"></i>

                <i class="fab fa-twitter fa-2x"></i>

            </div>

        </div>

        <hr>

        <p class="text-center">
            {{ __('site.footer.rights') }}
        </p>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>

</body>
</html>
