<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Models\ProductPropertyValue;
use RedJasmine\Product\Services\Property\Actions\Values;
use RedJasmine\Product\Services\Property\Data\PropertyValueData;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @method Values\PropertyValueQueryAction query()
 * @method ProductPropertyValue create(PropertyValueData|array $data)
 * @method ProductPropertyValue update(int $id, PropertyValueData|array $data)
 * @method boolean delete(int $id)
 */
class PropertyValueService extends Service
{

    protected static ?string $model = ProductPropertyValue::class;

    protected static ?string $data = PropertyValueData::class;

    /**
     * 默认的操作
     * @var array|string[]
     */
    protected static array $actions = [
        'query'  => Values\PropertyValueQueryAction::class,
        'create' => Values\PropertyValueCreateAction::class,
        'update' => Values\PropertyValueUpdateAction::class,
        'delete' => Values\PropertyValueDeleteAction::class,
    ];


}
