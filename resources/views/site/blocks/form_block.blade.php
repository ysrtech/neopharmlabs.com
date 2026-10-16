<div class="form-block marginTopBottom-M animateOnScroll no-box" id="form">
    <div class="sideBySide">
        <div class="text">
            @if (bt($block, 'label'))<h2 class="section-label">{{ bt($block, 'label') }}</h2>@endif
            <div class="rich-text">{!! bt($block, 'html') !!}</div>
        </div>
        <div class="form">
            @include('site.forms.contact', ['form' => bt($block, 'form', 'privacy_request'), 'white' => false])
        </div>
    </div>
</div>
