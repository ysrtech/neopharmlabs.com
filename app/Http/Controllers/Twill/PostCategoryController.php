<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;

class PostCategoryController extends BaseModuleController
{
    protected $moduleName = 'postCategories';

    protected function setUpController(): void
    {
        $this->setPermalinkBase('blog/category');
        $this->enableReorder();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('heading')->label('Page heading')->translatable());

        return $form;
    }
}
