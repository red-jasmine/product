<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Services\Property\Actions\Values;
use RedJasmine\Product\Services\Property\Data\PropertyValueData;
use RedJasmine\Support\Foundation\Service\ResourceService;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @method ProductPropertyValue create(PropertyValueData|array $data)
 * @method ProductPropertyValue update(int $id, PropertyValueData|array $data)
 */
class PropertyValueService extends ResourceService
{

    protected static string $modelClass = ProductPropertyValue::class;

    protected static string $dataClass = PropertyValueData::class;

    protected static ?string $serviceConfigKey = 'red-jasmine.product.services.property.value';

    public static function filters() : array
    {
        return [
            'name',
            'group_id',
            'pid',
            AllowedFilter::exact('status'),
        ];
    }


}
