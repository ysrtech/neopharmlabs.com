@php
    $style = bt($block, 'style', 'primary');
    $boxClass = ['primary' => 'bg-primary color-white', 'dark' => 'bg-dark color-white', 'light' => 'bg-light'][$style] ?? 'bg-primary color-white';
    $buttonStyle = $style === 'primary' ? 'white' : 'primary';
@endphp
<div class="call-to-action-box marginTopBottom-S animateOnScroll center {{ $boxClass }}">
    {!! bt($block, 'html') !!}
    @include('site.partials.button', ['url' => bt($block, 'button_url'), 'label' => bt($block, 'button_label'), 'style' => $buttonStyle])
</div>
