<?php

namespace App\Http\Controllers\Site;

use App\Models\Resource;

class ResourceController extends Controller
{
    public function index(?string $type = null)
    {
        $resources = Resource::query()->published()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->whereHas('translations', fn ($q) => $q->where('locale', app()->getLocale())->where('active', true))
            ->with('medias')
            ->orderByDesc('published_at')->orderByDesc('id')->get();

        return view('site.resources.index', [
            'resources' => $resources,
            'type' => $type,
            'seo' => [
                'title' => ($type ? Resource::TYPES[$type][app()->getLocale() === 'fr' ? 1 : 0] . ' | ' : '') . strip_tags(__('site.resources.title')) . ' | Neopharm',
                'description' => null,
            ],
            'alternates' => $type ? $this->staticAlternates('resources.type', ['type' => $type]) : $this->staticAlternates('resources.index'),
        ]);
    }

    public function show(string $slug)
    {
        $resource = $this->findBySlug(Resource::class, $slug);
        if ($resource->getSlug() !== $slug) {
            return redirect(lroute('resources.show', ['slug' => $resource->getSlug()]), 301);
        }

        return view('site.resources.show', [
            'resource' => $resource,
            'seo' => $this->seo($resource, $resource->title, strip_tags((string) $resource->intro)),
            'alternates' => $this->alternates($resource, 'resources.show'),
            'bodyClass' => 'bg-white',
        ]);
    }
}
