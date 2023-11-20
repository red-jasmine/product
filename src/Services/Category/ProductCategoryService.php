<?php

namespace RedJasmine\Product\Services\Category;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Product\Models\ProductCategory;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use RedJasmine\Support\Rules\ParentIDValidationRule;
use RedJasmine\Support\Traits\WithUserService;

/**
 * 商品类目服务
 */
class ProductCategoryService
{

    use WithUserService;


    /**
     * @param array $attributes
     *
     * @return ProductCategory
     * @throws Exception
     */
    public function create(array $attributes) : ProductCategory
    {
        $productCategory     = new ProductCategory();
        $productCategory->id = Snowflake::getInstance()->nextId();

        $validator = $this->validator($attributes);
        $validator->validate();

        $productCategory->fill($validator->safe()->all());
        $productCategory->withCreator($this->getOperator());

        $productCategory->save();

        return $productCategory;
    }

    public function validator(array $attributes) : \Illuminate\Validation\Validator
    {
        return Validator::make($attributes, $this->rules(), [], $this->attributes());
    }

    protected function rules() : array
    {

        return [
            'id'         => [],
            'parent_id'  => [
                'required',
                'integer',
                new NotZeroExistsRule('product_categories', 'id'),
            ],
            'name'       => [ 'required', 'max:100' ],
            'group_name' => [ 'sometimes', 'max:100' ],
            'sort'       => [ 'integer' ],
            'is_leaf'    => [ 'required', 'boolean' ],
            'status'     => [ new Enum(CategoryStatusEnum::class) ],
            'extends'    => [ 'sometimes', 'array' ],
        ];

    }

    protected function attributes() : array
    {
        return [
            'id'               => __('red-jasmine/product::product-category.attributes.id'),
            'parent_id'        => __('red-jasmine/product::product-category.attributes.parent_id'),
            'name'             => __('red-jasmine/product::product-category.attributes.name'),
            'group_name'       => __('red-jasmine/product::product-category.attributes.group_name'),
            'sort'             => __('red-jasmine/product::product-category.attributes.sort'),
            'is_leaf'          => __('red-jasmine/product::product-category.attributes.is_leaf'),
            'status'           => __('red-jasmine/product::product-category.attributes.status'),
            'extends'          => __('red-jasmine/product::product-category.attributes.extends'),
            'creator_type'     => __('red-jasmine/product::product-category.attributes.creator_type'),
            'creator_uid'      => __('red-jasmine/product::product-category.attributes.creator_uid'),
            'creator_nickname' => __('red-jasmine/product::product-category.attributes.creator_nickname'),
            'updater_type'     => __('red-jasmine/product::product-category.attributes.updater_type'),
            'updater_uid'      => __('red-jasmine/product::product-category.attributes.updater_uid'),
            'updater_nickname' => __('red-jasmine/product::product-category.attributes.updater_nickname'),
        ];
    }

    /**
     * 更新操作
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return ProductCategory
     */
    public function update(int $id, array $attributes) : ProductCategory
    {
        $productCategory = ProductCategory::findOrFail($id);
        $validator       = $this->validator($attributes);
        $validator->addRules([ 'parent_id' => [ new ParentIDValidationRule($id) ] ]);
        $validator->validated();
        $productCategory->fill($validator->safe()->all());
        $productCategory->withUpdater($this->getOperator());
        $productCategory->save();
        return $productCategory;
    }
}
