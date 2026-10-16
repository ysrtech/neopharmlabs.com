<?php

namespace App\Console\Commands;

use A17\Twill\Models\Block;
use A17\Twill\Models\Media;
use App\Models\JobPosting;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Redirect;
use App\Models\Resource;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Imports the content recovered from archive.org (database/recovered/content.json + legacy.json + images/)
 * into the Twill CMS. Run once on a fresh database: php artisan neopharm:import --fresh
 */
class ImportRecoveredContent extends Command
{
    protected $signature = 'neopharm:import {--fresh : Delete existing CMS content before importing}';

    protected $description = 'Import the content recovered from the Wayback Machine into the CMS';

    protected string $dir;

    protected array $data;

    /** @var array<string, Media> */
    protected array $mediaCache = [];

    /** Block field names declared with :translated="true", per block type. */
    protected array $translatedFields = [];

    protected array $columns = [];

    /** Content key (original EN path) => model, per type. */
    protected array $map = ['services' => [], 'posts' => [], 'categories' => [], 'resources' => [], 'pages' => []];

    protected array $redirects = [];

    protected array $missingImages = [];

    public function handle(): int
    {
        $this->dir = base_path('database/recovered');
        $this->data = json_decode(File::get($this->dir . '/content.json'), true);
        $this->readBlockDefinitions();

        if ($this->option('fresh')) {
            $this->wipe();
        } elseif (Service::query()->exists()) {
            $this->error('Content already exists. Use --fresh to replace it.');

            return self::FAILURE;
        }

        DB::transaction(function () {
            $this->importCategories();
            $this->importServices();
            $this->importPosts();
            $this->importResources();
            $this->importTeam();
            $this->importJobs();
            $this->importPages();
            $this->importLegacy();
            $this->importRedirects();
        });

        $this->table(['Type', 'Count'], [
            ['Pages', Page::count()],
            ['Services', Service::count()],
            ['Posts', Post::count()],
            ['Categories', PostCategory::count()],
            ['Resources', Resource::count()],
            ['Team members', TeamMember::count()],
            ['Job postings', JobPosting::count()],
            ['Redirects', Redirect::count()],
            ['Images', Media::count()],
        ]);
        if ($this->missingImages) {
            $this->warn('Images not found in the recovered set: ' . implode(', ', array_unique($this->missingImages)));
        }

        return self::SUCCESS;
    }

    // ---------------------------------------------------------------- content types

    protected function importCategories(): void
    {
        $order = ['/blog/category/science-corner', '/blog/category/company-updates', '/blog/category/newsroom', '/blog/category/talent-culture'];
        foreach ($this->data['categories'] as $item) {
            $category = $this->createModel(PostCategory::class, [
                'published' => true,
                'position' => array_search($item['key'], $order) === false ? 99 : array_search($item['key'], $order) + 1,
            ]);
            $this->translate($category, $item['translations'], fn ($t) => ['title' => $t['title'], 'heading' => $t['heading'] ?? $t['title']]);
            $this->map['categories'][$item['key']] = $category;
        }
    }

    protected function importServices(): void
    {
        foreach ($this->data['services'] as $item) {
            $isTop = empty($item['parent']);
            $hidden = ! $isTop && $item['index_position'] === null;
            $service = $this->createModel(Service::class, [
                'published' => ! $hidden,
                'position' => $isTop ? ($item['top_level_position'] ?? 0) + 1 : ($item['index_position'] ?? 999) + 1,
                'icon_svg' => $item['icon_svg'] ?: null,
                'taxonomy' => array_values($item['taxonomy'] ?? []),
            ]);
            $this->translate($service, $item['translations'], fn ($t) => [
                'title' => $t['title'],
                'hero_title' => $t['hero_title'] ?? null,
                'hero_intro' => $this->html($t['hero_intro'] ?? null),
                'hero_button_label' => $t['hero_button']['label'] ?? null,
                'hero_button_url' => $this->url($t['hero_button']['url'] ?? null),
                'meta_title' => $t['meta_title'] ?? null,
                'meta_description' => $t['meta_description'] ?? null,
            ]);
            $this->attachMedia($service, 'cover', $item['image'] ?? null);
            $this->createBlocks($service, $item['translations']);
            $this->map['services'][$item['key']] = $service;
        }

        foreach ($this->data['services'] as $item) {
            if (! empty($item['parent']) && isset($this->map['services'][$item['parent']])) {
                $service = $this->map['services'][$item['key']];
                $service->parent_id = $this->map['services'][$item['parent']]->id;
                $service->saveQuietly();
                // Services that were no longer listed on the original site redirect to their category.
                if (! $service->published) {
                    $this->redirectItem($item['translations'], 'service:' . $item['parent']);
                }
            }
        }
    }

