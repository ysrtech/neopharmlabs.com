@php($alternates = $alternates ?? [])
<header class="page-header-container {{ $headerClass ?? '' }}">
    <div class="nav-top-row">
        <div class="wrap">
            <div class="nav-top-row-inner">
                <div class="left">
                    <div class="announcement-line">
                        <p>{!! __('site.announcement', ['url' => page_url('careers')]) !!}</p>
                    </div>
                </div>
                <ul class="right">
                    <li>
                        <a class="nav-link" href="{{ lroute('search') }}">{{ __('site.search') }} @include('site.svg.search')</a>
                    </li>
                    <li><a class="nav-link" href="{{ page_url('contact') }}">{{ __('site.contact_us') }}</a></li>
                    <li>@include('site.partials.lang-switch')</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="wrap inner-header">
        <div class="logo headerLogoSize">
            <a href="{{ lroute('home') }}" class="site-logo-link">@include('site.svg.logo')</a>
        </div>
        <nav class="main-navigation">
            <span class="nav-trigger"><span><em aria-hidden="true"></em></span></span>
            <ul id="navigation">
                <div class="navigation-split">
                    <div class="left-nav">
                        <li>
                            <span class="nav-link hasMegamenu">{{ __('site.menu.services') }} @include('site.svg.chevron-down')</span>
                            <div class="megamenu">
                                <div class="megamenu-grid-wrapper">
                                    <div class="megamenu-column column-25 only-medium-screen megamenu-intro">
                                        <p class="text-2xl font-medium">{{ __('site.menu.services_intro') }}</p>
                                        <a class="btn arrow primary" href="{{ lroute('services.index') }}">
                                            {{ __('site.menu.explore_services') }}
                                            <div class="circle-arrow"><span class="circle"></span>@include('site.svg.arrow-black')</div>
                                        </a>
                                    </div>
                                    <div class="megamenu-column column-50 flex-expand">
                                        <ul class="list-border-arrow">
                                            @foreach ($navServices as $navService)
                                                <li class="list-item-border-arrow">
                                                    <a class="nav-sub-link" href="{{ lroute('services.show', ['slug' => $navService->getSlug()]) }}">
                                                        <div class="service-icon">{!! $navService->icon_svg !!}</div>
                                                        <span class="item-title">{{ $navService->title }}</span>
                                                        <div class="circle-arrow-btn">@include('site.svg.arrow-small')</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                            <li class="list-item-border-arrow">
                                                <a class="nav-sub-link primary" href="{{ lroute('services.index') }}">
                                                    <div class="service-icon">@include('site.svg.explore-all')</div>
                                                    <span class="item-title">{{ __('site.menu.view_all_services') }}</span>
                                                    <div class="circle-arrow-btn primary">@include('site.svg.arrow-small')</div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="megamenu-column column-25 only-big-screen">
                                        <span class="text-s font-medium opacity-50">{{ __('site.menu.filter_by') }}</span>
                                        <ul class="list-border-arrow">
                                            @foreach (__('site.menu.filters') as $filterLabel)
                                                <li class="list-item-border-arrow">
                                                    <a class="nav-sub-link" href="{{ lroute('services.index') }}#filters">
                                                        <span class="item-title">{{ $filterLabel }}</span>
                                                        <div class="circle-arrow-btn">@include('site.svg.arrow-small')</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <span class="nav-link hasMegamenu">{{ __('site.menu.resources') }} @include('site.svg.chevron-down')</span>
                            <div class="megamenu">
                                <div class="megamenu-grid-wrapper">
                                    <div class="megamenu-column column-25 only-medium-screen megamenu-intro">
                                        <p class="text-2xl font-medium">{{ __('site.menu.resources_intro') }}</p>
                                        <a class="btn arrow primary" href="{{ lroute('resources.index') }}">
                                            {{ __('site.menu.view_all_resources') }}
                                            <div class="circle-arrow"><span class="circle"></span>@include('site.svg.arrow-black')</div>
                                        </a>
                                    </div>
                                    <div class="megamenu-column column-25 flex-expand">
                                        <ul class="list-border-arrow">
                                            @foreach (\App\Models\Resource::TYPES as $typeKey => $typeLabels)
                                                <li class="list-item-border-arrow">
                                                    <a class="nav-sub-link" href="{{ lroute('resources.type', ['type' => $typeKey]) }}">
                                                        <span class="item-title">{{ $typeLabels[app()->getLocale() === 'fr' ? 1 : 0] }}</span>
                                                        <div class="circle-arrow-btn">@include('site.svg.arrow-small')</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                            <li class="list-item-border-arrow">
                                                <a class="nav-sub-link" href="{{ lroute('blog.index') }}">
                                                    <span class="item-title">{{ __('site.menu.blog') }}</span>
                                                    <div class="circle-arrow-btn">@include('site.svg.arrow-small')</div>
                                                </a>
                                            </li>
                                            <li class="list-item-border-arrow">
                                                <a class="nav-sub-link primary" href="{{ lroute('resources.index') }}">
                                                    <span class="item-title">{{ __('site.menu.view_all_resources') }}</span>
                                                    <div class="circle-arrow-btn primary">@include('site.svg.arrow-small')</div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="megamenu-column column-50 only-big-screen">
                                        @if ($navResource)
                                            <ul class="double-card-layout flex-50">
                                                <li class="flex-child">
                                                    <a href="{{ lroute('resources.show', ['slug' => $navResource->getSlug()]) }}" class="resource-card light">
                                                        <div class="card-inner">
                                                            <div class="card-top">
                                                                <div class="card-header">
                                                                    <div class="card-category">{{ $navResource->typeLabel() }}</div>
                                                                    <div class="card-color-dot"></div>
                                                                </div>
                                                                <span class="card-title">{{ $navResource->title }}</span>
                                                            </div>
                                                            <div class="card-img">{!! lazy_img($navResource, 'cover', [500]) !!}</div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <span class="nav-link hasMegamenu">{{ __('site.menu.company') }} @include('site.svg.chevron-down')</span>
                            <div class="megamenu">
                                <div class="megamenu-grid-wrapper">
                                    <div class="megamenu-column column-25 only-medium-screen megamenu-intro">
                                        <p class="text-2xl font-medium">{{ __('site.menu.company_intro') }}</p>
                                        <a class="btn arrow primary" href="{{ page_url('contact') }}">
                                            {{ __('site.contact_us') }}
                                            <div class="circle-arrow"><span class="circle"></span>@include('site.svg.arrow-black')</div>
                                        </a>
                                    </div>
                                    <div class="megamenu-column column-25 flex-expand">
                                        <ul class="list-border-arrow">
                                            @foreach ([
                                                [page_url('about'), __('site.menu.about')],
                                                [page_url('careers'), __('site.menu.careers')],
                                                [page_url('contact'), __('site.contact_us')],
                                                [lroute('blog.category', ['slug' => 'newsroom']), __('site.menu.newsroom')],
                                            ] as [$linkUrl, $linkLabel])
                                                <li class="list-item-border-arrow">
                                                    <a class="nav-sub-link" href="{{ $linkUrl }}">
                                                        <span class="item-title">{{ $linkLabel }}</span>
                                                        <div class="circle-arrow-btn">@include('site.svg.arrow-small')</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="megamenu-column column-50 only-big-screen">
                                        <ul class="double-card-layout flex-50">
                                            <li class="flex-child">
                                                <a href="{{ page_url('about') }}" class="card bg-image">
                                                    <div class="card-inner">
                                                        <div class="card-header"><span class="card-title">{{ __('site.menu.about') }}</span></div>
                                                        <div class="card-footer"></div>
                                                    </div>
                                                    <div class="card-bg-img"><img class="lozad" src="/assets/img/site/neo-about-us.jpg" alt=""></div>
                                                </a>
                                            </li>
                                            <li class="flex-child">
                                                <a href="{{ page_url('contact') }}" class="card bg-image">
                                                    <div class="card-inner">
                                                        <div class="card-header"><span class="card-title color-white">{{ __('site.contact_us') }}</span></div>
                                                        <div class="card-bg-img"><img class="lozad" src="/assets/img/site/neopharm-contact-world-image-with-pins-2023-07.jpg" alt=""></div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <br>
                        <li class="secondary-links only-mobile"><a class="nav-link" href="{{ page_url('contact') }}">{{ __('site.contact_us') }}</a></li>
                        <li class="secondary-links only-mobile"><a class="nav-link" href="{{ lroute('search') }}">@include('site.svg.search-mobile') {{ __('site.search') }}</a></li>
                        <li class="secondary-links only-mobile">@include('site.partials.lang-switch')</li>
                    </div>
                    <div class="right-nav">
                        <li><a class="nav-cta btn border" href="{{ config('site.contact.neoconnect') }}" target="_blank" rel="noopener">{{ __('site.neoconnect') }}</a></li>
                    </div>
                </div>
            </ul>
        </nav>
    </div>
</header>
