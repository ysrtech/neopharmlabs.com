<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;

class Page extends Model
{
    use HasBlocks, HasTranslation, HasSlug, HasMedias, HasRevisions;

    public const TEMPLATES = [
        'default' => 'Default',
        'home' => 'Home page',
        'about' => 'About',
        'careers' => 'Careers',
        'contact' => 'Contact',
        'legal' => 'Legal',
    ];

    protected $fillable = [
        'published',
        'template',
        'key',
        'title',
        'meta_title',
        'meta_description',
    ];

    public $translatedAttributes = [
        'title',
        'meta_title',
        'meta_description',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'og_image' => [
            'default' => [['name' => 'default', 'ratio' => 0]],
        ],
    ];
}
