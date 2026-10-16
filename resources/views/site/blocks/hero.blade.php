@php
    $variant = bt($block, 'variant', 'default');
    $title = bt($block, 'title_html');
    $text = bt($block, 'text_html');
    $buttonLabel = bt($block, 'button_label');
    $buttonUrl = bt($block, 'button_url');
@endphp
@switch($variant)
    @case('home')
        <div class="hero_container bg-dark rounded-bottom bg-img-overlay image-fullwidth">
            <div class="featured-image">{!! lazy_img($block, 'image', [1600, 1000, 500]) !!}</div>
            <div class="wrap">
                <div class="hero hero-homepage-grid">
                    <div class="content color-white">
                        <h1 class="h1 color-white">{!! strip_tags($title, '<b><strong><em><i><br>') !!}</h1>
                        {!! str_starts_with(trim((string) $text), '<') ? $text : '<p>' . $text . '</p>' !!}
                        @include('site.partials.button', ['url' => $buttonUrl, 'label' => $buttonLabel, 'style' => 'primary'])
                    </div>
                    @if (bt($block, 'video_url'))
                        <div class="visual">
                            <div class="video-visual">
                                <a class="video-pop-link" data-featherlight="#video-{{ $block->id }}" data-featherlight-persist="shared" href="#">
                                    <div class="cs-play-video-button">
                                        @include('site.svg.play-video')
                                        <p class="cs-play-video-text">{{ bt($block, 'video_label') }}</p>
                                    </div>
                                </a>
                                <div style="display: none;">
                                    <div id="video-{{ $block->id }}">
                                        <iframe class="lozad" data-src="{{ bt($block, 'video_url') }}" width="600" height="508" frameborder="0" loading="lazy" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @break

    @case('contact')
        <div class="hero_container rounded-bottom">
            <div class="hero marginTopBottom-S">
                <div class="wrap">
                    <div class="thin-content-side animated fadeInUp delay-100">
                        <h1 class="h1 color-white">{!! strip_tags($title, '<b><strong><em><i><br>') !!}</h1>
                        <div class="color-white">{!! str_starts_with(trim((string) $text), '<') ? $text : '<p class="color-white">' . $text . '</p>' !!}</div>
                        @include('site.forms.contact', ['form' => 'contact', 'white' => true])
                    </div>
                </div>
            </div>
            <div class="world_image-container">{!! lazy_img($block, 'image', [2000, 1600, 1000, 500]) !!}</div>
        </div>
        @break

    @case('legal')
        <div class="hero_container bg-light rounded-bottom">
            <div class="hero">
                <div class="wrap wrap-S center">
                    <h1 class="h2">{!! strip_tags($title, '<b><strong><em><i><br>') !!}</h1>
                    @if ($text){!! $text !!}@endif
                </div>
            </div>
        </div>
        @break

    @case('careers')
        <div class="hero_container careersPage">
            <div class="hero marginTopBottom-S">
                <div class="wrap wrap-M center">
                    <div class="content animated fadeInUp delay-100">
                        <h1 class="h1">{!! strip_tags($title, '<b><strong><em><i><br>') !!}</h1>
                        {!! str_starts_with(trim((string) $text), '<') ? $text : '<p>' . $text . '</p>' !!}
                        @include('site.partials.button', ['url' => $buttonUrl, 'label' => $buttonLabel, 'style' => 'primary'])
                    </div>
                </div>
            </div>
        </div>
        @break

    @default
        {{-- "about" and generic pages --}}
        <div class="hero_container aboutUsPage">
            <div class="hero marginTopBottom-S">
                <div class="wrap">
                    <div class="content animated fadeInUp delay-100">
                        <h1 class="h1">{!! strip_tags($title, '<b><strong><em><i><br>') !!}</h1>
                        @if ($text)
                            <div class="two-col-grid">{!! str_starts_with(trim((string) $text), '<') ? $text : '<p>' . $text . '</p>' !!}</div>
                        @endif
                        @include('site.partials.button', ['url' => $buttonUrl, 'label' => $buttonLabel, 'style' => 'primary'])
                    </div>
                </div>
            </div>
        </div>
@endswitch
