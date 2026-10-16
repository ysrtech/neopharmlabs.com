<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class SubmissionController extends BaseModuleController
{
    protected $moduleName = 'submissions';

    protected function setUpController(): void
    {
        $this->disableCreate();
        $this->disablePermalink();
        $this->disableEditor();
        $this->disablePublish();
        $this->setTitleColumnKey('title');
        $this->defaultOrders = ['created_at' => 'desc'];
    }

    public function getForm(TwillModelContract $model): Form
    {
        return Form::make([
            BladePartial::make()->view('twill.submission-details')->withAdditionalParams(['submission' => $model]),
        ]);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('form')->title('Form'));
        $table->add(Text::make()->field('email')->title('Email'));
        $table->add(Text::make()->field('created_at')->title('Received')->sortable());

        return $table;
    }
}
