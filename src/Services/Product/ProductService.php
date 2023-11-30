<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Enums\Product\ProductStatus;
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
     * 创建商品
     *
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function create(array $data) : Product
    {
        // 验证数据
        $builder = new ProductBuilder();
        $data    = $builder->validate($data);
        // 保存数据
        DB::beginTransaction();
        try {
            // 生成商品ID

            $product     = new Product();
            $productInfo = new ProductInfo();

            $product->id = $product->id ?? $builder->generateID();

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


    /**
     * @throws Throwable
     */
    public function update(int $id, array $data) : Product
    {

        $product     = Product::findOrFail($id);
        $productInfo = $product->info;
        // 验证数据
        $builder = new ProductBuilder();
        $data    = $builder->validate($data);

        // 保存数据
        DB::beginTransaction();
        try {
            // 生成商品ID
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
            $product->withUpdater($this->getOperator());

            // 获取数据库中所有的SKU
            /**
             * @var array|Product[] $skuModelList
             */
            $skuModelList = $product->skus()
                                    ->withTrashed()
                                    ->with([ 'info' => function ( $query) {
                                        $query->withTrashed();
                                    } ])
                                    ->get()
                                    ->keyBy('properties');


            $skus = collect($skus)->map(function ($sku) use ($product, $builder, $skuModelList) {

                $skuModel     = $skuModelList[$sku['properties']] ?? new Product();
                $skuModel->id = $skuModel->id ?? $builder->generateID();

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
                $skuModel->deleted_at         = null;
                if (blank($skuModel->owner_type)) {
                    $skuModel->withOwner($this->getOwner());
                    $skuModel->withCreator($this->getOperator());
                }
                $skuModel->withUpdater($this->getOperator());

                foreach ($sku as $key => $value) {
                    $skuModel->setAttribute($key, $value);
                }
                return $skuModel;
            })->keyBy('properties');

            $keys = $skus->keys()->all();
            $product->save();
            $product->info()->save($productInfo);
            $product->skus()->saveMany($skus);

            collect($skus)->each(function ($sku) {
                /**
                 * @var Product $sku
                 */
                $productInfo             = $sku->info ?? new ProductInfo();
                $productInfo->id         = $sku->id;
                $productInfo->deleted_at = null;
                $productInfo->save();

            });
            // 无效的SKU 进行关闭

            foreach ($skuModelList as $properties => $sku) {
                if (!in_array($properties, $keys, true)) {
                    $sku->status     = ProductStatus::DELETED;
                    $sku->deleted_at = now();
                    $sku->withUpdater($this->getOperator());
                    $sku->info->deleted_at = now();
                    $sku->save();
                    $sku->info->save();
                }
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $product;
    }
}
