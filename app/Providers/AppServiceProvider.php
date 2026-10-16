<?php

namespace App\Providers;

use A17\Twill\Facades\TwillNavigation;
use A17\Twill\View\Components\Navigation\NavigationLink;
use App\Models\Page;
use App\Models\Resource;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        TwillNavigation::addLink(NavigationLink::make()->forModule('pages'));
        TwillNavigation::addLink(NavigationLink::make()->forModule('services'));
        TwillNavigation::addLink(
            NavigationLink::make()->forModule('posts')->title('Blog')->setChildren([
                NavigationLink::make()->forModule('posts')->title('Posts'),
                NavigationLink::make()->forModule('postCategories')->title('Categories'),
            ])
        );
        TwillNavigation::addLink(NavigationLink::make()->forModule('resources'));
        TwillNavigation::addLink(NavigationLink::make()->forModule('teamMembers')->title('Team'));
        TwillNavigation::addLink(NavigationLink::make()->forModule('jobPostings')->title('Careers'));
        TwillNavigation::addLink(NavigationLink::make()->forModule('submissions')->title('Form submissions'));
        TwillNavigation::addLink(NavigationLink::make()->forModule('redirects'));

        // Header and footer navigation data, loaded once per request.
        View::composer(['site.partials.header', 'site.partials.footer'], function ($view) {
            static $data;
            $data ??= [
                'navServices' => Service::query()->published()->topLevel()->orderBy('position')
                    ->with(['translations', 'slugs'])->get()
                    ->filter(fn ($s) => $s->hasTranslation(app()->getLocale())),
                'navResource' => Resource::query()->published()->orderByDesc('published_at')
                    ->whereHas('translations', fn ($q) => $q->where('locale', app()->getLocale())->where('active', true))
                    ->first(),
            ];
            $view->with($data);
        });
    }
}
