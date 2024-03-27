<?php

namespace RedJasmine\Product\Services\Category\Actions;


use RedJasmine\Product\Services\Category\Pipelines\CategoryUpdatePipelines;
use RedJasmine\Support\Foundation\Service\Actions\UpdateAction;

class CategoryUpdateAction extends UpdateAction
{

    public function pipes() : array
    {
        return [
            CategoryUpdatePipelines::class
        ];
    }

}
