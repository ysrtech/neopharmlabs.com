<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class TeamMemberController extends BaseModuleController
{
    protected $moduleName = 'teamMembers';

    protected $titleColumnKey = 'name';

    protected function setUpController(): void
    {
        $this->setPermalinkBase('about/team');
        $this->enableReorder();
        $this->setTitleColumnKey('name');
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('role')->label('Title / role')->translatable());
        $form->add(Medias::make()->name('photo')->label('Photo'));
        $form->add(Wysiwyg::make()->name('lead')->label('Intro paragraph')->translatable()->toolbarOptions(['bold', 'italic']));
        $form->add(Wysiwyg::make()->name('bio')->label('Biography')->translatable());

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('role')->title('Role'));

        return $table;
    }
}
