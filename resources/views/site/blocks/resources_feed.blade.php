@php
    $locale = app()->getLocale();
    $feedPosts = \App\Models\Post::query()->published()->where('legacy', false)
        ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('active', true))
        ->with('category')->orderByDesc('published_at')->take((int) (bt($block, 'post_count') ?: 6))->get();
    $feedResources = \App\Models\Resource::query()->published()
        ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('active', true))
        ->orderByDesc('published_at')->take(2)->get();
    $button = ['url' => bt($block, 'button_url', lroute('resources.index')), 'label' => bt($block, 'button_label'), 'style' => 'primary'];
@endphp
<div class="resources-posts-block marginTopBottom-M animateOnScroll bg-light section-box">
    <div class="two-column-header">
        <div class="column rich-text">{!! bt($block, 'html') !!}</div>
        <div class="column">
            @if (bt($block, 'text'))
                <div class="right-side-text"><p class="p1">{{ bt($block, 'text') }}</p></div>
            @endif
            <div class="button-wrapper-desktop">@include('site.partials.button', $button)</div>
        </div>
    </div>
    <div class="slider-resources-component">
        <div class="swiper">
            <ul class="swiper-wrapper resources-posts-grid">
                @foreach ($feedPosts as $post)
                    @include('site.partials.post-card', ['post' => $post])
                @endforeach
                @foreach ($feedResources as $resource)
                    @include('site.partials.resource-card', ['resource' => $resource])
                @endforeach
            </ul>
            <div class="swiper-pagination"></div>
            @include('site.partials.swiper-arrows')
        </div>
    </div>
    <div class="button-wrapper-mobile">@include('site.partials.button', $button)</div>
</div>
