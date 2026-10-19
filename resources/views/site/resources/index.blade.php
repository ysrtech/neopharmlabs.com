@extends('site.layouts.app')

@php($bodyClass = 'bg-light')

@section('content')
    <div class="hero_container bg-white rounded-bottom">
        <div class="hero marginTopBottom-M">
            <div class="wrap wrap-M center">
                <div class="content animated fadeInUp delay-100">
                    <h1 class="h1">{!! $type ? e(\App\Models\Resource::TYPES[$type][app()->getLocale() === 'fr' ? 1 : 0]) : __('site.resources.title') !!}</h1>
                    <ul class="resources-filters">
                        <li><a class="filter tag {{ $type ? '' : 'active' }}" href="{{ lroute('resources.index') }}">{{ __('site.resources.all') }}</a></li>
                        @foreach (\App\Models\Resource::TYPES as $typeKey => $typeLabels)
                            <li><a class="filter tag {{ $type === $typeKey ? 'active' : '' }}" href="{{ lroute('resources.type', ['type' => $typeKey]) }}">{{ $typeLabels[app()->getLocale() === 'fr' ? 1 : 0] }}</a></li>
                        @endforeach
                        <li><a class="filter tag" href="{{ lroute('blog.index') }}">{{ __('site.menu.blog') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content hero marginTopBottom-M">
        <div class="wrap">
            @if ($resources->isEmpty())
                <p class="center">{{ __('site.resources.empty') }}</p>
            @else
                <ul class="grid-container">
                    @foreach ($resources as $resource)
                        @include('site.partials.resource-card', ['resource' => $resource])
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
