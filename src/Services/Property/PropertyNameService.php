<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Services\Property\Actions\Names;
use RedJasmine\Product\Services\Property\Data\PropertyData;
use RedJasmine\Support\Foundation\Service\ResourceService;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @method ProductProperty create(PropertyData|array $data)
 * @method ProductProperty update(int $id, PropertyData|array $data)
 */
class PropertyNameService extends ResourceService
{

    protected static string $modelClass = ProductProperty::class;

    protected static string $dataClass = PropertyData::class;


    protected static ?string $serviceConfigKey = 'red-jasmine.product.services.property.name';

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
