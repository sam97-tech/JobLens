@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <h2 class="fw-bold mb-0">{{ __('site.jobposts.index_title') }}</h2>

        <a href="{{ route('company.jobposts.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            {{ __('site.jobposts.post_new') }}
        </a>
    </div>

    @if ($jobPosts->isEmpty())
        <div class="card shadow border-0 p-4">
            <p class="text-muted mb-0">{{ __('site.jobposts.no_posts') }}</p>
        </div>
    @else
        <div class="card shadow border-0 p-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('site.jobposts.title_label') }}</th>
                            <th>{{ __('site.jobposts.status_label') }}</th>
                            <th>{{ __('site.jobposts.applicants_label') }}</th>
                            <th class="text-end">{{ __('site.jobposts.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobPosts as $jobPost)
                            <tr>
                                <td>{{ $jobPost->title }}</td>
                                <td>
                                    <span class="badge-soft {{ $jobPost->status === 'open' ? 'badge-soft-green' : 'badge-soft-gray' }}">
                                        {{ $jobPost->status === 'open' ? __('site.jobposts.open') : __('site.jobposts.closed') }}
                                    </span>
                                </td>
                                <td>{{ $jobPost->applications_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('company.jobposts.applicants', $jobPost) }}" class="btn btn-sm btn-outline-primary me-1">
                                        {{ __('site.jobposts.view_applicants') }}
                                    </a>
                                    <a href="{{ route('company.jobposts.edit', $jobPost) }}" class="btn btn-sm btn-outline-primary me-1">
                                        {{ __('site.jobposts.edit') }}
                                    </a>
                                    <form action="{{ route('company.jobposts.destroy', $jobPost) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('site.jobposts.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('site.jobposts.delete') }}</button>
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
