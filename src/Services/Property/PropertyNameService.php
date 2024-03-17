<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Product\Services\Property\Actions\Names;
use RedJasmine\Product\Services\Property\Data\PropertyData;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @method Names\PropertyNameQueryAction query()
 * @method ProductProperty create(PropertyData|array $data)
 * @method ProductProperty update(int $id, PropertyData|array $data)
 * @method boolean delete(int $id)
 */
class PropertyNameService extends Service
{

    protected static ?string $model = ProductProperty::class;

    protected static ?string $data = PropertyData::class;
    /**
     * 默认的操作
     * @var array|string[]
     */
    protected static array $actions = [
        'query'  => Names\PropertyNameQueryAction::class,
        'create' => Names\PropertyNameCreateAction::class,
        'update' => Names\PropertyNameUpdateAction::class,
        'delete' => Names\PropertyNameDeleteAction::class,
    ];


}
