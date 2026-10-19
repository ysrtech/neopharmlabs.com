<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\Redirect;

class RedirectRepository extends ModuleRepository
{
    public function __construct(Redirect $model)
    {
        $this->model = $model;
    }
}
