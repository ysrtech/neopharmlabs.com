<?php

namespace App\Http\Controllers\Site;

use App\Models\Page;
use App\Models\TeamMember;

class PageController extends Controller
{
    public function home()
    {
        $page = Page::query()->published()->where('key', 'home')->firstOrFail();

        return view('site.page', [
            'page' => $page,
            'seo' => $this->seo($page),
            'alternates' => $this->staticAlternates('home'),
            'bodyClass' => 'bg-white',
        ]);
    }

    public function show(string $slug)
    {
        $page = $this->findBySlug(Page::class, $slug);

        if ($page->key === 'home') {
            return redirect(lroute('home'), 301);
        }
        if ($page->getSlug() !== $slug) {
            return redirect(lroute('pages.show', ['slug' => $page->getSlug()]), 301);
        }

        return view('site.page', [
            'page' => $page,
            'seo' => $this->seo($page),
            'alternates' => $this->alternates($page, 'pages.show'),
            'bodyClass' => 'bg-white',
        ]);
    }

    public function teamMember(string $slug)
    {
        $member = $this->findBySlug(TeamMember::class, $slug);

        return view('site.team-member', [
            'member' => $member,
            'seo' => $this->seo($member, $member->name . ' - ' . $member->role, strip_tags($member->lead)),
            'alternates' => $this->alternates($member, 'team.show'),
        ]);
    }
}
