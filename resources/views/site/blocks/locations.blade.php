<div class="facility-locations-container-wrapper">
    <div class="wrap">
        <ul class="facility-locations-grid">
            @foreach ($block->children->where('type', 'location')->sortBy('position') as $location)
                <li class="grid-item">
                    <div class="location-card card">
                        <div class="card-inner">
                            <div class="content-side">
                                <p class="location-descriptor">{{ bt($location, 'descriptor') }}</p>
                                <h3 class="location-title h3">{!! strip_tags(bt($location, 'title_html'), '<br><b><strong>') !!}</h3>
                                @if (bt($location, 'phone'))<p class="phone"><a href="tel:{{ preg_replace('/[^0-9+]/', '', bt($location, 'phone')) }}">{{ bt($location, 'phone') }}</a></p>@endif
                                @if (bt($location, 'email'))<p class="email"><a href="mailto:{{ bt($location, 'email') }}">{{ bt($location, 'email') }}</a></p>@endif
                                @if (bt($location, 'address'))
                                    <p class="address">
                                        @if (bt($location, 'map_url'))
                                            <a href="{{ bt($location, 'map_url') }}" target="_blank" rel="noopener">{!! nl2br(e(bt($location, 'address'))) !!}</a>
                                        @else
                                            {!! nl2br(e(bt($location, 'address'))) !!}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="visual">{!! lazy_img($location, 'image', [500]) !!}</div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
