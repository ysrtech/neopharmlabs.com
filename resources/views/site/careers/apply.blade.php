@extends('site.layouts.app')

@section('content')
    <div class="careers-apply-page">
        <div class="careers-hero">
            <div class="wrap wrap-L">
                @include('site.careers._hero-breadcrumb', [
                    'crumbs' => array_filter([[lroute('jobs.index'), __('site.careers.open_positions')], $job ? [lroute('jobs.show', ['reference' => $job->reference]), $job->title] : null]),
                    'current' => $job ? __('site.careers.apply') : __('site.careers.spontaneous'),
                ])
                <div class="hero-content">
                    <div class="hero-main">
                        <h1>{{ $job ? __('site.careers.apply') . ' - ' . $job->title : __('site.careers.spontaneous') }}</h1>
                        @unless ($job)<p>{{ __('site.careers.spontaneous_intro') }}</p>@endunless
                    </div>
                </div>
            </div>
        </div>
        <section class="apply-content">
            <div class="wrap">
                <div class="apply-grid">
                    <div class="apply-form-container">
                        @include('site.forms.application', ['job' => $job])
                    </div>
                    <aside class="apply-sidebar">
                        <div class="sidebar-card">
                            <h3 class="sidebar-title">{{ __('site.careers.help_title') }}</h3>
                            <p class="sidebar-text">{{ __('site.careers.help_text') }}</p>
                            <a href="mailto:{{ config('site.contact.careers_email') }}" class="sidebar-link">{{ config('site.contact.careers_email') }}</a>
                        </div>
                        <div class="sidebar-card">
                            <h3 class="sidebar-title">{{ __('site.careers.why_title') }}</h3>
                            <p class="sidebar-text">{{ __('site.careers.why_text') }}</p>
                            <a href="{{ lroute('jobs.index') }}" class="sidebar-link">{{ __('site.careers.view_positions') }}</a>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
@endsection
