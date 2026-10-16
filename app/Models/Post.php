<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasBlocks, HasTranslation, HasSlug, HasMedias, HasRevisions;

    protected $fillable = [
        'published',
        'post_category_id',
        'published_at',
        'author_name',
        'legacy',
        'title',
        'excerpt',
        'author_bio',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'published_at' => 'date',
        'legacy' => 'boolean',
    ];

    public $translatedAttributes = [
        'title',
        'excerpt',
        'author_bio',
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
        'author_photo' => [
            'default' => [['name' => 'default', 'ratio' => 1]],
        ],
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }
}
