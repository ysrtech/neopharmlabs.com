@extends('site.layouts.app')

@php($bodyClass = 'bg-light')

@section('content')
    <div class="hero_container blogIndex bg-white rounded-bottom">
        <div class="hero marginTopBottom-S">
            <div class="wrap">
                <div class="flex-50">
                    <div class="flex-child hero-title">
                        <h1 class="h1 mb-0">{{ $category ? ($category->heading ?: $category->title) : __('site.blog.title') }}</h1>
                    </div>
                    <div class="flex-child">
                        <div class="category-navigation-block">
                            <ul class="categories-grid">
                                <li><a class="tag {{ $category ? '' : 'active' }}" href="{{ lroute('blog.index') }}">{{ __('site.blog.all_categories') }}</a></li>
                                @foreach ($categories as $cat)
                                    <li><a class="tag {{ $category?->id === $cat->id ? 'active' : '' }}" href="{{ lroute('blog.category', ['slug' => $cat->getSlug()]) }}">{{ $cat->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrap">
        <div class="paddingTopBottom-M">
            <h5 class="h5">{{ __('site.blog.recent') }}</h5>
            <ul class="blog-grid-container">
                @foreach ($posts as $post)
                    @if ($loop->first && $posts->currentPage() === 1)
                        <li class="grid-item featured">
                            <a href="{{ lroute('blog.show', ['slug' => $post->getSlug()]) }}" class="featured resource-card blog-card">
                                <div class="card-inner">
                                    <div class="card-top">
                                        <div class="card-header">
                                            @if ($post->category)<div class="card-category">{{ $post->category->title }}</div>@endif
                                            @if ($post->published_at)<span class="card-date">{{ $post->published_at->locale(app()->getLocale())->translatedFormat(__('site.blog.date_format')) }}</span>@endif
                                        </div>
                                        <span class="card-title">{{ $post->title }}</span>
                                        @if ($post->excerpt)<span class="card-summary">{{ $post->excerpt }}</span>@endif
                                    </div>
                                    <div class="card-img">{!! lazy_img($post, 'cover', [1000, 500]) !!}</div>
                                </div>
                            </a>
                        </li>
                    @else
                        @include('site.partials.post-card', ['post' => $post])
                    @endif
                @endforeach
            </ul>

            @if ($posts->lastPage() > 1)
                <div class="pagination-container">
                    <nav role="navigation" aria-label="Pagination Navigation">
                        <ul class="pagination-list-wrap">
                            <li>
                                <a class="circle-arrow-btn {{ $posts->onFirstPage() ? 'disabled' : '' }}" href="{{ $posts->onFirstPage() ? '#' : $pageUrl($posts->currentPage() - 1) }}" aria-label="{{ __('site.careers.previous') }}">
                                    <span style="display:inline-flex;transform:rotate(180deg)">@include('site.svg.arrow-small')</span>
                                </a>
                            </li>
                            <li class="page-numbers">{{ __('site.blog.page_of', ['current' => $posts->currentPage(), 'total' => $posts->lastPage()]) }}</li>
                            <li>
                                <a class="circle-arrow-btn {{ $posts->hasMorePages() ? '' : 'disabled' }}" href="{{ $posts->hasMorePages() ? $pageUrl($posts->currentPage() + 1) : '#' }}" aria-label="{{ __('site.careers.next') }}">
                                    @include('site.svg.arrow-small')
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection
