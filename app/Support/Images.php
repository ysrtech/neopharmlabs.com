<?php

namespace App\Support;

class Images
{
    public static function attributes($source, string $role, array $widths, string $crop = 'default'): ?array
    {
        if (! $source || ! method_exists($source, 'hasImage') || ! $source->hasImage($role, $crop)) {
            return null;
        }

        $srcset = collect($widths)
            ->map(fn ($w) => $source->image($role, $crop, ['w' => $w, 'fm' => 'jpg', 'q' => 80]) . " {$w}w")
            ->implode(', ');

        return [
            'src' => $source->image($role, $crop, ['w' => 40, 'blur' => 5, 'fm' => 'jpg', 'q' => 50]),
            'srcset' => $srcset,
            'full' => $source->image($role, $crop, ['w' => max($widths), 'fm' => 'jpg', 'q' => 80]),
            'alt' => $source->imageAltText($role) ?? '',
        ];
    }

    public static function tag($source, string $role, array $widths, string $class = 'lozad', string $crop = 'default'): string
    {
        $a = self::attributes($source, $role, $widths, $crop);
        if (! $a) {
            return '';
        }

        return sprintf(
            '<img class="%s" sizes="100vw" src="%s" data-srcset="%s" alt="%s">',
            e($class),
            e($a['src']),
            e($a['srcset']),
            e($a['alt'])
        );
    }
}
