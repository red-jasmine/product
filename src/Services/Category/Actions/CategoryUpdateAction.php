<?php

namespace RedJasmine\Product\Services\Category\Actions;


use RedJasmine\Product\Services\Category\Pipelines\CategoryUpdatePipelines;
use RedJasmine\Support\Foundation\Service\Actions\ResourceUpdateAction;

class CategoryUpdateAction extends ResourceUpdateAction
{

    protected function pipes() : array
    {
        return [
            CategoryUpdatePipelines::class
        ];
    }

}
