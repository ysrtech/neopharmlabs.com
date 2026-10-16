<li class="presenter-item">
    @if ($block->hasImage('image'))
        <div class="image-profile">{!! lazy_img($block, 'image', [400]) !!}</div>
    @endif
    <p class="presenter-title h5 mt-4 mb-0">{{ bt($block, 'name') }}@if (bt($block, 'role'))<span class="job-title">{{ bt($block, 'role') }}</span>@endif</p>
    @if (bt($block, 'bio'))
        <div class="description">{!! bt($block, 'bio') !!}</div>
    @endif
</li>
