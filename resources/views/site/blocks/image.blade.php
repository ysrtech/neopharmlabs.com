@if ($block->hasImage('image'))
    <figure class="marginTopBottom-S">
        {!! lazy_img($block, 'image', [1400, 1000, 500]) !!}
        @if (bt($block, 'caption'))
            <figcaption>{{ bt($block, 'caption') }}</figcaption>
        @endif
    </figure>
@endif
