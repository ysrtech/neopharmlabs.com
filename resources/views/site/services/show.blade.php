@extends('site.layouts.app')

@php
    $headerClass = 'is-white';
    $related = $related->filter(fn ($s) => $s->hasTranslation(app()->getLocale()));
@endphp

@section('content')
    <div class="hero_container bg-img-overlay rounded-bottom">
        <div class="hero morePadding">
            <div class="wrap">
                <div class="content-two-thirds animated fadeInUp delay-100">
                    <div class="breadcrumbs-minimal white mb-4">
                        <a class="breadcrumb-item" href="{{ lroute('services.index') }}">{{ __('site.menu.services') }}</a>
                        @if ($category->id !== $service->id)
                            <div class="breadcrumb-arrow"></div>
                            <a class="breadcrumb-item" href="{{ lroute('services.show', ['slug' => $category->getSlug()]) }}">{{ $category->title }}</a>
                        @endif
                        <div class="breadcrumb-arrow last"></div>
                        <a class="breadcrumb-item active" href="{{ lroute('services.show', ['slug' => $service->getSlug()]) }}">{{ $service->title }}</a>
                    </div>
                    <h1 class="h1 text-white">{{ $service->hero_title ?: $service->title }}</h1>
                    @if ($service->hero_intro)
                        <p class="p0 text-white">{!! strip_tags($service->hero_intro, '<b><strong><em><i><a><br>') !!}</p>
                    @endif
                    @include('site.partials.button', [
                        'url' => $service->hero_button_url ?: page_url('contact'),
                        'label' => $service->hero_button_label ?: __('site.contact_us'),
                        'style' => 'primary',
                    ])
                </div>
                <div class="featured-image">{!! lazy_img($service, 'cover', [1600, 1000, 500]) !!}</div>
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <div class="services-parent-list-container bg-light rounded-bottom">
            <div class="paddingTopBottom-M">
                <div class="wrap">
                    <div class="mb-8">
                        <h4 class="h4">{{ __('site.services.related', ['name' => $category->title]) }} </h4>
                    </div>
                    <ul class="services-parent-list-grid">
                        @foreach ($related as $relatedService)
                            <li class="grid-item">
                                <a class="card service-card thin {{ $relatedService->id === $service->id ? 'active' : '' }}" href="{{ lroute('services.show', ['slug' => $relatedService->getSlug()]) }}">
                                    <div class="card-inner v-center">
                                        <div class="card-footer">
                                            <div class="card-info"><div class="card-title bigger">{{ $relatedService->title }}</div></div>
                                            <div class="arrow-circle">@include('site.svg.arrow-card')</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="wrap">
        <div class="paddingTopBottom-M">
            <div class="service-content-grid content-section">
                <div class="sticky-column">
                    @include('site.partials.leadgen')
                </div>
                <div class="content-column">
                    @include('site.partials.content-blocks', ['item' => $service])
                </div>
            </div>
        </div>
    </div>
@endsection
