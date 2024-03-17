<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Models\ProductPropertyGroup;
use RedJasmine\Product\Services\Property\Actions\Groups;
use RedJasmine\Product\Services\Property\Data\PropertyGroupData;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @method ProductPropertyGroup create(PropertyGroupData $data)
 * @method ProductPropertyGroup update(int $id, PropertyGroupData $data)
 * @method boolean delete(int $id)
 */
class PropertyGroupService extends Service
{

    protected static ?string $model = ProductPropertyGroup::class;

    protected static ?string $data = PropertyGroupData::class;

    /**
     * 默认的操作
     * @var array|string[]
     */
    protected static array $actions = [
        'create' => Groups\PropertyGroupCreateAction::class,
        'update' => Groups\PropertyGroupUpdateAction::class,
        'delete' => Groups\PropertyGroupDeleteAction::class,
    ];


}
