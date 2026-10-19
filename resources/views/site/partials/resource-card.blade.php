<li class="grid-item swiper-slide {{ $class ?? '' }}">
    <a class="resource-card" href="{{ lroute('resources.show', ['slug' => $resource->getSlug()]) }}">
        <div class="card-inner">
            <div class="card-top">
                <div class="card-header">
                    <div class="card-category">{{ $resource->typeLabel() }}</div>
                    <div class="card-color-dot"></div>
                </div>
                <span class="card-title">{{ $resource->title }}</span>
            </div>
            <div class="card-img">{!! lazy_img($resource, 'cover', [500]) !!}</div>
        </div>
    </a>
</li>
