<?php

namespace App\Http\Controllers\Site;

use App\Models\JobPosting;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Resource;
use App\Models\Service;
use App\Models\TeamMember;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $urls = [];
        foreach (array_keys(config('site.locales')) as $locale) {
            app()->setLocale($locale);
            foreach (['home', 'services.index', 'blog.index', 'resources.index', 'jobs.index'] as $route) {
                $urls[] = lroute($route);
            }
            $add = function (string $class, string $route, string $param = 'slug') use (&$urls, $locale) {
                $class::query()->published()->with('slugs')
                    ->when($class === Post::class, fn ($q) => $q->where('legacy', false))
                    ->get()->each(function ($item) use (&$urls, $route, $param, $locale) {
                        if ($slug = optional($item->getActiveSlug($locale))->slug) {
                            if ($item instanceof Page && $item->key === 'home') {
                                return;
                            }
                            $urls[] = lroute($route, [$param => $slug]);
                        }
                    });
            };
            $add(Page::class, 'pages.show');
            $add(Service::class, 'services.show');
            $add(Post::class, 'blog.show');
            $add(PostCategory::class, 'blog.category');
            $add(Resource::class, 'resources.show');
            $add(TeamMember::class, 'team.show');
            $add(JobPosting::class, 'jobs.show', 'reference');
        }

        return response()->view('site.sitemap', ['urls' => array_unique($urls)])->header('Content-Type', 'application/xml');
    }
}
