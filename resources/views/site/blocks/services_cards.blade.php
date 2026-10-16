@php($cardServices = \App\Models\Service::query()->published()->topLevel()->orderBy('position')->get()->filter(fn ($s) => $s->hasTranslation(app()->getLocale())))
<div class="wrap">
    <ul class="services-stack-homepage">
        <li class="grid-item">
            <a class="card service-card primary" href="{{ lroute('services.index') }}">
                <div class="card-inner">
                    <div class="card-header"><div class="card-top-icon">@include('site.svg.explore-all')</div></div>
                    <div class="card-footer">
                        <div class="card-info"><div class="card-title bigger color-white">{{ bt($block, 'all_label', __('site.services.explore_all')) }}</div></div>
                        <div class="arrow-circle white">@include('site.svg.arrow-card')</div>
                    </div>
                </div>
            </a>
        </li>
        @foreach ($cardServices as $cardService)
            <li class="grid-item">
                <a class="card service-card" href="{{ lroute('services.show', ['slug' => $cardService->getSlug()]) }}">
                    <div class="card-inner">
                        <div class="card-header"><div class="card-top-icon">{!! $cardService->icon_svg !!}</div></div>
                        <div class="card-footer">
                            <div class="card-info"><div class="card-title bigger">{{ $cardService->title }}</div></div>
                            <div class="arrow-circle">@include('site.svg.arrow-card')</div>
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>
