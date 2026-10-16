<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\MultiSelect;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\Service;

class ServiceController extends BaseModuleController
{
    protected $moduleName = 'services';

    protected function setUpController(): void
    {
        $this->setPermalinkBase('services');
        $this->enableReorder();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $parents = Service::query()->topLevel()->whereKeyNot($model->id ?? 0)->get()
            ->map(fn (Service $s) => Option::make($s->id, $s->title))->all();

        $form->add(
            Select::make()->name('parent_id')->label('Parent service')
                ->note('Leave empty for a top-level service category (shown in the menu)')
                ->options(Options::make([Option::make('', '— none (top level) —'), ...$parents]))
        );
        $form->add(Input::make()->name('hero_title')->label('Hero heading')->translatable());
        $form->add(Wysiwyg::make()->name('hero_intro')->label('Hero text')->translatable()->toolbarOptions(['bold', 'italic', 'link']));
        $form->add(Input::make()->name('hero_button_label')->label('Hero button label')->translatable());
        $form->add(Input::make()->name('hero_button_url')->label('Hero button link')->translatable());
        $form->add(Medias::make()->name('cover')->label('Hero image'));
        $form->add(BlockEditor::make()->blocks(['text', 'cta', 'faq', 'image', 'quote', 'video']));

        $filters = [];
        foreach (Service::TAXONOMY as $group => $values) {
            $filters[] = MultiSelect::make()->name('taxonomy_' . $group)->label(ucfirst(str_replace('_', ' ', $group)))
                ->options(Options::make(collect($values)->map(fn ($l, $v) => Option::make($v, $l[0]))->values()->all()));
        }
        $form->addFieldset(Fieldset::make()->title('Services filters')->id('filters')->fields($filters));

        $form->addFieldset(
            Fieldset::make()->title('Menu icon & SEO')->id('seo')->closed()->fields([
                Input::make()->name('icon_svg')->label('Menu icon (SVG markup)')->type('textarea')->note('Only used for top-level services'),
                Input::make()->name('meta_title')->label('Meta title')->translatable(),
                Input::make()->name('meta_description')->label('Meta description')->type('textarea')->translatable(),
            ])
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Relation::make()->field('title')->title('Parent')->relation('parent'));

        return $table;
    }
}
