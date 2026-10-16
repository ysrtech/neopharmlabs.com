<li class="grid-item swiper-slide">
    <a class="resource-card blog-card" href="{{ lroute('blog.show', ['slug' => $post->getSlug()]) }}">
        <div class="card-inner">
            <div class="card-top">
                <div class="card-header">
                    @if ($post->category)<div class="card-category op-50">{{ $post->category->title }}</div>@endif
                    @if ($post->published_at)<span class="card-date">{{ $post->published_at->locale(app()->getLocale())->translatedFormat(__('site.blog.date_format')) }}</span>@endif
                </div>
                <span class="card-title">{{ $post->title }}</span>
            </div>
            <div class="card-img">{!! lazy_img($post, 'cover', [500]) !!}</div>
        </div>
    </a>
</li>
