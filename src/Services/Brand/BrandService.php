<?php

namespace RedJasmine\Product\Services\Brand;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Product\Exceptions\BrandException;
use RedJasmine\Product\Models\Brand;
use RedJasmine\Product\Services\Brand\Data\BrandData;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @method  Brand create(BrandData|array $data)
 * @method  Brand update(int $id, BrandData|array $data)
 */
class BrandService extends ResourceService
{

    protected static string $modelClass = Brand::class;

    protected static string $dataClass = BrandData::class;


    protected static ?string $actionsConfigKey = '';

    public static function filters() : array
    {
        // TODO 多字段查询
        return [
            'name',
            'english_name',
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_show'),
        ];
    }

    /**
     * @param int $id
     *
     * @return Brand|null
     * @throws  ModelNotFoundException
     */
    public function isAllowUse(int $id) : ?Brand
    {
        return $this->query(false)->findOrFail($id);

    }

}
