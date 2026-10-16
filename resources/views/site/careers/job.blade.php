@extends('site.layouts.app')

@section('content')
    <div class="job-detail-page">
        <div class="careers-hero">
            <div class="wrap wrap-L">
                @include('site.careers._hero-breadcrumb', ['crumbs' => [[lroute('jobs.index'), __('site.careers.open_positions')]], 'current' => $job->title])
                <div class="hero-content">
                    <div class="hero-main">
                        <h1>{{ $job->title }}</h1>
                        @if ($job->department)
                            <div class="job-department"><span class="badge badge-department">{{ $job->department }}</span></div>
                        @endif
                        <div class="job-quick-info">
                            @if ($job->location)<div class="info-item">@include('site.svg.icon-location') {{ $job->location }}</div>@endif
                            @if ($job->schedule)<div class="info-item">@include('site.svg.icon-briefcase') {{ $job->schedule }}</div>@endif
                            @if ($job->posted_at)<div class="info-item">@include('site.svg.icon-calendar') {{ $job->posted_at->format('Y-m-d') }}</div>@endif
                        </div>
                    </div>
                    <div class="hero-actions">
                        @include('site.partials.button', ['url' => lroute('jobs.apply', ['reference' => $job->reference]), 'label' => __('site.careers.apply_now'), 'style' => 'primary', 'class' => 'btn-apply'])
                        <button class="btn-share" type="button" onclick="if(navigator.share){navigator.share({title:document.title,url:window.location.href})}else{navigator.clipboard.writeText(window.location.href);alert('Link copied!');}">
                            @include('site.svg.icon-share') {{ __('site.careers.share') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="job-content-section">
            <div class="wrap wrap-L">
                <div class="job-content-grid">
                    <div class="job-main-content">
                        @if ($job->body)
                            <div class="content-block">
                                <h2>{{ __('site.careers.about_position') }}</h2>
                                <div class="richtext">{!! $job->body !!}</div>
                            </div>
                        @endif
                        @if ($job->requirements)
                            <div class="content-block">
                                <h2>{{ __('site.careers.responsibilities') }}</h2>
                                <div class="richtext requirement-list">{!! $job->requirements !!}</div>
                            </div>
                        @endif
                    </div>
                    <aside class="job-sidebar">
                        <div class="sidebar-card">
                            <h3>{{ __('site.careers.company_name') }}</h3>
                            <p>{{ __('site.careers.company_text') }}</p>
                            <a href="{{ page_url('about') }}" class="link-arrow">{{ __('site.menu.about') }} @include('site.svg.arrow-white')</a>
                        </div>
                        <div class="sidebar-card">
                            <h3>{{ __('site.careers.job_details') }}</h3>
                            <ul class="details-list">
                                @if ($job->department)<li><strong>{{ __('site.careers.department') }}:</strong> {{ $job->department }}</li>@endif
                                @if ($job->location)<li><strong>{{ __('site.careers.location') }}:</strong> {{ $job->location }}</li>@endif
                                @if ($job->schedule)<li><strong>{{ __('site.careers.type') }}:</strong> {{ $job->schedule }}</li>@endif
                            </ul>
                        </div>
                        <div class="sidebar-card apply-card">
                            @include('site.partials.button', ['url' => lroute('jobs.apply', ['reference' => $job->reference]), 'label' => __('site.careers.apply_position'), 'style' => 'primary', 'class' => 'wide'])
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection
