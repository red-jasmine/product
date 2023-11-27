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
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Traits\WithUserService;

class PropertyService
{
    use WithUserService;

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
            $values['creator_type']     = $this->getOperator()->getUserType();
            $values['creator_uid']      = $this->getOperator()->getUID();
            $values['creator_nickname'] = $this->getOperator()->getNickname();
        }
        $attributes = [
            'name' => $name,
        ];
        return ProductProperty::firstOrCreate($attributes, $values);

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
            $values['creator_type']     = $this->getOperator()->getUserType();
            $values['creator_uid']      = $this->getOperator()->getUID();
            $values['creator_nickname'] = $this->getOperator()->getNickname();
        }
        $attributes = [
            'pid'  => $pid,
            'name' => $name,
        ];
        return ProductPropertyValue::firstOrCreate($attributes, $values);
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
}
