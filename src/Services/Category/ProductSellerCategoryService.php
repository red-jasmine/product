<?php

namespace RedJasmine\Product\Services\Category;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Product\Models\ProductSellerCategory as Model;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use RedJasmine\Support\Rules\ParentIDValidationRule;
use RedJasmine\Support\Traits\WithUserService;

/**
 * 商品类目服务
 */
class ProductSellerCategoryService
{

    use WithUserService;


    public function find(int $id) : Model
    {
        return Model::findOrFail($id);
    }

    /**
     * to tree
     * @return array
     */
    public function tree() : array
    {

        $query = (new Model())->withQuery(function (Model $query) {
            return $query->where('status', CategoryStatusEnum::ENABLE)->owner($this->getOwner());
        });

        return $query->toTree();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     * @throws Exception
     */
    public function create(array $attributes) : Model
    {
        $productCategory     = new Model();
        $productCategory->id = Snowflake::getInstance()->nextId();
        $validator           = $this->validator($attributes);
        $validator->validate();
        $productCategory->fill($validator->safe()->all());
        $productCategory->withOwner($this->getOwner());
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
            'parent_id'  => [ 'required', 'integer', new NotZeroExistsRule('product_seller_categories', 'id'), ],
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
            'id'               => __('red-jasmine/product::product-seller-category.attributes.id'),
            'parent_id'        => __('red-jasmine/product::product-seller-category.attributes.parent_id'),
            'name'             => __('red-jasmine/product::product-seller-category.attributes.name'),
            'group_name'       => __('red-jasmine/product::product-seller-category.attributes.group_name'),
            'sort'             => __('red-jasmine/product::product-seller-category.attributes.sort'),
            'is_leaf'          => __('red-jasmine/product::product-seller-category.attributes.is_leaf'),
            'status'           => __('red-jasmine/product::product-seller-category.attributes.status'),
            'extends'          => __('red-jasmine/product::product-seller-category.attributes.extends'),
            'creator_type'     => __('red-jasmine/product::product-seller-category.attributes.creator_type'),
            'creator_uid'      => __('red-jasmine/product::product-seller-category.attributes.creator_uid'),
            'creator_nickname' => __('red-jasmine/product::product-seller-category.attributes.creator_nickname'),
            'updater_type'     => __('red-jasmine/product::product-seller-category.attributes.updater_type'),
            'updater_uid'      => __('red-jasmine/product::product-seller-category.attributes.updater_uid'),
            'updater_nickname' => __('red-jasmine/product::product-seller-category.attributes.updater_nickname'),
        ];
    }

    /**
     * 更新操作
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return Model
     */
    public function update(int $id, array $attributes) : Model
    {
        $productCategory = Model::findOrFail($id);
        $validator       = $this->validator($attributes);
        $validator->addRules([ 'parent_id' => [ new ParentIDValidationRule($id) ] ]);
        $validator->validated();
        $productCategory->fill($validator->safe()->all());
        $productCategory->withUpdater($this->getOperator());
        $productCategory->save();
        return $productCategory;
    }

    /**
     * 修改
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return Model
     */
    public function modify(int $id, array $attributes) : Model
    {
        $productCategory = Model::findOrFail($id);
        $validator       = $this->validator($attributes);
        $validator->addRules([ 'parent_id' => [ new ParentIDValidationRule($id) ] ]);
        $validator->setRules(Arr::only($validator->getRules(), array_keys($attributes)));
        $validator->validated();
        $productCategory->fill($validator->safe()->all());
        $productCategory->withUpdater($this->getOperator());
        $productCategory->save();
        return $productCategory;

    }
}
