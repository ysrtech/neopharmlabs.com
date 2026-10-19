@extends('site.layouts.app')

@php
    $locale = app()->getLocale();
    $i = $locale === 'fr' ? 1 : 0;
    $categories = $categories->filter(fn ($s) => $s->hasTranslation($locale));
    $services = $services->filter(fn ($s) => $s->hasTranslation($locale));
    $filterGroups = [
        ['all' => __('site.services.all_phases'), 'options' => collect(\App\Models\Service::TAXONOMY['research_phase'])->map(fn ($l) => $l[$i])],
        ['all' => __('site.services.all_types'), 'options' => $categories->mapWithKeys(fn ($c) => [$c->getSlug('en') => $c->title])],
        ['all' => __('site.services.all_industries'), 'options' => collect(\App\Models\Service::TAXONOMY['industry'])->map(fn ($l) => $l[$i])],
        ['all' => __('site.services.all_molecules'), 'options' => collect(\App\Models\Service::TAXONOMY['molecule_type'])->map(fn ($l) => $l[$i])],
        ['all' => __('site.services.all_locations'), 'options' => collect(\App\Models\Service::TAXONOMY['location'])->map(fn ($l) => $l[$i])],
    ];
@endphp

@section('content')
    <div class="hero_container bg-color-light rounded-bottom">
        <div class="hero marginTopBottom-S">
            <div class="wrap">
                <div class="content animated fadeInUp delay-100">
                    <h1 class="h1">{!! __('site.services.index_title') !!}</h1>
                </div>
                <div class="slider-component animated fadeInLeft delay-100">
                    <div class="swiper services-slider">
                        <ul class="swiper-wrapper">
                            @foreach ($categories as $cat)
                                <li class="grid-item swiper-slide">
                                    <a class="card" href="{{ lroute('services.show', ['slug' => $cat->getSlug()]) }}">
                                        <div class="card-inner">
                                            <div class="card-header"><div class="card-top-icon">{!! $cat->icon_svg !!}</div></div>
                                            <div class="card-footer">
                                                <div class="card-info"><div class="card-title bigger">{{ $cat->title }}</div></div>
                                                <div class="arrow-circle">@include('site.svg.arrow-card')</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @include('site.partials.swiper-arrows')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="paddingTopBottom-M">
        <div class="wrap">
            <div class="services-filtering-section" id="filters">
                <h2 class="section-title h2">{{ __('site.services.all_heading') }}</h2>
                <div class="filters mt-4 mb-4">
                    <form class="services-list-filters" id="serviceFilters">
                        <span class="desktop-only filter-label color-gray font-medium mr-2">{{ __('site.services.filters') }}</span>
                        @foreach ($filterGroups as $group)
                            <fieldset>
                                <select class="filter-dropdown">
                                    <option value="">{{ $group['all'] }}</option>
                                    @foreach ($group['options'] as $value => $label)
                                        <option value=".{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        @endforeach
                    </form>
                </div>
            </div>
            <ul class="grid-container-3" id="services_mix_container">
                @foreach ($services as $item)
                    <li class="grid-item mix {{ $item->filterClasses() }}">
                        <a class="card" href="{{ lroute('services.show', ['slug' => $item->getSlug()]) }}">
                            <div class="card-inner">
                                <div class="card-header"><div class="card-top-parent">{{ $item->category()->title }}</div></div>
                                <div class="card-footer">
                                    <div class="card-info"><div class="card-title bigger">{{ $item->title }}</div></div>
                                    <div class="arrow-circle">@include('site.svg.arrow-card')</div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
                <div class="no-results-textbox">
                    <p class="p1">{{ __('site.services.no_results') }}</p>
                </div>
            </ul>
        </div>
    </div>
@endsection
