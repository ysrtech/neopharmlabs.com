{{-- Arrow button. Params: $url, $label, $style (primary|white|border), $class --}}
@if (filled($label ?? null))
    <a class="btn arrow {{ $style ?? 'primary' }} {{ $class ?? '' }}" href="{{ local_url($url ?? '#') }}">
        {{ $label }}
        <div class="circle-arrow">
            <span class="circle"></span>
            @include('site.svg.arrow-black')
        </div>
    </a>
@endif
