<?php

namespace App\Http\Controllers\Site;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = Service::query()->published()->topLevel()->orderBy('position')->get();
        $services = Service::query()->published()->whereNotNull('parent_id')
            ->with('parent')->orderBy('position')->get()
            ->sortBy(fn (Service $s) => [optional($s->parent)->position, $s->position])->values();

        return view('site.services.index', [
            'categories' => $categories,
            'services' => $services,
            'seo' => ['title' => __('site.menu.services') . ' | Neopharm', 'description' => __('site.leadgen.text')],
            'alternates' => $this->staticAlternates('services.index'),
        ]);
    }

    public function show(string $slug)
    {
        $service = $this->findBySlug(Service::class, $slug);
        if ($service->getSlug() !== $slug) {
            return redirect(lroute('services.show', ['slug' => $service->getSlug()]), 301);
        }

        $category = $service->category();
        $siblings = Service::query()->published()->where('parent_id', $category->id)->orderBy('position')->get();

        return view('site.services.show', [
            'service' => $service,
            'category' => $category,
            'related' => $siblings,
            'seo' => $this->seo($service, $service->title, strip_tags((string) $service->hero_intro)),
            'alternates' => $this->alternates($service, 'services.show'),
            'bodyClass' => 'bg-white',
        ]);
    }
}
