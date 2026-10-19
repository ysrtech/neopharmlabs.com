<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\JobPosting;

class JobPostingTranslation extends Model
{
    protected $baseModuleModel = JobPosting::class;
}
