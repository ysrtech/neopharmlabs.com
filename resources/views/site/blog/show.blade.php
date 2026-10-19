@extends('site.layouts.app')

@php
    $bodyClass = '';
    $shareUrl = url()->current();
    $authorPhoto = $post->hasImage('author_photo') ? $post->image('author_photo', 'default', ['w' => 120, 'h' => 120, 'fit' => 'crop']) : null;
@endphp

@section('content')
    <div class="hero_container bg-light rounded-bottom blogPost">
        <div class="hero">
            <div class="wrap side-by-side-layout">
                <div class="content animated fadeInUp delay-100">
                    <div class="breadcrumbs-minimal mb-4">
                        <a class="breadcrumb-item" href="{{ lroute('blog.index') }}">{{ __('site.menu.blog') }}</a>
                        @if ($post->category)
                            <div class="breadcrumb-arrow"></div>
                            <a class="breadcrumb-item" href="{{ lroute('blog.category', ['slug' => $post->category->getSlug()]) }}">{{ $post->category->title }}</a>
                        @endif
                        <div class="breadcrumb-arrow last"></div>
                        <a class="breadcrumb-item active" href="{{ $shareUrl }}">{{ $post->title }}</a>
                    </div>
                    <h1 class="h2 mb-4">{{ $post->title }}</h1>
                    @if ($post->excerpt)<p>{{ $post->excerpt }}</p>@endif
                    <div class="blog-detail blog-post-information-header">
                        @if ($authorPhoto)<img class="user-photo" src="{{ $authorPhoto }}" alt="">@endif
                        <div class="info-beside-user-photo">
                            <ul class="information-list text-m">
                                @if ($post->author_name)
                                    <li>{{ __('site.blog.written_by') }} <span class="headerName"> {{ $post->author_name }}</span></li>
                                @endif
                                @if ($post->published_at)
                                    <li>{{ __('site.blog.posted_on') }} <span class="posted_date">{{ $post->published_at->locale(app()->getLocale())->translatedFormat(__('site.blog.long_date_format')) }}</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="featured-image">{!! lazy_img($post, 'cover', [1000, 500]) !!}</div>
            </div>
        </div>
    </div>

    <div class="wrap paddingTopBottom-M">
        <div class="blog-content-grid">
            <div class="social-column">
                <div class="sharing-blog-buttons-block">
                    <div class="share_social">
                        <ul class="share_circles">
                            <li><a class="circle-social-share" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" aria-label="Facebook">@include('site.svg.share-facebook')</a></li>
                            <li><a class="circle-social-share" target="_blank" rel="noopener" href="https://twitter.com/share?url={{ urlencode($shareUrl) }}" aria-label="X">@include('site.svg.share-twitter')</a></li>
                            <li><a class="circle-social-share" target="_blank" rel="noopener" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($shareUrl) }}" aria-label="LinkedIn">@include('site.svg.share-linkedin')</a></li>
                            <li><a class="circle-social-share" href="mailto:?subject={{ rawurlencode(__('site.blog.share_subject')) }}&body={{ rawurlencode(__('site.blog.share_body') . ' ' . $shareUrl) }}" aria-label="Email">@include('site.svg.share-email')</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content-section blog-section">
                @include('site.partials.content-blocks', ['item' => $post])

                @if ($post->author_name)
                    <div class="footer-author-section blog-post-footer">
                        <div class="blog-detail">
                            @if ($authorPhoto)<img class="user-photo" src="{{ $authorPhoto }}" alt="">@endif
                            <div class="info-beside-user-photo"><span class="authorName">{{ $post->author_name }}</span></div>
                        </div>
                        @if ($post->author_bio)
                            <div class="blog-author-shortBio">{!! strip_tags($post->author_bio, '<a><b><strong><em><i><br>') !!}</div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="call-to-action-column">
                <div class="leadgen-box bg-color-light">
                    <p class="h5 font-semibold mb-2">{{ __('site.blog.leadgen_title') }}</p>
                    <p>{{ __('site.blog.leadgen_text') }}</p>
                    @include('site.partials.button', ['url' => lroute('resources.index'), 'label' => __('site.blog.leadgen_button'), 'style' => 'primary'])
                </div>
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <div class="bg-color-light">
            <div class="paddingTopBottom-S bg-white rounded-bottom"></div>
            <div class="wrap paddingTopBottom-M">
                <div class="related-section">
                    <p class="h3 mb-8">{{ __('site.blog.related') }}</p>
                    <ul class="grid-container grid-column-3">
                        @foreach ($related as $relatedPost)
                            @include('site.partials.post-card', ['post' => $relatedPost])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
@endsection
