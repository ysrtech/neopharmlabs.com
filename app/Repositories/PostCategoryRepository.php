<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\PostCategory;

class PostCategoryRepository extends ModuleRepository
{
    use HandleTranslations, HandleSlugs;

    public function __construct(PostCategory $model)
    {
        $this->model = $model;
    }
}
