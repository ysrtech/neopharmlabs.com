<?php

namespace App\Http\Controllers\Twill\Concerns;

/**
 * Makes the admin permalink match the public URLs: English at the root, French under /fr,
 * with the translated segments from config('site.segments').
 */
trait LocalizedPermalinks
{
    protected ?string $permalinkSegment = null;

    protected function useLocalizedPermalinks(?string $segment): void
    {
        $this->permalinkSegment = $segment;
        $this->setPermalinkBase('');
        $this->withoutLanguageInPermalink();
    }

    protected function getLocalizedPermalinkBase(): array
    {
        $bases = [];
        foreach (config('site.locales') as $locale => $prefix) {
            $path = trim($prefix . '/' . ($this->permalinkSegment ? seg($this->permalinkSegment, $locale) : ''), '/');
            if ($path !== '') {
                $bases[$locale] = $path;
            }
        }

        return $bases;
    }
}
