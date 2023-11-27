<?php

namespace RedJasmine\Product\Services\Brand;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Product\Models\Brand;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Traits\WithUserService;

class BrandService
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
     * 品牌
     *
     * @param array $data
     *
     * @return Brand
     * @throws Exception
     */
    public function create(array $data) : Brand
    {
        $brand = Brand::where('name', $data['name'])->first();
        if (filled($brand)) {
            return $brand;
        }
        $validator = $this->validator($data);
        $validator->validate();
        $brand     = new Brand();
        $brand->id = $this->buildID();

        $brand->fill($validator->safe()->all());
        $brand->withCreator($this->getOperator());
        $brand->save();
        return $brand;

    }

    /**
     * 更新
     *
     * @param int   $id
     * @param array $data
     *
     * @return Brand
     */
    public function update(int $id, array $data) : Brand
    {
        $brand     = Brand::findOrFail($id);
        $validator = $this->validator($data);
        $validator->validate();
        $brand->fill($validator->safe()->all());
        $brand->withUpdater($this->getOperator());
        $brand->save();
        return $brand;
    }


    /**
     * 修改
     *
     * @param int   $id
     * @param array $data
     *
     * @return Brand
     */
    public function modify(int $id, array $data) : Brand
    {
        $brand     = Brand::findOrFail($id);
        $validator = $this->validator($data);
        $validator->setRules(Arr::only($validator->getRules(), array_keys($data)));
        $validator->validate();
        $brand->fill($validator->safe()->all());
        $brand->withUpdater($this->getOperator());
        $brand->save();
        return $brand;

    }

    public function validator(array $data) : \Illuminate\Validation\Validator
    {
        return Validator::make($data, $this->rules(), [], $this->attributes());
    }

    protected function attributes() : array
    {
        return [
            'name'    => '名称',
            'logo'    => 'Logo',
            'sort'    => '排序',
            'status'  => '状态',
            'extends' => '扩展字段',
        ];

    }

    protected function rules() : array
    {

        return [
            'name'    => [ 'required', 'max:100' ],
            'logo'    => [ 'sometimes', 'max:255' ],
            'sort'    => [ 'integer' ],
            'status'  => [ new Enum(BrandStatusEnum::class) ],
            'extends' => [ 'sometimes', 'array' ],
        ];

    }

}
