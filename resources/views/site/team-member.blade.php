@extends('site.layouts.app')

@section('content')
    <div class="hero_container">
        <div class="hero">
            <div class="wrap wrap-S">
                <div class="content animated fadeInUp delay-100">
                    <div class="breadcrumbs-minimal mb-4">
                        <a class="breadcrumb-item" href="{{ page_url('about') }}">{{ __('site.menu.about') }}</a>
                        <div class="breadcrumb-arrow"></div>
                        <a class="breadcrumb-item" href="{{ page_url('about') }}#team">{{ __('site.team.team') }}</a>
                        <div class="breadcrumb-arrow last"></div>
                        <a class="breadcrumb-item active" href="{{ url()->current() }}">{{ $member->name }}</a>
                    </div>
                    <h1 class="h2 mb-0">{{ $member->name }}</h1>
                    @if ($member->role)<h3 class="h4 font-normal color-primary mb-6">{{ $member->role }}</h3>@endif
                </div>
                <div class="featured-image">{!! lazy_img($member, 'photo', [1000, 500]) !!}</div>
                <div class="marginTopBottom-S">
                    @if ($member->lead)<p class="p0">{!! strip_tags($member->lead, '<b><strong><em><i><a><br>') !!}</p>@endif
                    {!! $member->bio !!}
                </div>
            </div>
        </div>
    </div>
@endsection
