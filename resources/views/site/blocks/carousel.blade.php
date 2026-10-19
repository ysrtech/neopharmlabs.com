<div class="slider-carousel-component carousel-component marginTopBottom-L animateOnScroll">
    <div class="carousel swiper">
        <ul class="swiper-wrapper">
            @foreach ($block->imagesAsArrays('images') as $index => $carouselImage)
                <li class="grid-item swiper-slide">
                    <img class="lozad" sizes="100vw"
                         src="{{ $block->images('images', 'default', ['w' => 40, 'blur' => 5, 'fm' => 'jpg'])[$index] ?? '' }}"
                         data-srcset="{{ $block->images('images', 'default', ['w' => 900, 'fm' => 'jpg', 'q' => 80])[$index] ?? '' }} 900w, {{ $block->images('images', 'default', ['w' => 500, 'fm' => 'jpg', 'q' => 80])[$index] ?? '' }} 500w"
                         alt="{{ $carouselImage['alt'] ?? '' }}">
                </li>
            @endforeach
        </ul>
        @include('site.partials.swiper-arrows')
    </div>
</div>
