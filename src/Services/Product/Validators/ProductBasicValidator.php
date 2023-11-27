<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;

class ProductBasicValidator extends AbstractProductValidator
{
    public function validator(array $data) : Validator
    {
        return \Illuminate\Support\Facades\Validator::make($data,
                                                           $this->rules(),
                                                           $this->messages());
    }

    public function attributes() : array
    {
        return [];

    }

    public function messages() : array
    {
        return [];
    }


    public function rules() : array
    {
        return [];
    }

    public function fields() : array
    {
        $fields = [
            'category_id'        => [ 'attribute' => '类目', 'rules' => [ 'sometimes', 'integer' ], ],
            'brand_id'           => [ 'attribute' => '品牌', 'rules' => [ 'sometimes', 'integer' ], ],
            'seller_category_id' => [ 'attribute' => '卖家分类', 'rules' => [ 'sometimes', 'integer' ], ],
            'product_type'       => [ 'attribute' => '商品类型', 'rules' => [ new Enum(ProductTypeEnum::class) ], ],
            'shipping_type'      => [ 'attribute' => '发货方式', 'rules' => [ new Enum(ShippingTypeEnum::class) ], ],
            'title'              => [ 'attribute' => '标题', 'rules' => [ 'required', 'max:60', 'min:2' ], ],
            'image'              => [ 'attribute' => '主图', 'rules' => [ 'sometimes', 'max:255' ], ],
            'barcode'            => [ 'attribute' => '条形码', 'rules' => [ 'sometimes', 'max:64' ], ],
            'outer_id'           => [ 'attribute' => '商品编码', 'rules' => [ 'sometimes', 'max:64' ], ],
            'quantity'           => [ 'attribute' => '库存', 'rules' => [ 'required', 'integer' ], ],

            'price'        => [ 'attribute' => '价格', 'rules' => [ 'required', ], ],
            'market_price' => [ 'attribute' => '市场价', 'rules' => [ 'sometimes', ], ],
            'cost_price'   => [ 'attribute' => '成本价', 'rules' => [ 'sometimes', ], ],

            'min'      => [ 'attribute' => '起购量', 'rules' => [ 'sometimes', 'integer' ], ],
            'max'      => [ 'attribute' => '限购量', 'rules' => [ 'sometimes', 'integer' ], ],
            'multiple' => [ 'attribute' => '倍数', 'rules' => [ 'sometimes', 'integer' ], ],


            'desc'       => [ 'attribute' => '描述', 'rules' => [], ],
            'web_detail' => [ 'attribute' => '电脑详情', 'rules' => [], ],
            'wap_detail' => [ 'attribute' => '手机详情', 'rules' => [], ],
            'images'     => [ 'attribute' => '图片集', 'rules' => [], ],
            'videos'     => [ 'attribute' => '视频集', 'rules' => [], ],
            'weight'     => [ 'attribute' => '重量', 'rules' => [], ],
            'width'      => [ 'attribute' => '宽度', 'rules' => [], ],
            'height'     => [ 'attribute' => '高度', 'rules' => [], ],
            'length'     => [ 'attribute' => '长度', 'rules' => [], ],
            'size'       => [ 'attribute' => '大小', 'rules' => [], ],

            'freight_payer' => [ 'attribute' => '运费承担方', 'rules' => [], ],
            'sub_stock'     => [ 'attribute' => '减库存方式', 'rules' => [], ],
            'postage_id'    => [ 'attribute' => '运费模板', 'rules' => [], ],
            'remarks'       => [ 'attribute' => '备注', 'rules' => [], ],
            'extends'       => [ 'attribute' => '扩展', 'rules' => [], ],

        ];

        $sku = [
            'skus.*.item_type'       => $fields['item_type'],
            'skus.*.shipping_type'   => $fields['shipping_type'],
            'skus.*.title'           => $fields['title'],
            'skus.*.image'           => $fields['image'],
            'skus.*.barcode'         => $fields['barcode'],
            'skus.*.outer_id'        => $fields['outer_id'],
            'skus.*.quantity'        => $fields['quantity'],
            'skus.*.price'           => $fields['price'],
            'skus.*.market_price'    => $fields['market_price'],
            'skus.*.cost_price'      => $fields['cost_price'],
            'skus.*.min'             => $fields['min'],
            'skus.*.max'             => $fields['max'],
            'skus.*.multiple'        => $fields['multiple'],
            'skus.*.desc'            => $fields['desc'],
            'skus.*.web_detail'      => $fields['web_detail'],
            'skus.*.wap_detail'      => $fields['wap_detail'],
            'skus.*.images'          => $fields['images'],
            'skus.*.videos'          => $fields['videos'],
            'skus.*.weight'          => $fields['weight'],
            'skus.*.width'           => $fields['width'],
            'skus.*.height'          => $fields['height'],
            'skus.*.length'          => $fields['length'],
            'skus.*.size'            => $fields['size'],
            'skus.*.item_props'      => $fields['item_props'],
            'skus.*.item_props_data' => $fields['item_props_data'],

        ];


        return array_merge($fields, $sku);
    }


}
