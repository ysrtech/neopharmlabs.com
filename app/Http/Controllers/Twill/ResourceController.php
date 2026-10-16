<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use App\Http\Controllers\Twill\Concerns\LocalizedPermalinks;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\DatePicker;
use A17\Twill\Services\Forms\Fields\Files;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\Resource;

class ResourceController extends BaseModuleController
{
    use LocalizedPermalinks;

    protected $moduleName = 'resources';

    protected function setUpController(): void
    {
        $this->useLocalizedPermalinks('resources');
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Select::make()->name('type')->label('Type')->options(Options::make(
                collect(Resource::TYPES)->map(fn ($l, $v) => Option::make($v, $l[0]))->values()->all()
            ))
        );
        $form->add(DatePicker::make()->name('published_at')->label('Date')->withoutTime());
        $form->add(Wysiwyg::make()->name('intro')->label('Intro')->translatable()->toolbarOptions(['bold', 'italic', 'link']));
        $form->add(Input::make()->name('button_label')->label('Hero button label')->translatable());
        $form->add(Medias::make()->name('cover')->label('Image'));
        $form->add(Wysiwyg::make()->name('body')->label('Description')->translatable()->allowSource());
        $form->add(Input::make()->name('form_title')->label('Download form title')->translatable());
        $form->add(
            Files::make()->name('download')->label('Downloadable file')
                ->note('Sent to visitors after they fill in the download form')
        );
        $form->add(Input::make()->name('presenters_title')->label('Presenters heading')->translatable());
        $form->add(BlockEditor::make()->blocks(['presenter']));

        $form->addFieldset(
            Fieldset::make()->title('SEO')->id('seo')->closed()->fields([
                Input::make()->name('meta_title')->label('Meta title')->translatable(),
                Input::make()->name('meta_description')->label('Meta description')->type('textarea')->translatable(),
            ])
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('type')->title('Type'));

        return $table;
    }
}
