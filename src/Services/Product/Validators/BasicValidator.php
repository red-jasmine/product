<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Enums\Product\FreightPayerEnum;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Enums\Product\SubStockTypeEnum;
use RedJasmine\Product\Services\Brand\Rules\BrandAvailableRule;
use RedJasmine\Product\Services\Category\Rules\CategoryAvailableRule;
use RedJasmine\Product\Services\Category\Rules\SellerCategoryAvailableRule;
use RedJasmine\Product\Services\Product\ProductService;

use RedJasmine\Product\Services\Product\Validators\Rules\PriceRule;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Rules\NotZeroExistsRule;

class BasicValidator extends ValidatorCombiner
{
    public function setValidator(Validator $validator) : void
    {

        $validator->after(function (Validator $validator) {
            $data = $validator->getData();
            if ((boolean)(int)($data['is_multiple_spec'] ?? 0) === false) {
                $validator->setValue('skus', []);
                $validator->setValue('info.sale_props', []);
            }
        });


    }


    public function attributes() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('attribute'))->toArray();

    }

    public function fields() : array
    {
        return [
            'owner'              => [ 'attribute' => '卖家', 'rules' => [ 'sometimes', ] ],
            'owner_type'         => [ 'attribute' => '卖家', 'rules' => [ 'sometimes', ] ],
            'owner_id'           => [ 'attribute' => '卖家', 'rules' => [ 'sometimes', ] ],
            'category_id'        => [ 'attribute' => '类目', 'rules' => [ 'sometimes', 'nullable', new CategoryAvailableRule() ], ],
            'brand_id'           => [ 'attribute' => '品牌', 'rules' => [ 'sometimes', 'nullable', new BrandAvailableRule() ], ],
            'seller_category_id' => [ 'attribute' => '卖家分类', 'rules' => [ 'sometimes', 'nullable', new SellerCategoryAvailableRule() ], ],
            'product_type'       => [ 'attribute' => '商品类型', 'rules' => [ new Enum(ProductTypeEnum::class) ], ],
            'shipping_type'      => [ 'attribute' => '发货方式', 'rules' => [ new Enum(ShippingTypeEnum::class) ], ],
            'title'              => [ 'attribute' => '标题', 'rules' => [ 'required', 'max:60', 'min:2' ], ],
            'image'              => [ 'attribute' => '主图', 'rules' => [ 'sometimes', 'max:255' ], ],
            'barcode'            => [ 'attribute' => '条形码', 'rules' => [ 'sometimes', 'max:64' ], ],
            'outer_id'           => [ 'attribute' => '商品编码', 'rules' => [ 'sometimes', 'max:64' ], ],
            'stock'              => [ 'attribute' => '库存', 'rules' => [ 'required', 'integer', 'min:0', 'max:' . ProductService::MAX_QUANTITY ], ],
            'status'             => [ 'attribute' => '状态', 'rules' => [ 'required', new Enum(ProductStatusEnum::class) ], ],
            'price'              => [ 'attribute' => '价格', 'rules' => [ 'required', new PriceRule() ], ],
            'market_price'       => [ 'attribute' => '市场价', 'rules' => [ 'sometimes', new PriceRule(true, true) ], ],
            'cost_price'         => [ 'attribute' => '成本价', 'rules' => [ 'sometimes', new PriceRule(true, true) ], ],
            'is_multiple_spec'   => [ 'attribute' => '多规格', 'rules' => [ 'required', new Enum(BoolIntEnum::class) ], ],
            'min'                => [ 'attribute' => '起购量', 'rules' => [ 'sometimes', 'nullable', 'integer' ], ],
            'max'                => [ 'attribute' => '限购量', 'rules' => [ 'sometimes', 'nullable', 'integer' ], ],
            'multiple'           => [ 'attribute' => '倍数', 'rules' => [ 'sometimes', 'integer', 'min:1' ], ],
            'freight_payer'      => [ 'attribute' => '运费承担方', 'rules' => [ 'required', new Enum(FreightPayerEnum::class) ], ],
            'sub_stock'          => [ 'attribute' => '减库存方式', 'rules' => [ 'required', new Enum(SubStockTypeEnum::class) ], ],
            'stock'              => [ 'attribute' => '库存', 'rules' => [ 'required', 'integer', 'min:0' ], ],
            'fake_sales'         => [ 'attribute' => '虚拟销量', 'rules' => [ 'sometimes', 'integer', 'min:0' ], ],
            'postage_id'         => [ 'attribute' => '运费模板', 'rules' => [ 'sometimes' ], ],
            'delivery_time'      => [ 'attribute' => '发货时间', 'rules' => [ 'required', 'integer' ], ],
            'vip'                => [ 'attribute' => 'VIP', 'rules' => [ 'sometimes', 'integer', 'min:0' ], ],
            'points'             => [ 'attribute' => '积分', 'rules' => [ 'sometimes', 'integer' ], ],
            'is_hot'             => [ 'attribute' => '热销', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],
            'is_new'             => [ 'attribute' => '新品', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],
            'is_best'            => [ 'attribute' => '精品', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],
            'is_benefit'         => [ 'attribute' => '特惠', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],
            'info.keywords'      => [ 'attribute' => '关键字', 'rules' => [ 'sometimes', 'max:100' ], ],
            'info.description'   => [ 'attribute' => '描述', 'rules' => [ 'sometimes', 'max:500' ], ],
            'info.detail'        => [ 'attribute' => '详情', 'rules' => [], ],
            'info.images'        => [ 'attribute' => '图片集', 'rules' => [], ],
            'info.videos'        => [ 'attribute' => '视频集', 'rules' => [], ],
            'info.weight'        => [ 'attribute' => '重量', 'rules' => [], ],
            'info.width'         => [ 'attribute' => '宽度', 'rules' => [], ],
            'info.height'        => [ 'attribute' => '高度', 'rules' => [], ],
            'info.length'        => [ 'attribute' => '长度', 'rules' => [], ],
            'info.size'          => [ 'attribute' => '大小', 'rules' => [], ],
            'info.extends'       => [ 'attribute' => '扩展', 'rules' => [ 'sometimes', 'nullable', 'array' ], ],
            'info.tools'         => [ 'attribute' => '工具', 'rules' => [ 'sometimes', 'nullable', 'array' ], ],
            'info.remarks'       => [ 'attribute' => '备注', 'rules' => [], ],
            'info.basic_props'   => [ 'attribute' => '基础属性', 'rules' => [ 'sometimes', ], ],
            'info.sale_props'    => [ 'attribute' => '销售属性', 'rules' => [ 'sometimes', ], ],
        ];
    }


    public function skuRules() : array
    {
        $fields = $this->fields();
        return [
            'skus.*.properties'      => [ 'attribute' => '规格', 'rules' => [ 'sometimes', ], ],
            'skus.*.properties_name' => [ 'attribute' => '规格名称', 'rules' => [ 'sometimes', ], ],
            'skus.*.status'          => $fields['status'],
            'skus.*.price'           => $fields['price'],
            'skus.*.market_price'    => $fields['market_price'],
            'skus.*.cost_price'      => $fields['cost_price'],
            'skus.*.image'           => $fields['image'],
            'skus.*.barcode'         => $fields['barcode'],
            'skus.*.outer_id'        => $fields['outer_id'],
            'skus.*.stock'           => $fields['stock'],
            'skus.*.virtual_stock'   => $fields['stock'],
            'skus.*.safety_stock'    => $fields['stock'],
        ];
    }

    public function rules() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('rules'))->toArray();
    }


}
