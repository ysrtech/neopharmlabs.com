<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\DatePicker;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class JobPostingController extends BaseModuleController
{
    protected $moduleName = 'jobPostings';

    protected function setUpController(): void
    {
        $this->setPermalinkBase('careers/jobs');
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('reference')->label('Reference number')->note('Used in the job URL, e.g. 20260302001'));
        $form->add(Input::make()->name('department')->label('Department'));
        $form->add(Input::make()->name('location')->label('Location'));
        $form->add(Input::make()->name('schedule')->label('Schedule / type')->note('e.g. Day, Evening, Full-time'));
        $form->add(DatePicker::make()->name('posted_at')->label('Posted on')->withoutTime());
        $form->add(Input::make()->name('excerpt')->label('Short description (job list)')->type('textarea')->translatable());
        $form->add(Wysiwyg::make()->name('body')->label('About the position')->translatable()->allowSource());
        $form->add(Wysiwyg::make()->name('requirements')->label('Responsibilities / requirements')->translatable());

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('location')->title('Location'));
        $table->add(Text::make()->field('posted_at')->title('Posted')->sortable());

        return $table;
    }
}
