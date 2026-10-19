<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model implements Sortable
{
    use HasTranslation, HasSlug, HasPosition;

    protected $fillable = [
        'published',
        'position',
        'title',
        'heading',
    ];

    public $translatedAttributes = [
        'title',
        'heading',
    ];

    public $slugAttributes = [
        'title',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
