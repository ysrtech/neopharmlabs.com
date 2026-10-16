<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\PostCategory;

class PostCategoryTranslation extends Model
{
    protected $baseModuleModel = PostCategory::class;
}
