@php($videoUrl = bt($block, 'url'))
@if ($videoUrl)
    <div class="marginTopBottom-S video-embed">
        <iframe src="{{ $videoUrl }}" width="100%" height="450" frameborder="0" loading="lazy" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
@endif
