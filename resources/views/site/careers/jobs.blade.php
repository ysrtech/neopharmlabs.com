@extends('site.layouts.app')

@php
    $badges = collect();
    foreach ($jobs as $job) {
        foreach (['location' => $job->location, 'jobtype' => $job->schedule, 'department' => $job->department] as $type => $value) {
            if (filled($value)) {
                $badges->put($type . ':' . \Illuminate\Support\Str::slug($value), ['type' => $type, 'value' => \Illuminate\Support\Str::slug($value), 'label' => $value]);
            }
        }
    }
    $badges = $badges->sortBy(fn ($b) => array_search($b['type'], ['location', 'jobtype', 'department']))->values();
@endphp

@section('content')
    <div class="careers-hero">
        <div class="wrap">
            @include('site.careers._hero-breadcrumb', ['current' => __('site.careers.open_positions')])
            <span class="hero-badge">{{ __('site.careers.hiring') }}</span>
            <h1>{{ __('site.careers.jobs_title') }}</h1>
            <p>{{ __('site.careers.jobs_text') }}</p>
        </div>
    </div>
    <div class="jobs-section">
        <div class="wrap wrap-L">
            <div class="jobs-layout-grid">
                <div class="jobs-main-content">
                    <div class="search-bar-container">
                        @include('site.svg.icon-search')
                        <input type="text" id="searchJobs" placeholder="{{ __('site.careers.search_placeholder') }}" class="search-input">
                    </div>
                    <div class="filter-badges">
                        <button class="badge-filter active" data-filter="all" data-type="department">{{ __('site.careers.view_all') }}</button>
                        @foreach ($badges as $badge)
                            <button class="badge-filter" data-filter="{{ $badge['value'] }}" data-type="{{ $badge['type'] }}">{{ $badge['label'] }}</button>
                        @endforeach
                    </div>
                    <ul class="jobs-list" id="jobsContainer">
                        @forelse ($jobs as $job)
                            <li class="job-item" data-department="{{ \Illuminate\Support\Str::slug($job->department) }}" data-location="{{ \Illuminate\Support\Str::slug($job->location) }}" data-jobtype="{{ \Illuminate\Support\Str::slug($job->schedule) }}">
                                <a href="{{ lroute('jobs.show', ['reference' => $job->reference]) }}" class="job-card">
                                    <div class="job-info">
                                        <h3 class="job-title">{{ $job->title }}</h3>
                                        <p class="job-description">{{ \Illuminate\Support\Str::limit($job->excerpt ?: strip_tags((string) $job->body), 150) }}</p>
                                        <div class="job-badges">
                                            @if ($job->location)<span class="job-badge">{{ $job->location }}</span>@endif
                                            @if ($job->schedule)<span class="job-badge">{{ $job->schedule }}</span>@endif
                                        </div>
                                    </div>
                                    <div class="job-apply">
                                        <span class="apply-btn">{{ __('site.careers.apply') }} @include('site.svg.icon-arrow-right')</span>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="job-item no-jobs"><p>{{ __('site.careers.no_jobs') }}</p></li>
                        @endforelse
                    </ul>
                    <div class="pagination-wrapper" id="paginationWrapper">
                        <button class="pagination-btn prev-btn" id="prevPage" disabled>@include('site.svg.icon-chevron-left') {{ __('site.careers.previous') }}</button>
                        <div class="pagination-numbers" id="paginationNumbers"></div>
                        <button class="pagination-btn next-btn" id="nextPage">{{ __('site.careers.next') }} @include('site.svg.icon-chevron-right')</button>
                    </div>
                </div>
                <aside class="jobs-sidebar">
                    <div class="spontaneous-banner">
                        <div class="spontaneous-content">
                            <h3>{{ __('site.careers.spontaneous_title') }}</h3>
                            <p>{{ __('site.careers.spontaneous_text') }}</p>
                            @include('site.partials.button', ['url' => lroute('jobs.spontaneous'), 'label' => __('site.careers.spontaneous_button'), 'style' => 'primary'])
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/jobs-filtering.js"></script>
@endpush
