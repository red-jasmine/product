<?php

namespace RedJasmine\Product\Services\Property\Actions\Groups;

use RedJasmine\Product\Models\ProductPropertyGroup;
use RedJasmine\Product\Services\Property\Data\PropertyGroupData;
use RedJasmine\Product\Services\Property\PropertyGroupService;
use RedJasmine\Support\Foundation\Service\Actions;

/**
 * @property PropertyGroupService $service
 * @property ProductPropertyGroup $model
 * @property PropertyGroupData    $data
 */
class PropertyGroupDeleteAction extends Actions\AbstractDeleteAction
{
    public PropertyGroupService $service;

}
