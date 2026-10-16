<?php

use App\Support\Images;

if (! function_exists('lroute')) {
    /** Named front-end route in the given (or current) locale, e.g. lroute('services.show', ['slug' => 'x']). */
    function lroute(string $name, array $params = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return route($locale . '.' . $name, $params);
    }
}

if (! function_exists('seg')) {
    /** Translated URL segment from config/site.php. */
    function seg(string $key, ?string $locale = null): string
    {
        return config("site.segments.$key." . ($locale ?? app()->getLocale()));
    }
}

if (! function_exists('img')) {
    /**
     * Lazy-loaded image attributes matching the original markup (lozad + data-srcset).
     * $source is a model or block using HasMedias.
     */
    function img($source, string $role, array $widths = [1000, 500], string $crop = 'default'): ?array
    {
        return Images::attributes($source, $role, $widths, $crop);
    }
}

if (! function_exists('lazy_img')) {
    function lazy_img($source, string $role, array $widths = [1000, 500], string $class = 'lozad', string $crop = 'default'): string
    {
        return Images::tag($source, $role, $widths, $class, $crop);
    }
}

if (! function_exists('local_url')) {
    /** Rewrites absolute links to the old domain into relative ones. */
    function local_url(?string $url): string
    {
        $url = trim((string) $url);

        return preg_replace('#^https?://(www\.)?neopharmlabs\.com#i', '', $url) ?: '/';
    }
}

if (! function_exists('page_url')) {
    /** URL of a CMS page by its key (home, about, careers, contact, legal) in the current locale. */
    function page_url(string $key, ?string $locale = null): string
    {
        static $cache = [];
        $locale ??= app()->getLocale();
        if ($key === 'home') {
            return lroute('home', [], $locale);
        }

        return $cache["$key.$locale"] ??= (function () use ($key, $locale) {
            $page = \App\Models\Page::query()->where('key', $key)->first();
            $slug = $page ? optional($page->getActiveSlug($locale))->slug : null;

            return lroute('pages.show', ['slug' => $slug ?: $key], $locale);
        })();
    }
}

if (! function_exists('bt')) {
    /** Block field value in the current locale (falls back to English, or to the raw value for untranslated fields). */
    function bt($block, string $name, $default = null)
    {
        $value = $block->content[$name] ?? null;
        if (is_array($value)) {
            $value = filled($value[app()->getLocale()] ?? null) ? $value[app()->getLocale()] : ($value[config('translatable.fallback_locale', 'en')] ?? null);
        }

        return filled($value) ? $value : $default;
    }
}

if (! function_exists('root_blocks')) {
    /** Top-level blocks of a model's default editor, in order, with children and medias eager loaded. */
    function root_blocks($model)
    {
        return $model->blocks
            ->filter(fn ($b) => $b->parent_id === null && ($b->editor_name ?? 'default') === 'default')
            ->sortBy('position')
            ->values();
    }
}
