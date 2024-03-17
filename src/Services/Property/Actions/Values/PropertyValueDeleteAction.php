<?php

namespace RedJasmine\Product\Services\Property\Actions\Values;

use RedJasmine\Product\Models\ProductPropertyValue;
use RedJasmine\Product\Services\Property\Data\PropertyValueData;
use RedJasmine\Product\Services\Property\PropertyValueService;
use RedJasmine\Support\Foundation\Service\Actions;

/**
 * @property PropertyValueService $service
 * @property ProductPropertyValue $model
 * @property PropertyValueData    $data
 */
class PropertyValueDeleteAction extends Actions\AbstractDeleteAction
{
    public PropertyValueService $service;

}
