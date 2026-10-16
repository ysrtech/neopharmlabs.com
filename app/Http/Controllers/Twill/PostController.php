<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\DatePicker;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\PostCategory;

class PostController extends BaseModuleController
{
    protected $moduleName = 'posts';

    protected function setUpController(): void
    {
        $this->setPermalinkBase('blog');
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Select::make()->name('post_category_id')->label('Category')->options(Options::make(
                PostCategory::query()->orderBy('position')->get()->map(fn ($c) => Option::make($c->id, $c->title))->all()
            ))
        );
        $form->add(DatePicker::make()->name('published_at')->label('Publication date')->withoutTime());
        $form->add(Input::make()->name('excerpt')->label('Summary')->type('textarea')->translatable());
        $form->add(Medias::make()->name('cover')->label('Featured image'));
        $form->add(BlockEditor::make()->blocks(['text', 'cta', 'faq', 'image', 'quote', 'video']));

        $form->addFieldset(
            Fieldset::make()->title('Author')->id('author')->closed()->fields([
                Input::make()->name('author_name')->label('Author name'),
                Input::make()->name('author_bio')->label('Author short bio')->type('textarea')->translatable(),
                Medias::make()->name('author_photo')->label('Author photo'),
            ])
        );
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
        $table->add(Relation::make()->field('title')->title('Category')->relation('category'));
        $table->add(Text::make()->field('published_at')->title('Date')->sortable());

        return $table;
    }
}
