@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-1">{{ __('site.profile.title') }}</h2>
    <p class="text-muted mb-4">{{ __('site.profile.subtitle') }}</p>

    <div class="row g-4">

        <div class="col-lg-8">

            <!-- Personal Info -->
            <div class="card shadow border-0 p-4 mb-4">
                <h5 class="mb-4">{{ __('site.profile.personal_info') }}</h5>

                <form method="POST" action="{{ route('applicant.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('site.auth.full_name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('site.auth.phone') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('site.profile.birth_date') }}</label>
                            <input type="date" name="birth_date" class="form-control"
                                   value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('site.profile.gender') }}</label>
                            <select name="gender" class="form-select">
                                <option value="">{{ __('site.profile.not_specified') }}</option>
                                <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>{{ __('site.profile.male') }}</option>
                                <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>{{ __('site.profile.female') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('site.profile.education') }}</label>
                            <input type="text" name="education" class="form-control"
                                   value="{{ old('education', $user->education) }}"
                                   placeholder="{{ __('site.profile.education_placeholder') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('site.profile.experience_years') }}</label>
                            <input type="number" min="0" name="experience_years" class="form-control"
                                   value="{{ old('experience_years', $user->experience_years) }}" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">{{ __('site.profile.about') }}</label>
                            <textarea name="about" rows="4" class="form-control"
                                      placeholder="{{ __('site.profile.about_placeholder') }}">{{ old('about', $user->about) }}</textarea>
                        </div>

                    </div>

                    @error('name') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                    @error('phone') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                    @error('birth_date') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                    @error('experience_years') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                    <button type="submit" class="btn btn-primary">{{ __('site.profile.save_changes') }}</button>
                </form>
            </div>

            <!-- Skills -->
            <div class="card shadow border-0 p-4">
                <h5 class="mb-4">{{ __('site.profile.skills_title') }}</h5>

                <form method="POST" action="{{ route('applicant.skills.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('site.profile.skills_label') }}</label>
                        <input type="text" name="skills" class="form-control"
                               value="{{ old('skills', $skillNames) }}"
                               placeholder="{{ __('site.profile.skills_placeholder') }}">
                        <div class="form-text">{{ __('site.profile.skills_help') }}</div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('site.profile.save_skills') }}</button>
                </form>
            </div>

        </div>

        <div class="col-lg-4">

            <!-- Resume -->
            <div class="card shadow border-0 p-4">
                <h5 class="mb-4">{{ __('site.profile.resume_title') }}</h5>

                @if ($user->resume)
                    <p class="mb-3">
                        <i class="fa-solid fa-file-lines text-primary me-2"></i>
                        <a href="{{ asset('storage/' . $user->resume->file_path) }}" target="_blank">
                            {{ __('site.profile.view_current_resume') }}
                        </a>
                    </p>
                @else
                    <p class="text-muted mb-3">{{ __('site.profile.no_resume') }}</p>
                @endif

                <form method="POST" action="{{ route('applicant.resume.upload') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                        <div class="form-text">{{ __('site.profile.resume_help') }}</div>
                        @error('resume') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{ __('site.profile.upload_resume') }}</button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
