<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;

class JobPosting extends Model
{
    use HasTranslation, HasSlug;

    protected $fillable = [
        'published',
        'reference',
        'department',
        'location',
        'schedule',
        'posted_at',
        'title',
        'excerpt',
        'body',
        'requirements',
    ];

    protected $casts = [
        'posted_at' => 'date',
    ];

    public $translatedAttributes = [
        'title',
        'excerpt',
        'body',
        'requirements',
    ];

    // Job URLs use the posting reference (e.g. /careers/jobs/20260302001), as on the original site.
    public $slugAttributes = [
        'reference',
    ];
}
