@php
    $style = bt($block, 'style', 'bg-light');
    $boxClass = $style === 'no-box' ? 'no-box' : 'section-box ' . $style . (in_array($style, ['bg-primary', 'bg-midnight', 'bg-dark']) ? ' color-white' : '');
    $layout = bt($block, 'layout', 'fullHeight');
    $reverse = (bool) bt($block, 'reverse');
    $button = ['url' => bt($block, 'button_url'), 'label' => bt($block, 'button_label'), 'style' => bt($block, 'button_style', 'primary')];
@endphp
<div class="image-text-block marginTopBottom-M animateOnScroll {{ $boxClass }}">
    <div class="two-column-grid {{ $reverse ? 'reverse' : '' }} {{ $layout }}">
        <div class="column content">
            @if (bt($block, 'label'))
                <h2 class="section-label">{{ bt($block, 'label') }}</h2>
            @endif
            <div class="rich-text">{!! bt($block, 'html') !!}</div>
            @if ($button['label'])
                <div class="button-wrapper-desktop">@include('site.partials.button', $button)</div>
            @endif
        </div>
        <div class="column visual">
            <div class="visual-container {{ $layout }}">{!! lazy_img($block, 'image', [1000, 500]) !!}</div>
        </div>
        @if ($button['label'])
            <div class="button-wrapper-mobile">@include('site.partials.button', $button)</div>
        @endif
    </div>
</div>
