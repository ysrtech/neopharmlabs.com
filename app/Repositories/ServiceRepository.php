<?php

namespace App\Repositories;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Service;

class ServiceRepository extends ModuleRepository
{
    use HandleBlocks, HandleTranslations, HandleSlugs, HandleMedias, HandleRevisions;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    public function getFormFields(TwillModelContract $object): array
    {
        $fields = parent::getFormFields($object);
        $values = $object->taxonomy ?? [];

        foreach (Service::TAXONOMY as $group => $options) {
            $fields['taxonomy_' . $group] = array_values(array_intersect($values, array_keys($options)));
        }

        return $fields;
    }

    public function prepareFieldsBeforeSave(TwillModelContract $object, array $fields): array
    {
        $taxonomy = [];
        foreach (array_keys(Service::TAXONOMY) as $group) {
            $taxonomy = array_merge($taxonomy, (array) ($fields['taxonomy_' . $group] ?? []));
            unset($fields['taxonomy_' . $group]);
        }
        $fields['taxonomy'] = array_values(array_unique(array_filter($taxonomy)));
        $fields['parent_id'] = ($fields['parent_id'] ?? null) ?: null;

        return parent::prepareFieldsBeforeSave($object, $fields);
    }
}
