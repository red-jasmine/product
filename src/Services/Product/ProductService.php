<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Services\Product\Builder\ProductBuilder;
use RedJasmine\Product\Services\Product\Contracts\ProductInterface;
use RedJasmine\Support\Traits\WithUserService;
use Throwable;

class ProductService
{
    use WithUserService;

    /**
     * 最大库存
     */
    public const MAX_QUANTITY = 999999999;


    /**
     *
     *
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function create(array $data) : Product
    {
        //

        // 保存数据
        DB::beginTransaction();
        try {
            // 生成商品ID

            $product     = new Product();
            $productInfo = new ProductInfo();

            $builder     = new ProductBuilder();
            $product->id = $product->id ?? $builder->generateID();

            $data = $builder->validate($data);


            $info = $data['info'];
            unset($data['info']);
            $skus = $data['skus'];
            unset($data['skus']);

            foreach ($data as $key => $value) {
                $product->setAttribute($key, $value);
            }

            foreach ($info as $infoKey => $infoValue) {
                $productInfo->setAttribute($infoKey, $infoValue);
            }

            $product->withOwner($this->getOwner());
            $product->withCreator($this->getOperator());

            $skus = collect($skus)->map(function ($sku) use ($product, $builder) {
                $skuModel     = new Product();
                $skuModel->id = $builder->generateID();

                $skuModel->has_skus           = 0;
                $skuModel->is_sku             = 1;
                $skuModel->parent_id          = $product->parent_id;
                $skuModel->title              = $product->title;
                $skuModel->product_type       = $product->product_type;
                $skuModel->shipping_type      = $product->shipping_type;
                $skuModel->title              = $product->title;
                $skuModel->category_id        = $product->category_id;
                $skuModel->seller_category_id = $product->seller_category_id;
                $skuModel->freight_payer      = $product->freight_payer;
                $skuModel->postage_id         = $product->postage_id;
                $skuModel->sub_stock          = $product->sub_stock;
                $skuModel->delivery_time      = $product->delivery_time;
                $skuModel->vip                = $product->vip;
                $skuModel->points             = $product->points;
                $skuModel->status             = $product->status;

                $skuModel->withOwner($this->getOwner());
                $skuModel->withCreator($this->getOperator());

                foreach ($sku as $key => $value) {
                    $skuModel->setAttribute($key, $value);
                }
                return $skuModel;
            });

            $product->save();
            $product->info()->save($productInfo);

            $product->skus()->saveMany($skus);

            collect($skus)->map(function ($sku) {
                $productInfo     = new ProductInfo();
                $productInfo->id = $sku->id;
                $productInfo->save();
            });
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $product;

    }


// 验证数据
    public
    function validateProduct($data)
    {
        //

    }


    public
    function update()
    {

    }
}
