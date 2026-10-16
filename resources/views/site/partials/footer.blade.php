<footer class="footer" role="contentinfo">
    <div class="footer-gradient"></div>
    <div class="footer-background">
        <img class="lozad" src="/assets/img/site/footer-image-neopharm-dna.jpg" alt="">
    </div>

    <div class="wrap">
        <div class="footer-newsletter" id="mc_embed_signup_scroll">
            <div class="flex-50 half-40-60">
                <div class="flex-child mb-1">
                    <span class="h1 color-white">{{ __('site.footer.newsletter_title') }}</span>
                </div>
                <div class="flex-child">
                    <p class="color-white">{{ __('site.footer.newsletter_text') }}</p>
                    <form action="{{ config('site.newsletter_action') }}" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_self">
                        <div class="newsletter-form-layout">
                            <div class="flex-parent">
                                <div class="newsletter-field">
                                    <input name="EMAIL" value="" class="text-input" required placeholder="{{ __('site.footer.email_placeholder') }}" type="email" id="mce-EMAIL">
                                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="{{ config('site.newsletter_honeypot') }}" tabindex="-1" value=""></div>
                                    <button type="submit" class="btn-arrow" aria-label="Subscribe">@include('site.svg.newsletter-submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer-mega-menu">
            <div class="footer-left-side white">
                <div class="logo">
                    <a href="{{ lroute('home') }}" class="site-logo-link">@include('site.svg.logo')</a>
                </div>
                <a class="text-xl color-white font-medium contact-link hover-line" href="mailto:{{ config('site.contact.email') }}">{{ config('site.contact.email') }}</a>
                <a class="text-xl color-white font-medium contact-link hover-line" href="tel:{{ config('site.contact.phone_link') }}">{{ config('site.contact.phone') }}</a>
                <ul class="social-bubbles">
                    <li class="social-circle white"><a href="{{ config('site.contact.linkedin') }}" aria-label="LinkedIn">@include('site.svg.linkedin')</a></li>
                    <li class="social-circle white"><a href="{{ config('site.contact.youtube') }}" aria-label="YouTube">@include('site.svg.youtube')</a></li>
                </ul>
            </div>

            <div class="footer-right-side">
                <div class="col">
                    <span class="h4 color-white">{{ __('site.menu.services') }}</span>
                    <ul>
                        <li><a class="list-link hover-line" href="{{ lroute('services.index') }}">{{ __('site.menu.view_all_services') }}</a></li>
                        @foreach ($navServices as $navService)
                            <li><a class="list-link hover-line" href="{{ lroute('services.show', ['slug' => $navService->getSlug()]) }}">{{ $navService->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col">
                    <span class="h4 color-white">{{ __('site.menu.resources') }}</span>
                    <ul>
                        @foreach (\App\Models\Resource::TYPES as $typeKey => $typeLabels)
                            <li><a class="list-link hover-line" href="{{ lroute('resources.type', ['type' => $typeKey]) }}">{{ $typeLabels[app()->getLocale() === 'fr' ? 1 : 0] }}</a></li>
                        @endforeach
                        <li><a class="list-link hover-line" href="{{ lroute('blog.index') }}">{{ __('site.menu.blog') }}</a></li>
                        <li><a class="list-link hover-line" href="{{ lroute('resources.index') }}">{{ __('site.menu.view_all_resources') }}</a></li>
                    </ul>
                </div>
                <div class="col">
                    <span class="h4 color-white">{{ __('site.menu.company') }}</span>
                    <ul>
                        <li><a class="list-link hover-line" href="{{ page_url('about') }}">{{ __('site.menu.about') }}</a></li>
                        <li><a class="list-link hover-line" href="{{ page_url('contact') }}">{{ __('site.contact_us') }}</a></li>
                        <li><a class="list-link hover-line" href="{{ page_url('careers') }}">{{ __('site.menu.careers') }}</a></li>
                        <li><a class="list-link hover-line" href="{{ lroute('blog.category', ['slug' => 'newsroom']) }}">{{ __('site.menu.newsroom') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom-row cf">
            <div class="left">
                <p class="copyright">{{ date('Y') }} &copy; Neopharm Labs . {{ __('site.footer.rights') }} . <a href="{{ page_url('legal') }}">{{ __('site.footer.conditions') }}</a></p>
            </div>
            <div class="right">
                <ul>
                    <li class="designer_credit">
                        <a href="https://www.capp.studio" target="_blank" rel="noopener"><p>{{ __('site.footer.credit') }} <span class="companyText"><b>{{ __('site.footer.credit_name') }}</b></span></p>
                            @include('site.svg.capp-logo')
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
