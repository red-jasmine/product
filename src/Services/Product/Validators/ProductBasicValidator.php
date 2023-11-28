<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Enums\Product\ProductStatus;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;

class ProductBasicValidator extends AbstractProductValidator
{
    public function withValidator(Validator $validator) : void
    {


        $validator->after(function (Validator $validator) {

            $data = $validator->getData();


            if (blank($data['skus'] ?? [])) {
                $validator->addRules([
                                         'parent_id' => [],
                                         'has_skus'  => [],
                                         'is_sku'    => [],
                                     ]);
                $validator->setValue('parent_id', 0);
                $validator->setValue('has_skus', 0);
                $validator->setValue('is_sku', 1);
            }

        });


    }


    public function attributes() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('attribute'))->toArray();

    }

    public function rules() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('rules'))->toArray();
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
            'quantity'           => [ 'attribute' => '库存', 'rules' => [ 'required', 'integer', 'min:0' ], ],
            'status'             => [ 'attribute' => '状态', 'rules' => [ 'required', new Enum(ProductStatus::class) ], ],

            'price'        => [ 'attribute' => '价格', 'rules' => [ 'required', ], ],
            'market_price' => [ 'attribute' => '市场价', 'rules' => [ 'sometimes', ], ],
            'cost_price'   => [ 'attribute' => '成本价', 'rules' => [ 'sometimes', ], ],

            'min'      => [ 'attribute' => '起购量', 'rules' => [ 'sometimes', 'integer' ], ],
            'max'      => [ 'attribute' => '限购量', 'rules' => [ 'sometimes', 'integer' ], ],
            'multiple' => [ 'attribute' => '倍数', 'rules' => [ 'sometimes', 'integer' ], ],


            'freight_payer' => [ 'attribute' => '运费承担方', 'rules' => [], ],
            'sub_stock'     => [ 'attribute' => '减库存方式', 'rules' => [], ],
            'postage_id'    => [ 'attribute' => '运费模板', 'rules' => [], ],


            'info.desc'       => [ 'attribute' => '描述', 'rules' => [ 'sometimes', 'max:500' ], ],
            'info.web_detail' => [ 'attribute' => '电脑详情', 'rules' => [], ],
            'info.wap_detail' => [ 'attribute' => '手机详情', 'rules' => [], ],
            'info.images'     => [ 'attribute' => '图片集', 'rules' => [], ],
            'info.videos'     => [ 'attribute' => '视频集', 'rules' => [], ],
            'info.weight'     => [ 'attribute' => '重量', 'rules' => [], ],
            'info.width'      => [ 'attribute' => '宽度', 'rules' => [], ],
            'info.height'     => [ 'attribute' => '高度', 'rules' => [], ],
            'info.length'     => [ 'attribute' => '长度', 'rules' => [], ],
            'info.size'       => [ 'attribute' => '大小', 'rules' => [], ],
            'info.extends'    => [ 'attribute' => '扩展', 'rules' => [ 'sometimes', 'array' ], ],
            'info.tools'      => [ 'attribute' => '扩展', 'rules' => [ 'sometimes', 'tools' ], ],
            'info.remarks'    => [ 'attribute' => '备注', 'rules' => [], ],

            'info.basic_props' => [ 'attribute' => '扩展', 'rules' => [ 'sometimes', ], ],
            'info.sale_props'  => [ 'attribute' => '扩展', 'rules' => [ 'sometimes', ], ],
        ];

        $sku = [
            'skus.*.product_type'  => $fields['product_type'],
            'skus.*.shipping_type' => $fields['shipping_type'],
            'skus.*.title'         => $fields['title'],
            'skus.*.image'         => $fields['image'],
            'skus.*.barcode'       => $fields['barcode'],
            'skus.*.outer_id'      => $fields['outer_id'],
            'skus.*.quantity'      => $fields['quantity'],
            'skus.*.price'         => $fields['price'],
            'skus.*.market_price'  => $fields['market_price'],
            'skus.*.cost_price'    => $fields['cost_price'],
            'skus.*.min'           => $fields['min'],
            'skus.*.max'           => $fields['max'],
            'skus.*.multiple'      => $fields['multiple'],


            'skus.*.properties' => [ 'attribute' => '规格', 'rules' => [ 'sometimes', 'array' ], ],

        ];


        return array_merge($fields, $sku);
    }


}
