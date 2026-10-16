@extends('site.layouts.app')

@section('content')
    <div class="hero_container hero-search-page bg-light rounded-bottom">
        <div class="hero">
            <div class="wrap wrap-S center">
                <form method="get" action="{{ lroute('search') }}">
                    <div class="search-form-layout">
                        <div class="search-flex-parent">
                            <div class="search-field">
                                <input name="q" class="search-text-input" required placeholder="{{ __('site.search_page.placeholder') }}" type="text" value="{{ $q }}">
                                <button class="btn-search" aria-label="{{ __('site.search') }}">@include('site.svg.search-button')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="search-results-container">
        <div class="wrap wrap-M">
            @if (filled($q))
                <p class="search-top-header-text">
                    {{ $results->isEmpty() ? __('site.search_page.none', ['q' => $q]) : __('site.search_page.results', ['count' => $results->count(), 'q' => $q]) }}
                </p>
                <ul class="search--list-grid search-results">
                    @foreach ($results as $result)
                        <li class="search--card">
                            <a href="{{ $result['url'] }}">
                                <span class="opacity-50 text-s">{{ $result['type'] }}</span><br>
                                {{ $result['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
            <p class="search-top-header-text">{{ __('site.search_page.suggested') }}</p>
            <ul class="search--list-grid">
                @foreach ($suggested as $service)
                    @continue(! $service->hasTranslation(app()->getLocale()))
                    <li class="search--card"><a href="{{ lroute('services.show', ['slug' => $service->getSlug()]) }}">{{ $service->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
