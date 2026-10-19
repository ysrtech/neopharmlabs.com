<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class RedirectController extends BaseModuleController
{
    protected $moduleName = 'redirects';

    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->disableEditor();
        $this->setTitleColumnKey('from_path');
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('from_path')->label('Old path')->note('e.g. /services/stability-services'));
        $form->add(Input::make()->name('to_path')->label('Redirect to')->note('Path or full URL'));
        $form->add(Select::make()->name('status_code')->label('Type')->default(301)
            ->options(Options::fromArray([301 => 'Permanent (301)', 302 => 'Temporary (302)'])));

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('to_path')->title('Redirects to'));

        return $table;
    }
}
