<?php

namespace App\Models;

use A17\Twill\Models\Model;

class Submission extends Model
{
    public const FORMS = [
        'contact' => 'Contact form',
        'resource' => 'Resource download',
        'privacy_request' => 'Privacy request',
        'job_application' => 'Job application',
        'spontaneous_application' => 'Spontaneous application',
    ];

    protected $fillable = [
        'published',
        'title',
        'form',
        'locale',
        'email',
        'subject_type',
        'subject_id',
        'data',
        'attachments',
        'ip',
    ];

    protected $casts = [
        'data' => 'array',
        'attachments' => 'array',
    ];
}
