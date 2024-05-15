<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Services\Property\Actions\Groups;
use RedJasmine\Product\Services\Property\Data\PropertyGroupData;
use RedJasmine\Support\Foundation\Service\ResourceService;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @method ProductPropertyGroup create(PropertyGroupData $data)
 * @method ProductPropertyGroup update(int $id, PropertyGroupData $data)
 */
class PropertyGroupService extends ResourceService
{

    protected static string $modelClass = ProductPropertyGroup::class;

    protected static string $dataClass = PropertyGroupData::class;


    protected static ?string $serviceConfigKey = 'red-jasmine.product.services.property.group';

    public static function filters() : array
    {
        return [
            'name',
            AllowedFilter::exact('status'),
        ];
    }

}
