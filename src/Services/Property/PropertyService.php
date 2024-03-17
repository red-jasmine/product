<?php

namespace RedJasmine\Product\Services\Property;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Property\PropertyStatusEnum;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Product\Models\ProductPropertyValue;
use RedJasmine\Product\Services\Property\Query\PropertyQuery;
use RedJasmine\Product\Services\Property\Query\PropertyValueQuery;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;


class PropertyService extends Service
{

    public function value() : PropertyValueService
    {
        return app(PropertyValueService::class);
    }

    public function group() : PropertyGroupService
    {
        return app(PropertyGroupService::class);
    }

    public function name() : PropertyNameService
    {
        return app(PropertyNameService::class);
    }


    /**
     * @return PropertyQuery
     */
    public function propertyQuery() : PropertyQuery
    {
        return new PropertyQuery();
    }

    /**
     * @return PropertyValueQuery
     */
    public function valueQuery() : PropertyValueQuery
    {
        return new PropertyValueQuery();
    }

    /**
     * 查询所有
     *
     * @param int $pid
     *
     * @return ProductPropertyValue[]|Collection
     */
    public function values(int $pid) : Collection|array
    {
        return ProductPropertyValue::available()
                                   ->select([
                                                'vid', 'pid', 'name', 'group_name', 'sort', 'extends'
                                            ])
                                   ->where('pid', $pid)
                                   ->orderBy('sort', 'desc')
                                   ->get();
    }


    /**
     * @throws ProductPropertyException
     */
    public function validateProps(array $props = []) : array
    {
        if (blank($props)) {
            return [];
        }
        // 查询属性
        $allPid               = collect($props)->pluck('pid')->values()->all();
        $allVid               = collect($props)->pluck('vid')->values()->all();
        $allProductProperties = ProductProperty::select([ 'pid', 'name' ])->find($allPid)
                                               ->keyBy('pid');

        $allVid = array_merge([], ...$allVid);
        // 查询值
        if (filled($allVid)) {
            $allProductPropertyValues = ProductPropertyValue::select([ 'vid', 'pid', 'name' ])->find($allVid);
        } else {
            $allProductPropertyValues = collect();
        }


        foreach ($props as &$item) {
            try {
                $item['name'] = $allProductProperties[$item['pid']]->name;
            } catch (\Throwable $throwable) {
                throw new ProductPropertyException('属性不存在');
            }

            $item['values'] = $allProductPropertyValues
                ->where('pid', $item['pid'])
                ->values()
                ->map(function ($item) {
                    unset($item['pid']);
                    return $item;
                })->toArray();


            if (count($item['values']) !== count($item['vid'] ?? [])) {
                throw new ProductPropertyException('属性值存在错误');
            }
            // 对比差值
            $result = collect($item['vid'])->diff(collect($item['values'])->pluck('vid'));
            if ($result->count() > 0) {
                throw new ProductPropertyException('属性值存在错误');
            }

        }

        return $props;
    }
}
