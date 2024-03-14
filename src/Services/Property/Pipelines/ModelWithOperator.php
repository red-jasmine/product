<?php

namespace RedJasmine\Product\Services\Property\Pipelines;

use RedJasmine\Support\Foundation\Service\Actions;

class ModelWithOperator
{


    public function handle(Actions $action, \Closure $closure)
    {

        $action->model->creator = $action->model->creator ?? $action->service->getOperator();
        $action->model->updater = $action->service->getOperator();
        return $closure($action);
    }

}
