<?php

namespace App\Http\Controllers\Site;

use App\Models\Post;
use App\Models\Resource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', $request->query('search', '')));
        $results = collect();

        if (mb_strlen($q) >= 2) {
            $results = $this->search(Service::class, $q, ['title', 'hero_title', 'hero_intro'], fn ($s) => [
                'type' => __('site.menu.services'), 'title' => $s->title, 'text' => strip_tags((string) $s->hero_intro),
                'url' => lroute('services.show', ['slug' => $s->getSlug()]),
            ])->merge($this->search(Post::class, $q, ['title', 'excerpt'], fn ($p) => [
                'type' => __('site.menu.blog'), 'title' => $p->title, 'text' => $p->excerpt,
                'url' => lroute('blog.show', ['slug' => $p->getSlug()]),
            ]))->merge($this->search(Resource::class, $q, ['title', 'intro', 'body'], fn ($r) => [
                'type' => $r->typeLabel(), 'title' => $r->title, 'text' => strip_tags((string) $r->intro),
                'url' => lroute('resources.show', ['slug' => $r->getSlug()]),
            ]));
        }

        return view('site.search', [
            'q' => $q,
            'results' => $results,
            'suggested' => Service::query()->published()->topLevel()->orderBy('position')->get(),
            'seo' => ['title' => __('site.search') . ' | Neopharm', 'description' => null],
            'alternates' => $this->staticAlternates('search'),
        ]);
    }

    protected function search(string $class, string $q, array $fields, callable $map): Collection
    {
        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';

        return $class::query()->published()
            ->whereHas('translations', function ($t) use ($fields, $like) {
                $t->where('locale', app()->getLocale())->where('active', true)
                    ->where(fn ($w) => collect($fields)->each(fn ($f) => $w->orWhere($f, 'like', $like)));
            })
            ->when($class === Post::class, fn ($qq) => $qq->where('legacy', false))
            ->take(30)->get()->map($map);
    }
}