    protected function importPosts(): void
    {
        $seen = [];
        foreach ($this->data['posts'] as $item) {
            $enTitle = $item['translations']['en']['title'] ?? $item['key'];
            // The original CMS had two copies of a few posts; keep one and redirect the other.
            if (isset($seen[$enTitle])) {
                $this->redirectItem($item['translations'], 'post:' . $seen[$enTitle]);
                continue;
            }
            $seen[$enTitle] = $item['key'];

            $post = $this->createModel(Post::class, [
                'published' => true,
                'legacy' => false,
                'post_category_id' => optional($this->map['categories'][$item['category']] ?? null)->id,
                'published_at' => $item['date'] ?? null,
                'author_name' => $item['author'] ?? null,
            ]);
            $this->translate($post, $item['translations'], fn ($t) => [
                'title' => $t['title'],
                'excerpt' => $t['excerpt'] ?? null,
                'author_bio' => $t['author_bio'] ?? null,
                'meta_title' => $t['meta_title'] ?? null,
                'meta_description' => $t['meta_description'] ?? null,
            ]);
            $this->attachMedia($post, 'cover', $item['image'] ?? null);
            $this->attachMedia($post, 'author_photo', $item['author_image'] ?? null);
            $this->createBlocks($post, $item['translations']);
            $this->map['posts'][$item['key']] = $post;
        }
    }

    protected function importResources(): void
    {
        // Translations that were listed on the archived resource library pages.
        $listed = [
            '/resources/a-complete-guide-to-impurity-studies-in-drug-development' => ['en', 'fr'],
            '/resources/experimental-polar-surface-area-epsa-poster' => ['en', 'fr'],
            '/resources/epsa-assay-101' => ['en'],
            '/resources/exploring-pharma-industry-trends-in-impurities-management' => ['en'],
            '/fr/ressources/services-we-offer' => ['fr'],
        ];
        $duplicates = [
            '/resources/a-rapid-experimental-polar-surface-area-epsa-assay' => 'resource:/resources/experimental-polar-surface-area-epsa-poster',
            '/resources/virtual-audits-what-do-we-offer' => 'post:/blog/virtual-audits-what-do-we-offer',
            '/resources/virtual-audits-what-do-we-offer-2' => 'post:/blog/virtual-audits-what-do-we-offer',
        ];
        $date = now()->startOfDay();
        foreach ($this->data['resources'] as $index => $item) {
            $locales = $listed[$item['key']] ?? [];
            $resource = $this->createModel(Resource::class, [
                'published' => (bool) $locales,
                'type' => $item['type'],
                // Newest first in the order the archived library listed them.
                'published_at' => $date->copy()->subDays(($order = array_search($item['key'], array_keys($listed))) === false ? 10 + $index : $order),
            ]);
            $this->translate($resource, $item['translations'], fn ($t) => [
                'title' => $t['title'],
                'intro' => $t['intro'] ?? null,
                'body' => $this->html($t['body'] ?? null),
                'button_label' => $t['button_label'] ?? null,
                'form_title' => $t['form_title'] ?? null,
                'presenters_title' => $t['presenters_title'] ?? null,
                'meta_title' => $t['meta_title'] ?? null,
                'meta_description' => $t['meta_description'] ?? null,
            ], fn ($locale) => in_array($locale, $locales));
            $this->attachMedia($resource, 'cover', $item['image'] ?? null);

            // Presenters (webinars) become "presenter" blocks.
            $presenters = [];
            foreach ($item['translations'] as $locale => $t) {
                foreach ($t['presenters'] ?? [] as $i => $p) {
                    $presenters[$i][$locale] = ['type' => 'presenter'] + $p;
                }
            }
            $this->createBlocks($resource, collect(['en', 'fr'])->mapWithKeys(fn ($l) => [$l => ['blocks' => array_values(array_filter(array_map(fn ($p) => $p[$l] ?? null, $presenters)))]])->all());

            $this->map['resources'][$item['key']] = $resource;
            if (isset($duplicates[$item['key']])) {
                $this->redirectItem($item['translations'], $duplicates[$item['key']]);
            }
        }
    }

