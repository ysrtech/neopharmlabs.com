@extends('site.layouts.app', ['alternates' => []])

@php
    $segments = request()->segments();
    if (($segments[0] ?? null) === 'fr') {
        app()->setLocale('fr');
    }
    $seo = ['title' => __('site.not_found.title') . ' | Neopharm', 'description' => null];
@endphp

@section('content')
    <div class="hero_container bg-light rounded-bottom">
        <div class="hero">
            <div class="wrap wrap-S center">
                <h1 class="h2">{{ __('site.not_found.title') }}</h1>
                <p>{{ __('site.not_found.text') }}</p>
                @include('site.partials.button', ['url' => lroute('home'), 'label' => __('site.not_found.home'), 'style' => 'primary'])
            </div>
        </div>
    </div>
    <div class="paddingTopBottom-M"></div>
@endsection
