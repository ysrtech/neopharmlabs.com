@extends('site.layouts.app')

@php
    $presenters = root_blocks($resource)->where('type', 'presenter');
    $otherBlocks = root_blocks($resource)->where('type', '!=', 'presenter');
@endphp

@section('content')
    <div class="hero_container bg-light rounded-bottom">
        <div class="hero">
            <div class="wrap">
                <div class="flex-50 half-60-40">
                    <div class="flex-child animated fadeInUp delay-100">
                        <div class="breadcrumbs-minimal mb-4">
                            <a class="breadcrumb-item" href="{{ lroute('resources.index') }}">{{ __('site.resources.library') }}</a>
                            <div class="breadcrumb-arrow"></div>
                            <a class="breadcrumb-item" href="{{ lroute('resources.type', ['type' => $resource->type]) }}">{{ $resource->typeLabel() }}</a>
                            <div class="breadcrumb-arrow last"></div>
                            <a class="breadcrumb-item active" href="{{ url()->current() }}">{{ $resource->title }}</a>
                        </div>
                        <h1 class="h2">{{ $resource->title }}</h1>
                        @if ($resource->intro)<p class="p1">{!! strip_tags($resource->intro, '<b><strong><em><i><a><br>') !!}</p>@endif
                        @include('site.partials.button', ['url' => '#form', 'label' => $resource->button_label ?: __('site.resources.download_now'), 'style' => 'primary'])
                    </div>
                    <div class="flex-child">
                        <div class="featured-image padded">{!! lazy_img($resource, 'cover', [1000, 500]) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrap">
        <div class="paddingTopBottom-M">
            <div class="two-col-content-and-thin-form">
                <div class="content-side">
                    @if ($resource->body)
                        <div class="rich-text clearfix">{!! $resource->body !!}</div>
                    @endif
                    @foreach ($otherBlocks as $block)
                        @includeIf('site.blocks.' . $block->type, ['block' => $block])
                    @endforeach
                    @if ($presenters->isNotEmpty())
                        @if ($resource->presenters_title)
                            <div class="rich-text clearfix"><h3>{{ $resource->presenters_title }}</h3></div>
                        @endif
                        <div class="paddingTopBottom-S">
                            <ul class="presenters-grid">
                                @foreach ($presenters as $block)
                                    @include('site.blocks.presenter', ['block' => $block])
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="form-side">
                    <div class="leadgen-box bg-color-light">
                        @if ($resource->form_title)
                            <div class="mb-4"><p class="h5">{{ $resource->form_title }}</p></div>
                        @endif
                        @include('site.forms.resource', ['resource' => $resource])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
