<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;

class Resource extends Model
{
    use HasBlocks, HasTranslation, HasSlug, HasMedias, HasFiles, HasRevisions;

    public const TYPES = [
        'posters' => ['Posters', 'Affiches'],
        'webinars' => ['Webinars', 'Webinaires'],
        'white-papers' => ['White Papers', 'Livres blancs'],
    ];

    protected $fillable = [
        'published',
        'type',
        'published_at',
        'title',
        'intro',
        'body',
        'button_label',
        'form_title',
        'presenters_title',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public $translatedAttributes = [
        'title',
        'intro',
        'body',
        'button_label',
        'form_title',
        'presenters_title',
        'meta_title',
        'meta_description',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'cover' => [
            'default' => [['name' => 'default', 'ratio' => 0]],
        ],
    ];

    public $filesParams = ['download'];

    public function typeLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return self::TYPES[$this->type][$locale === 'fr' ? 1 : 0] ?? $this->type;
    }
}
