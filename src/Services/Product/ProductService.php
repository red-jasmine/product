<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Enums\Product\ProductStatus;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Services\Product\Builder\ProductBuilder;
use RedJasmine\Support\Traits\Services\HasQueryBuilder;
use RedJasmine\Support\Traits\WithUserService;
use Throwable;

class ProductService
{
    use WithUserService;


    public string $model = Product::class;
    /**
     * 最大库存
     */
    public const MAX_QUANTITY = 999999999;


    use HasQueryBuilder;

    public function lists() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()->paginate();
    }

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
        return $this->save(null, $data);

    }


    /**
     * 商品更新
     *
     * @param int   $id
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function update(int $id, array $data) : Product
    {

        // 验证数据
        $builder = new ProductBuilder();
        $data    = $builder->validate($data);

        return $this->save($id, $data);
    }


    /**
     * @param int|null $id
     * @param array    $data
     *
     * @return Product
     * @throws Throwable
     */
    protected function save(?int $id = null, array $data = []) : Product
    {

        $builder = new ProductBuilder();
        if (filled($id)) {
            $product     = Product::findOrFail($id);
            $productInfo = $product->info;
        } else {
            $product = new Product();

            $productInfo = new ProductInfo();
        }
        $product->id     = $product->id ?? $builder->generateID();
        $productInfo->id = $product->id;
        // 保存数据
        DB::beginTransaction();
        try {
            // 生成商品ID
            $info = $data['info'] ?? [];
            unset($data['info']);
            $skus = $data['skus'] ?? [];
            unset($data['skus']);
            foreach ($data as $key => $value) {
                $product->setAttribute($key, $value);
            }
            foreach ($info as $infoKey => $infoValue) {
                $productInfo->setAttribute($infoKey, $infoValue);
            }
            if (blank($product->owner_type)) {
                $product->withOwner($this->getOwner());
            }
            if (blank($product->creator_type)) {
                $product->withCreator($this->getOperator());
            }

            $product->withUpdater($this->getOperator());


            $product->save();
            $product->info()->save($productInfo);


            // 对多规格操作
            if (filled($data['has_skus'] ?? null)) {

                // 获取数据库中所有的SKU
                /**
                 * @var array|Product[] $skuModelList
                 */
                $skuModelList = $product->skus()
                                        ->withTrashed()
                                        ->with([ 'info' => function ($query) {
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
                    $skuModel->owner_type         = $product->owner_type;
                    $skuModel->owner_uid          = $product->owner_uid;
                    $skuModel->deleted_at         = null;

                    if (blank($product->creator_type)) {
                        $product->withCreator($this->getOperator());
                    }
                    $skuModel->withUpdater($this->getOperator());

                    foreach ($sku as $key => $value) {
                        $skuModel->setAttribute($key, $value);
                    }
                    return $skuModel;
                })->keyBy('properties');

                $keys = $skus->keys()->all();
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
            }

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $product;
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function modify(int $id, array $data) : Product
    {
        $builder = new ProductBuilder();
        $data    = $builder->validateOnly($data);

        return $this->save($id, $data);
    }
}
