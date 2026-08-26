@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">

    <div class="col-md-8 mb-3">
        <label class="form-label">{{ __('site.jobposts.field_title') }}</label>
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $jobPost->title ?? '') }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('site.jobposts.field_status') }}</label>
        <select name="status" class="form-select">
            <option value="open" {{ old('status', $jobPost->status ?? 'open') == 'open' ? 'selected' : '' }}>
                {{ __('site.jobposts.open') }}
            </option>
            <option value="closed" {{ old('status', $jobPost->status ?? 'open') == 'closed' ? 'selected' : '' }}>
                {{ __('site.jobposts.closed') }}
            </option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('site.jobposts.field_location') }}</label>
        <select name="location" class="form-select">
            <option value="">{{ __('site.jobposts.select_location') }}</option>
            @foreach (config('locations') as $locationSlug)
                <option value="{{ $locationSlug }}"
                    {{ old('location', $jobPost->location ?? '') === $locationSlug ? 'selected' : '' }}>
                    {{ __('site.locations.' . $locationSlug) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('site.jobposts.field_salary') }}</label>
        <input type="text" name="salary" class="form-control" value="{{ old('salary', $jobPost->salary ?? '') }}">
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">{{ __('site.jobposts.field_description') }}</label>
        <textarea name="description" rows="4" class="form-control" required>{{ old('description', $jobPost->description ?? '') }}</textarea>
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">{{ __('site.jobposts.field_requirements') }}</label>
        <textarea name="requirements" rows="3" class="form-control">{{ old('requirements', $jobPost->requirements ?? '') }}</textarea>
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">{{ __('site.jobposts.field_skills') }}</label>
        <input type="text" name="skills" class="form-control"
               value="{{ old('skills', $skillNames ?? '') }}"
               placeholder="{{ __('site.jobposts.skills_placeholder') }}">
        <div class="form-text">{{ __('site.jobposts.skills_help') }}</div>
    </div>

</div>

<button type="submit" class="btn btn-primary">{{ __('site.jobposts.save') }}</button>
<a href="{{ route('company.jobposts.index') }}" class="btn btn-outline-primary ms-2">{{ __('site.jobposts.back_to_list') }}</a>
