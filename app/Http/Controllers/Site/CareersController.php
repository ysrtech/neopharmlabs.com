<?php

namespace App\Http\Controllers\Site;

use App\Models\JobPosting;

class CareersController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::query()->published()
            ->whereHas('translations', fn ($q) => $q->where('locale', app()->getLocale())->where('active', true))
            ->orderByDesc('posted_at')->get();

        return view('site.careers.jobs', [
            'jobs' => $jobs,
            'seo' => ['title' => __('site.careers.open_positions') . ' | Neopharm', 'description' => __('site.careers.jobs_text')],
            'alternates' => $this->staticAlternates('jobs.index'),
        ]);
    }

    public function show(string $reference)
    {
        $job = $this->findJob($reference);

        return view('site.careers.job', [
            'job' => $job,
            'seo' => ['title' => $job->title . ' | Neopharm', 'description' => $job->excerpt],
            'alternates' => $this->alternates($job, 'jobs.show', 'reference'),
        ]);
    }

    public function apply(string $reference)
    {
        $job = $this->findJob($reference);

        return view('site.careers.apply', [
            'job' => $job,
            'seo' => ['title' => __('site.careers.apply') . ' - ' . $job->title . ' | Neopharm', 'description' => null],
            'alternates' => $this->alternates($job, 'jobs.apply', 'reference'),
        ]);
    }

    public function spontaneous()
    {
        return view('site.careers.apply', [
            'job' => null,
            'seo' => ['title' => __('site.careers.spontaneous') . ' | Neopharm', 'description' => __('site.careers.spontaneous_intro')],
            'alternates' => $this->staticAlternates('jobs.spontaneous'),
        ]);
    }

    protected function findJob(string $reference): JobPosting
    {
        return JobPosting::query()->published()->where('reference', $reference)->firstOrFail();
    }
}
