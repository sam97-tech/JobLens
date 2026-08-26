@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <h2 class="fw-bold mb-0">{{ __('site.jobposts.applicants_title', ['title' => $jobPost->title]) }}</h2>

        <a href="{{ route('company.jobposts.index') }}" class="btn btn-outline-primary">
            {{ __('site.jobposts.back_to_list') }}
        </a>
    </div>

    @if ($applications->isEmpty())
        <div class="card shadow border-0 p-4">
            <p class="text-muted mb-0">{{ __('site.jobposts.no_applicants') }}</p>
        </div>
    @else
        <div class="card shadow border-0 p-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('site.jobposts.applicant_name') }}</th>
                            <th>{{ __('site.jobposts.applicant_email') }}</th>
                            <th>{{ __('site.jobposts.applicant_experience') }}</th>
                            <th>{{ __('site.jobposts.applicant_education') }}</th>
                            <th>{{ __('site.jobposts.match_score_label') }}</th>
                            <th>{{ __('site.jobposts.applicant_resume') }}</th>
                            <th>{{ __('site.jobposts.status_label') }}</th>
                            <th class="text-end">{{ __('site.jobposts.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                            <tr>
                                <td>{{ $application->user->name }}</td>
                                <td><span dir="ltr">{{ $application->user->email }}</span></td>
                                <td>{{ $application->user->experience_years }}</td>
                                <td>{{ $application->user->education ?? '-' }}</td>
                                <td>
                                    @if (!is_null($application->match_score))
                                        <span class="badge-soft badge-soft-blue">
                                            {{ number_format($application->match_score, 0) }}%
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($application->user->resume)
                                        <a href="{{ asset('storage/' . $application->user->resume->file_path) }}" target="_blank">
                                            {{ __('site.jobposts.view_resume') }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('site.jobposts.no_resume') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($application->status) {
                                            'accepted' => 'badge-soft-green',
                                            'rejected' => 'badge-soft-red',
                                            default => 'badge-soft-gray',
                                        };
                                    @endphp
                                    <span class="badge-soft {{ $statusClass }}">
                                        {{ __('site.jobposts.status_' . $application->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('company.applications.updateStatus', $application) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-sm btn-outline-primary me-1"
                                                {{ $application->status === 'accepted' ? 'disabled' : '' }}>
                                            {{ __('site.jobposts.accept') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('company.applications.updateStatus', $application) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                {{ $application->status === 'rejected' ? 'disabled' : '' }}>
                                            {{ __('site.jobposts.reject') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
