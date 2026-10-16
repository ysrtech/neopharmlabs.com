<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class TeamMember extends Model implements Sortable
{
    use HasTranslation, HasSlug, HasMedias, HasPosition;

    protected $fillable = [
        'published',
        'position',
        'name',
        'role',
        'lead',
        'bio',
        'meta_title',
        'meta_description',
    ];

    public $translatedAttributes = [
        'name',
        'role',
        'lead',
        'bio',
        'meta_title',
        'meta_description',
    ];

    public $slugAttributes = [
        'name',
    ];

    public $mediasParams = [
        'photo' => [
            'default' => [['name' => 'default', 'ratio' => 0]],
        ],
    ];

    public function getTitleAttribute(): ?string
    {
        return $this->name;
    }
}
