@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">{{ __('site.jobs.my_applications_title') }}</h2>
        <p class="text-muted mb-0">{{ __('site.jobs.my_applications_subtitle') }}</p>
    </div>

    @if ($applications->isEmpty())
        <div class="card shadow border-0 p-4">
            <p class="text-muted mb-0">{{ __('site.jobs.no_applications') }}</p>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary mt-3 align-self-start">
                {{ __('site.jobs.title') }}
            </a>
        </div>
    @else
        <div class="card shadow border-0 p-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('site.jobposts.title_label') }}</th>
                            <th>{{ __('site.jobs.company_label') }}</th>
                            <th>{{ __('site.jobs.applied_on') }}</th>
                            <th>{{ __('site.jobposts.status_label') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                            <tr>
                                <td>{{ $application->jobPost->title }}</td>
                                <td>{{ $application->jobPost->company->company_name ?? '' }}</td>
                                <td>{{ $application->created_at->format('Y-m-d') }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
