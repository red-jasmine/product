<?php

namespace RedJasmine\Product\Services\Property;

use App\Models\Patient;
use RedJasmine\Product\Models\ProductPropertyValue;
use RedJasmine\Product\Services\Property\Actions\Values;
use RedJasmine\Product\Services\Property\Data\PropertyValueData;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Foundation\Service\Service;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @method ProductPropertyValue create(PropertyValueData|array $data)
 * @method ProductPropertyValue update(int $id, PropertyValueData|array $data)
 */
class PropertyValueService extends ResourceService
{

    protected static string $model = ProductPropertyValue::class;

    protected static string $dataClass = PropertyValueData::class;

    protected static ?string $actionsConfigKey = 'red-jasmine.product.services.property.value.actions';

    public static ?string $actionPipelinesConfigPrefix = 'red-jasmine.product.services.property.value.pipelines';

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
