<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\Page;

class PageController extends BaseModuleController
{
    protected $moduleName = 'pages';

    protected function setUpController(): void
    {
        $this->setPermalinkBase('');
        $this->withoutLanguageInPermalink(false);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Select::make()->name('template')->label('Template')
                ->options(Options::fromArray(Page::TEMPLATES))
                ->default('default')
        );
        $form->add(
            BlockEditor::make()->blocks([
                'hero', 'services_cards', 'image_text', 'resources_feed', 'carousel', 'text_center',
                'form_block', 'locations', 'text', 'cta', 'faq', 'image', 'quote', 'video',
            ])
        );

        $form->addFieldset(
            Fieldset::make()->title('SEO')->id('seo')->closed()->fields([
                Input::make()->name('meta_title')->label('Meta title')->translatable(),
                Input::make()->name('meta_description')->label('Meta description')->type('textarea')->translatable(),
                Medias::make()->name('og_image')->label('Social sharing image'),
            ])
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('template')->title('Template'));

        return $table;
    }
}