    protected function importTeam(): void
    {
        foreach ($this->data['team'] as $index => $item) {
            $member = $this->createModel(TeamMember::class, ['published' => true, 'position' => $index + 1]);
            $this->translate($member, $item['translations'], fn ($t) => [
                'name' => $t['name'],
                'role' => $t['role'] ?? null,
                'lead' => $t['lead'] ?? null,
                'bio' => $this->html($t['bio'] ?? null),
                'meta_title' => $t['meta_title'] ?? null,
                'meta_description' => $t['meta_description'] ?? null,
            ]);
            $this->attachMedia($member, 'photo', $item['image'] ?? null);
        }
    }

    protected function importJobs(): void
    {
        foreach ($this->data['jobs'] as $item) {
            $en = $item['translations']['en'] ?? Arr::first($item['translations']);
            $sections = collect($en['sections'] ?? []);
            $about = $sections->first(fn ($s) => Str::contains(Str::lower($s['title']), ['about', 'propos']));
            $requirements = $sections->first(fn ($s) => Str::contains(Str::lower($s['title']), ['responsib', 'exigence', 'requirement']));
            $body = $this->html(trim(preg_replace('#^<div class="richtext">|</div>$#', '', trim($about['html'] ?? ''))));
            // Placeholder postings ("General", no title) are kept as drafts.
            $isPlaceholder = blank($en['title'] ?? null) || blank(strip_tags((string) $body));

            $job = $this->createModel(JobPosting::class, [
                'published' => ! $isPlaceholder,
                'reference' => $item['reference'],
                'department' => $item['department'],
                'location' => $item['location'],
                'schedule' => $item['schedule'],
                'posted_at' => $item['date'] ?? null,
            ]);
            $fields = [
                'title' => $en['title'] ?: 'Job ' . $item['reference'],
                'excerpt' => Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $body))), 240),
                'body' => $body,
                'requirements' => $this->html($requirements['html'] ?? null),
            ];
            // Postings were only published in English; the French site listed the same postings.
            $this->translate($job, ['en' => $fields, 'fr' => $fields], fn ($t) => $t, null, false);
            foreach (['en', 'fr'] as $locale) {
                $this->insertSlug($job, $item['reference'], $locale);
            }
        }
    }

    protected function importPages(): void
    {
        foreach ($this->data['pages'] as $item) {
            if (! array_key_exists($item['key'], Page::TEMPLATES) || $item['key'] === 'default') {
                continue; // listing pages (blog, services, resources, jobs, search) are rendered by their own controllers
            }
            $page = $this->createModel(Page::class, ['published' => true, 'template' => $item['key'], 'key' => $item['key']]);
            $this->translate($page, $item['translations'], fn ($t) => [
                'title' => $t['title'] ?: Str::headline($item['key']),
                'meta_title' => $t['meta_title'] ?? null,
                'meta_description' => $t['meta_description'] ?? null,
            ], null, true, fn ($t, $locale) => $item['key'] === 'home' ? ($locale === 'fr' ? 'accueil' : 'home') : null);
            $og = collect($item['translations'])->pluck('og_image')->filter()->first();
            $this->attachMedia($page, 'og_image', $og);
            $this->createBlocks($page, $item['translations']);
            $this->map['pages'][$item['key']] = $page;
        }
    }

    /** Pages from the older WordPress / 2023 sites: kept as drafts in the CMS, and their URLs redirected. */
    protected function importLegacy(): void
    {
        $legacy = json_decode(File::get($this->dir . '/legacy.json'), true);
        $newsroom = $this->map['categories']['/blog/category/newsroom'] ?? null;
        foreach ($legacy as $item) {
            $this->redirects[] = ['from' => $item['path'], 'target' => $item['target'], 'locale' => $item['locale'], 'title' => 'Old site: ' . $item['title']];
            if ($item['kind'] === 'index' || blank(strip_tags((string) $item['html']))) {
                continue;
            }
            $html = $this->html($item['html']);
            $translations = [$item['locale'] => ['title' => Str::limit($item['title'], 190, ''), 'blocks' => [['type' => 'text', 'html' => $html]]]];

            if ($item['kind'] === 'news') {
                $post = $this->createModel(Post::class, [
                    'published' => false,
                    'legacy' => true,
                    'post_category_id' => $newsroom?->id,
                    'published_at' => $item['date'],
                    'author_name' => 'Neopharm Labs',
                ]);
                $this->translate($post, $translations, fn ($t) => [
                    'title' => $t['title'],
                    'excerpt' => Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($html))), 240),
                ], null, true, fn ($t) => 'legacy-' . Str::slug(basename($item['path'])));
                $this->createBlocks($post, $translations);
            } else {
                $page = $this->createModel(Page::class, ['published' => false, 'template' => 'default', 'key' => 'legacy:' . $item['path']]);
                $this->translate($page, $translations, fn ($t) => ['title' => $t['title']], null, true, fn ($t) => 'legacy-' . Str::slug(trim($item['path'], '/')));
                $this->createBlocks($page, $translations);
            }
        }
    }

    protected function importRedirects(): void
    {
        foreach ($this->redirects as $r) {
            $to = $this->resolveTarget($r['target'], $r['locale']);
            $from = Redirect::normalizePath($r['from']);
            if (! $to || $from === Redirect::normalizePath($to) || Redirect::query()->where('from_path', $from)->exists()) {
                continue;
            }
            Redirect::query()->create([
                'published' => true,
                'title' => Str::limit($r['title'], 190),
                'from_path' => $from,
                'to_path' => $to,
                'status_code' => 301,
            ]);
        }
    }

    // ---------------------------------------------------------------- helpers

    protected function createModel(string $class, array $attributes): Model
    {
        /** @var Model $model */
        $model = new $class;
        $model->forceFill($attributes);
        $model->saveQuietly();

        return $model;
    }

    /**
     * Writes translation rows (and slugs) straight to the tables.
     * $fields maps one source translation to columns; $activeFor optionally limits which locales are active.
     */
    protected function translate(Model $model, array $translations, callable $fields, ?callable $activeFor = null, bool $withSlugs = true, ?callable $slugFor = null): void
    {
        $singular = Str::snake(class_basename($model));
        $table = $singular . '_translations';
        $columns = $this->columns[$table] ??= Schema::getColumnListing($table);
        foreach ($translations as $locale => $t) {
            if (! in_array($locale, ['en', 'fr'])) {
                continue;
            }
            $row = array_intersect_key($fields($t), array_flip($columns));
            DB::table($table)->insert($row + [
                $singular . '_id' => $model->getKey(),
                'locale' => $locale,
                'active' => $activeFor ? $activeFor($locale) : true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if ($withSlugs && method_exists($model, 'slugs')) {
                $slug = ($slugFor ? $slugFor($t, $locale) : null) ?: ($t['slug'] ?? null) ?: Str::slug($row['title'] ?? $row['name'] ?? $model->getKey());
                $this->insertSlug($model, $slug, $locale);
            }
        }
    }

    protected function insertSlug(Model $model, string $slug, string $locale): void
    {
        $singular = Str::snake(class_basename($model));
        $table = $singular . '_slugs';
        $slug = Str::lower(trim($slug, '/'));
        $base = $slug;
        $i = 2;
        while (DB::table($table)->where('slug', $slug)->where('locale', $locale)->exists()) {
            $slug = $base . '-' . $i++;
        }
        DB::table($table)->insert([
            $singular . '_id' => $model->getKey(),
            'slug' => $slug,
            'locale' => $locale,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** Creates the editor blocks from the EN/FR block lists (which share the same structure). */
    protected function createBlocks(Model $model, array $translations): void
    {
        $en = $translations['en']['blocks'] ?? [];
        $fr = $translations['fr']['blocks'] ?? [];
        $count = max(count($en), count($fr));
        for ($i = 0; $i < $count; $i++) {
            $pair = array_filter(['en' => $en[$i] ?? null, 'fr' => $fr[$i] ?? null]);
            if (count($pair) === 2 && $pair['en']['type'] !== $pair['fr']['type']) {
                unset($pair['fr']);
            }
            $this->createBlock($model, $pair, $i + 1);
        }
    }

    protected function createBlock(Model $model, array $pair, int $position, ?Block $parent = null, ?string $childKey = null): Block
    {
        $first = Arr::first($pair);
        $type = $first['type'];
        $source = [];
        foreach ($pair as $locale => $b) {
            if (isset($b['button']) && is_array($b['button'])) {
                $b['button_label'] = $b['button']['label'] ?? null;
                $b['button_url'] = $b['button']['url'] ?? null;
            }
            $source[$locale] = $b;
        }

        $content = [];
        $skip = ['type', 'image', 'images', 'items', 'button'];
        $fields = collect($source)->flatMap(fn ($b) => array_keys($b))->unique()->diff($skip);
        foreach ($fields as $field) {
            if (in_array($field, $this->translatedFields[$type] ?? [])) {
                $content[$field] = collect($source)->map(fn ($b, $locale) => $this->fieldValue($field, $b[$field] ?? null, $locale))->all();
            } else {
                $content[$field] = $this->fieldValue($field, $first[$field] ?? null, 'en');
            }
        }
        if ($type === 'presenter') {
            $content['name'] = $first['name'] ?? null;
        }

        $block = new Block;
        $block->forceFill([
            'blockable_id' => $model->getKey(),
            'blockable_type' => $model->getMorphClass(),
            'position' => $position,
            'content' => $content,
            'type' => $type,
            'child_key' => $childKey,
            'parent_id' => $parent?->id,
            'editor_name' => 'default',
        ])->save();

        if (! empty($first['image'])) {
            $this->attachMedia($block, 'image', $first['image']);
        }
        foreach (array_values($first['images'] ?? []) as $i => $image) {
            $this->attachMedia($block, 'images', $image, $i + 1);
        }

        $childType = ['faq' => 'faq_item', 'locations' => 'location'][$type] ?? null;
        if ($childType) {
            $items = collect($source)->map(fn ($b) => $b['items'] ?? []);
            $n = $items->map(fn ($list) => count($list))->max();
            for ($i = 0; $i < $n; $i++) {
                $childPair = $items->map(fn ($list) => isset($list[$i]) ? ['type' => $childType] + $list[$i] : null)->filter()->all();
                $this->createBlock($model, $childPair, $i + 1, $block, $childType);
            }
        }

        return $block;
    }

    protected function fieldValue(string $field, $value, string $locale)
    {
        if (is_string($value) && (Str::endsWith($field, 'url'))) {
            $value = $this->url($value, $locale);
        } elseif (is_string($value) && (Str::endsWith($field, 'html') || in_array($field, ['answer', 'bio', 'text_html']))) {
            $value = $this->html($value);
        }

        return $value;
    }

    /** Makes links to the old domain relative and fixes links that were missing their leading slash. */
    protected function url(?string $url, string $locale = 'en'): ?string
    {
        if (blank($url)) {
            return $url;
        }
        $url = preg_replace('#^https?://(www\.)?neopharmlabs\.com#i', '', trim($url)) ?: '/';
        if (! preg_match('#^(/|https?:|mailto:|tel:|\#)#', $url)) {
            $url = '/' . $url;
        }
        // The English home page linked to the French careers page on the original site.
        if ($locale === 'en' && $url === '/fr/carrieres') {
            $url = '/careers';
        }

        return $url;
    }

    /** Rewrites rich text: relative links to the old domain and recovered inline images served from storage. */
    protected function html(?string $html): ?string
    {
        if (blank($html)) {
            return $html;
        }
        $html = preg_replace('#(href|src)="https?://(www\.)?neopharmlabs\.com#i', '$1="', $html);
        $html = preg_replace_callback('#(src|href)="/recovered/([^"]+)"#', function ($m) {
            $path = rawurldecode($m[2]);
            $source = $this->findImage($path);
            if ($source) {
                $target = storage_path('app/public/recovered/' . $path);
                File::ensureDirectoryExists(dirname($target));
                File::copy($source, $target);
            } else {
                $this->missingImages[] = $path;
            }

            return $m[1] . '="/storage/recovered/' . str_replace('%2F', '/', rawurlencode($path)) . '"';
        }, $html);

        return $html;
    }

    protected function findImage(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }
        $path = ltrim(rawurldecode($path), '/');
        foreach ([$path, 'images/' . $path, preg_replace('#^images/#', '', $path)] as $candidate) {
            $file = $this->dir . '/images/' . $candidate;
            if (is_file($file)) {
                return $file;
            }
        }

        return null;
    }

    protected function media(string $path): ?Media
    {
        if (isset($this->mediaCache[$path])) {
            return $this->mediaCache[$path];
        }
        $file = $this->findImage($path);
        if (! $file) {
            $this->missingImages[] = $path;

            return null;
        }
        $filename = Str::of(basename($file))->replaceMatches('/[^A-Za-z0-9._-]+/', '-')->toString();
        $uuid = Str::uuid() . '/' . $filename;
        $target = storage_path('app/public/' . config('twill.media_library.local_path', 'uploads') . '/' . $uuid);
        File::ensureDirectoryExists(dirname($target));
        File::copy($file, $target);
        [$width, $height] = @getimagesize($file) ?: [null, null];

        return $this->mediaCache[$path] = Media::query()->create([
            'uuid' => $uuid,
            'filename' => $filename,
            'width' => $width ?: 1,
            'height' => $height ?: 1,
            'alt_text' => '',
            'caption' => '',
        ]);
    }

    protected function attachMedia(Model $model, string $role, ?string $path, int $position = 1): void
    {
        if (blank($path) || ! ($media = $this->media($path))) {
            return;
        }
        $model->medias()->attach($media->id, [
            'crop' => 'default',
            'role' => $role,
            'locale' => config('app.locale', 'en'),
            'ratio' => 'default',
            'crop_w' => $media->width,
            'crop_h' => $media->height,
            'crop_x' => 0,
            'crop_y' => 0,
            'metadatas' => json_encode(['caption' => null, 'altText' => null, 'video' => null]),
            'position' => $position,
        ]);
    }

    protected function redirectItem(array $translations, string $target): void
    {
        foreach ($translations as $locale => $t) {
            if (! empty($t['path'])) {
                $this->redirects[] = ['from' => $t['path'], 'target' => $target, 'locale' => $locale, 'title' => 'Duplicate: ' . ($t['title'] ?? $t['path'])];
            }
        }
    }

    /** Resolves "page:about", "service:/services/x", "post:/blog/x", "resource:/resources/x", "category:/blog/category/x", "route:name". */
    protected function resolveTarget(string $target, string $locale): ?string
    {
        [$kind, $key] = explode(':', $target, 2);
        app()->setLocale($locale);
        $path = function (?Model $model, string $route, string $param = 'slug') use ($locale) {
            if (! $model) {
                return null;
            }
            $slug = optional($model->slugs()->where('locale', $locale)->where('active', true)->first())->slug
                ?? optional($model->slugs()->where('locale', 'en')->first())->slug;
            $hasLocale = DB::table(Str::snake(class_basename($model)) . '_translations')
                ->where(Str::snake(class_basename($model)) . '_id', $model->getKey())->where('locale', $locale)->exists();

            return $slug ? parse_url(lroute($route, [$param => $slug], $hasLocale ? $locale : 'en'), PHP_URL_PATH) : null;
        };

        $url = match ($kind) {
            'page' => $key === 'home' ? parse_url(lroute('home', [], $locale), PHP_URL_PATH) ?? '/' : $path($this->map['pages'][$key] ?? null, 'pages.show'),
            'service' => $path($this->map['services'][$key] ?? null, 'services.show'),
            'post' => $path($this->map['posts'][$key] ?? null, 'blog.show'),
            'resource' => $path($this->map['resources'][$key] ?? null, 'resources.show'),
            'category' => $path($this->map['categories'][$key] ?? null, 'blog.category'),
            'route' => parse_url(lroute($key, [], $locale), PHP_URL_PATH),
            default => null,
        };
        app()->setLocale('en');

        return $url ?: '/';
    }

    protected function readBlockDefinitions(): void
    {
        foreach (File::glob(resource_path('views/twill/{blocks,repeaters}/*.blade.php'), GLOB_BRACE) as $file) {
            preg_match_all('/<x-twill::[a-z-]+[^>]*?name="([a-z_]+)"[^>]*?:translated="true"/s', File::get($file), $m);
            $this->translatedFields[basename($file, '.blade.php')] = $m[1];
        }
    }

    protected function wipe(): void
    {
        $this->warn('Deleting existing CMS content...');
        Schema::disableForeignKeyConstraints();
        foreach (['pages', 'services', 'posts', 'post_categories', 'resources', 'team_members', 'job_postings'] as $table) {
            $singular = Str::singular($table);
            foreach ([$singular . '_translations', $singular . '_slugs', $singular . '_revisions', $table] as $t) {
                if (Schema::hasTable($t)) {
                    DB::table($t)->delete();
                }
            }
        }
        foreach (['redirects', 'twill_blocks', 'twill_mediables', 'twill_medias', 'twill_fileables'] as $t) {
            $t = Schema::hasTable($t) ? $t : str_replace('twill_', '', $t);
            if (Schema::hasTable($t)) {
                DB::table($t)->delete();
            }
        }
        Schema::enableForeignKeyConstraints();
        File::deleteDirectory(storage_path('app/public/' . config('twill.media_library.local_path', 'uploads')));
        File::deleteDirectory(storage_path('app/public/recovered'));
    }
}
