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
use RedJasmine\Support\Helpers\ID\Snowflake;

use RedJasmine\Support\Traits\WithUserService;

class PropertyService
{
    use WithUserService;


    /**
     * @return PropertyQuery
     */
    public function propertyQuery() : PropertyQuery
    {
        return new PropertyQuery();
    }

    /**
     * @return int
     * @throws Exception
     */
    protected function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


    /**
     * @param string $name
     *
     * @return string
     * @throws ProductPropertyException
     */
    public function validateName(string $name) : string
    {
        // 不能包含特殊字符
        if (Str::contains($name, [ ':', ';', ',' ])) {
            throw new ProductPropertyException('属性名称不支持特殊符号');
        }

        return $name;

    }

    /**
     * 创建属性名
     *
     * @param string $name
     * @param array  $data
     *
     * @return ProductProperty
     * @throws Exception
     */
    public function createName(string $name, array $data = []) : ProductProperty
    {
        $this->validateName($name);
        $values    = [
            'pid'     => $this->buildID(),
            'name'    => $name,
            'sort'    => (int)($data['sort'] ?? 0),
            'extends' => $data['extends'] ?? [],
            'status'  => PropertyStatusEnum::from($data['status'] ?? PropertyStatusEnum::ENABLE->value),
        ];
        $validator = $this->nameValidator($values);
        $validator->validate();

        if ($this->getOperator()) {
            $values['creator_type'] = $this->getOperator()->getUserType();
            $values['creator_uid']  = $this->getOperator()->getUID();
        }
        $attributes = [
            'name' => $name,
        ];
        return ProductProperty::firstOrCreate($attributes, $values);

    }

    /**
     * 更新
     *
     * @param int   $pid
     * @param array $data
     *
     * @return ProductProperty
     */
    public function updateName(int $pid, array $data) : ProductProperty
    {
        $productProperty = ProductProperty::findOrFail($pid);
        $validator       = $this->nameValidator($data);
        $validator->validate();
        $safe = $validator->safe()->all();
        foreach ($safe as $key => $value) {
            $productProperty->setAttribute($key, $value);
        }
        $productProperty->withUpdater($this->getOperator());
        $productProperty->save();
        return $productProperty;
    }


    /**
     * @param int    $pid
     * @param string $name
     * @param array  $data
     *
     * @return ProductPropertyValue
     * @throws Exception
     */
    public function createValue(int $pid, string $name, array $data = []) : ProductPropertyValue
    {
        $this->validateName($name);
        $values = [
            'vid'        => $this->buildID(),
            'pid'        => $pid,
            'name'       => $name,
            'sort'       => (int)($data['sort'] ?? 0),
            'group_name' => $data['group_name'] ?? '',
            'extends'    => $data['extends'] ?? [],
            'status'     => PropertyStatusEnum::from($data['status'] ?? PropertyStatusEnum::ENABLE->value),
        ];

        $validator = $this->valueValidator($values);
        $validator->validate();

        if ($this->getOperator()) {
            $values['creator_type'] = $this->getOperator()->getUserType();
            $values['creator_uid']  = $this->getOperator()->getUID();
        }
        $attributes = [
            'pid'  => $pid,
            'name' => $name,
        ];
        return ProductPropertyValue::firstOrCreate($attributes, $values);
    }


    /**
     * 更新
     *
     * @param int   $vid
     * @param array $data
     *
     * @return ProductPropertyValue
     */
    public function updateValue(int $vid, array $data) : ProductPropertyValue
    {
        $productPropertyValue = ProductPropertyValue::findOrFail($vid);
        $validator            = $this->valueValidator($data);
        $validator->validate();
        $safe = $validator->safe()->all();
        foreach ($safe as $key => $value) {
            $productPropertyValue->setAttribute($key, $value);
        }
        $productPropertyValue->withUpdater($this->getOperator());
        $productPropertyValue->save();
        return $productPropertyValue;
    }


    public function nameValidator(array $data = []) : \Illuminate\Validation\Validator
    {
        return Validator::make($data, $this->nameRules(), [], $this->nameAttributes());
    }


    protected function nameRules() : array
    {
        return [
            'name'    => [ 'required', 'max:30' ],
            'extends' => [ 'sometimes', 'array' ],
            'sort'    => [ 'integer' ],
            'status'  => [ new Enum(PropertyStatusEnum::class) ],
        ];
    }

    protected function nameAttributes() : array
    {
        return [

            'name'    => '属性名',
            'extends' => '扩展字段',
            'status'  => '状态',
            'sort'    => '排序值',
        ];
    }

    public function valueValidator(array $data = []) : \Illuminate\Validation\Validator
    {
        return Validator::make($data, $this->valueRules(), [], $this->valueAttributes());
    }

    protected function valueRules() : array
    {
        return [
            'pid'        => [ 'required', 'integer', Rule::exists('product_properties', 'pid') ],
            'name'       => [ 'required', 'max:30' ],
            'extends'    => [ 'sometimes', 'array' ],
            'group_name' => [ 'sometimes', 'max:30' ],
            'sort'       => [ 'integer' ],
            'status'     => [ new Enum(PropertyStatusEnum::class) ],

        ];
    }

    protected function valueAttributes() : array
    {
        return [
            'pid'        => '属性ID',
            'name'       => '属性值',
            'extends'    => '扩展字段',
            'group_name' => '分租名称',
            'status'     => '状态',
            'sort'       => '排序值',
        ];
    }


    public array $propertyValueFields = [
        'vid', 'pid', 'name', 'group_name', 'sort', 'extends'
    ];


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
                                   ->select($this->propertyValueFields)
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
