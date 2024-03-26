<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Models\ProductPropertyGroup;
use RedJasmine\Product\Services\Property\Actions\Groups;
use RedJasmine\Product\Services\Property\Data\PropertyGroupData;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Foundation\Service\Service;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @method ProductPropertyGroup create(PropertyGroupData $data)
 * @method ProductPropertyGroup update(int $id, PropertyGroupData $data)
 */
class PropertyGroupService extends ResourceService
{

    protected static string $modelClass = ProductPropertyGroup::class;

    protected static string $dataClass = PropertyGroupData::class;


    protected static ?string $actionsConfigKey = 'red-jasmine.product.services.property.group.actions';

    public static ?string $actionPipelinesConfigPrefix = 'red-jasmine.product.services.property.group.pipelines';

    public static function filters() : array
    {
        return [
            'name',
            AllowedFilter::exact('status'),
        ];
    }

}
