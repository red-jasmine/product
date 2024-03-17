<?php

namespace RedJasmine\Product\Services\Property\Actions\Names;

use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Product\Services\Property\Data\PropertyData;
use RedJasmine\Product\Services\Property\PropertyNameService;
use RedJasmine\Product\Services\Property\PropertyService;
use RedJasmine\Support\Foundation\Service\Actions;

/**
 * @property PropertyService $service
 * @property ProductProperty $model
 * @property PropertyData    $data
 */
class PropertyNameCreateAction extends Actions\AbstractCreateAction
{
    public PropertyNameService $service;



}
