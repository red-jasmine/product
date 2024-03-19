<?php

namespace RedJasmine\Product\Services\Category\Pipelines;

use Closure;
use RedJasmine\Support\Foundation\Service\Actions\AbstractResourceAction;
use RedJasmine\Support\Foundation\Service\Pipelines\ResourceActionPipelines;
use RedJasmine\Support\Rules\ParentIDValidationRule;

class CategoryUpdatePipelines extends ResourceActionPipelines
{
    public function validate(AbstractResourceAction $action, Closure $next) : array
    {
        $validator = $action->getValidator();
        $validator->addRules([ 'parent_id' => [ new ParentIDValidationRule($action->model->getKey()) ] ]);
        return parent::validate($action, $next);
    }


}
