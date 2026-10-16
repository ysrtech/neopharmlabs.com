<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Controller extends BaseController
{
    /**
     * Builds the language switcher links: one URL per locale, falling back to that locale's home page
     * when the item has no translation.
     */
    protected function alternates(?Model $item, string $routeName, string $param = 'slug', array $extra = []): array
    {
        $links = [];
        foreach (array_keys(config('site.locales')) as $locale) {
            $slug = $item && method_exists($item, 'getActiveSlug') ? optional($item->getActiveSlug($locale))->slug : null;
            $translated = $item && (! method_exists($item, 'hasTranslation') || $item->hasTranslation($locale));
            $links[$locale] = ($item === null)
                ? lroute($routeName, $extra, $locale)
                : (($slug && $translated) ? lroute($routeName, [$param => $slug] + $extra, $locale) : lroute('home', [], $locale));
        }

        return $links;
    }

    protected function staticAlternates(string $routeName, array $params = []): array
    {
        return collect(config('site.locales'))->keys()
            ->mapWithKeys(fn ($l) => [$l => lroute($routeName, $params, $l)])->all();
    }

    /** Finds a published item by its slug in the current locale (or any locale as a fallback). */
    protected function findBySlug(string $class, string $slug, ?callable $scope = null): Model
    {
        $query = fn () => tap($class::query()->published(), fn (Builder $q) => $scope ? $scope($q) : null);

        $item = $query()->forSlug($slug)->first()
            ?? $query()->forInactiveSlug($slug)->first()
            ?? $query()->whereHas('slugs', fn ($q) => $q->where('slug', $slug))->first();

        abort_unless($item, 404);

        return $item;
    }

    protected function seo(?Model $item, ?string $fallbackTitle = null, ?string $fallbackDescription = null): array
    {
        $title = $item?->meta_title ?: ($fallbackTitle ?: ($item?->title ?? null));

        return [
            'title' => $title ? (str_contains($title, 'Neopharm') ? $title : $title . ' | Neopharm') : null,
            'description' => $item?->meta_description ?: $fallbackDescription,
        ];
    }
}
