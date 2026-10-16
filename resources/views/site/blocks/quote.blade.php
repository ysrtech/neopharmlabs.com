<div class="marginTopBottom-S">
    <blockquote class="quote text-xl font-regular">
        {!! nl2br(e(bt($block, 'quote'))) !!}
        @if (bt($block, 'author') || bt($block, 'role'))
            <cite>
                @if (bt($block, 'author'))<span class="color-black text-base font-semibold">&mdash; {{ bt($block, 'author') }}</span>@endif
                @if (bt($block, 'role'))<span class="color-gray text-base font-regular">{{ bt($block, 'role') }}</span>@endif
            </cite>
        @endif
    </blockquote>
</div>
