<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Services\Product\Builder\ProductBuilder;
use RedJasmine\Product\Services\Product\Stock\ProductStock;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Foundation\Service\WithUserService;
use Throwable;

class ProductService extends Service
{

    protected static ?string $actionsConfigKey = 'red-jasmine.product.actions';

    public function queries() : ProductQuery
    {
        return new ProductQuery($this);
    }

    protected ?ProductStock $stockService = null;

    public function stock() : ProductStock
    {
        if ($this->stockService) {
            return $this->stockService;
        }
        // 1、多规格 商品级 库存如何统计
        // 2、单规格 变成多规格商品时 库存如何处理
        // 3、
        $this->stockService = new ProductStock($this);
        return $this->stockService;
    }

    /**
     * @return Builder|Product
     */
    public function query() : Builder
    {
        return Product::query()->productable();
    }

    public function find(int $id) : Product
    {
        return $this->query()->findOrFail($id);
    }


    public string $model = Product::class;
    /**
     * 最大库存
     */
    public const MAX_QUANTITY = 9999999999;


    protected ?ProductBuilder $productBuilder = null;

    public function productBuilder() : ProductBuilder
    {
        if ($this->productBuilder) {
            return $this->productBuilder;
        }

        $this->productBuilder = new ProductBuilder();
        $this->productBuilder
            ->setOwner($this->getOwner())
            ->setOperator($this->getOperator());
        return $this->productBuilder;
    }


    /**
     * 创建商品
     *
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function createv2(array $data) : Product
    {
        // 验证数据
        $builder            = $this->productBuilder();
        $data['owner_type'] = $this->getOwner()->getType();
        $data['owner_id']   = $this->getOwner()->getID();

        $data               = $builder->validate($data);

        return $this->createSave($data);
    }


    /**
     * 更新
     *
     * @param int   $id
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function update(int $id, array $data) : Product
    {
        $product = $this->find($id);
        // 验证数据
        $builder            = $this->productBuilder();
        $data['owner_type'] = $product->owner_type;
        $data['owner_id']   = $product->owner_id;
        $data               = $builder->validate($data);
        return $this->updateSave($id, $data);

    }

    /**
     * 修改
     *
     * @param int   $id
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function modify(int $id, array $data) : Product
    {

        $product = $this->find($id);
        // 验证数据
        $builder            = $this->productBuilder();
        $data['owner_type'] = $product->owner_type;
        $data['owner_id']   = $product->owner_id;
        $data               = $builder->validateOnly($data);

        // 修改操作支持更新库存
        return $this->updateSave($id, $data);
    }


    /**
     * 删除
     *
     * @param int $id
     *
     * @return true
     * @throws AbstractException
     * @throws Throwable
     */
    public function delete(int $id) : true
    {
        try {
            DB::beginTransaction();
            $product = $this->find($id);
            $product->info->delete();
            $product->skus()->delete();
            $product->delete();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;
    }


    /**
     *  强制删除
     *
     * @param int $id
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function forceDelete(int $id) : bool
    {
        try {
            DB::beginTransaction();
            /**
             * @var Product $product
             */
            $product = $this->query()->onlyTrashed()->find($id);
            $product->info()->onlyTrashed()->forceDelete();
            $product->skus()->onlyTrashed()->forceDelete();
            $product->forceDelete();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;


    }

    /**
     * 恢复
     *
     * @param int $id
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function restore(int $id) : bool
    {
        try {
            DB::beginTransaction();
            /**
             * @var Product $product
             */
            $product = $this->query()->onlyTrashed()->find($id);
            $product->info()->onlyTrashed()->restore();
            $product->skus()->onlyTrashed()->where('status', '<>', ProductStatusEnum::DELETED->value)->restore();
            $product->restore();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;


    }


    /**
     * 设状态
     *
     * @param int               $id
     * @param ProductStatusEnum $productStatus
     *
     * @return bool
     */
    public function updateStatus(int $id, ProductStatusEnum $productStatus) : bool
    {
        $product                = $this->find($id);
        $product->status        = $productStatus;
        $product->modified_time = now();
        $product->updater       = $this->getOperator();
        $product->save();

        return true;
    }


    /**
     * 创建保存
     *
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    protected function createSave(array $data = []) : Product
    {
        $builder         = $this->productBuilder();
        $product         = new Product();
        $productInfo     = new ProductInfo();
        $product->id     = $builder->generateID();
        $productInfo->id = $product->id;
        // 保存数据
        DB::beginTransaction();
        try {
            // 生成商品ID
            $info = $data['info'] ?? [];
            unset($data['info']);
            $skus = $data['skus'] ?? [];
            unset($data['skus']);

            if (blank($product->owner_type)) {
                $product->owner = $this->getOwner();
            }
            if (blank($product->creator_type)) {
                $product->creator = $this->getOperator();
            }
            $product->updater = $this->getOperator();

            $product->is_multiple_spec = $data['is_multiple_spec'] ?? $product->is_multiple_spec;
            $product->is_sku           = ($product->is_multiple_spec === BoolIntEnum::YES) ? BoolIntEnum::NO : BoolIntEnum::YES;

            foreach ($data as $key => $value) {
                $product->setAttribute($key, $value);
            }
            foreach ($info as $infoKey => $infoValue) {
                $productInfo->setAttribute($infoKey, $infoValue);
            }

            $this->linkageTime($product);
            $product->modified_time = now();
            $product->save();
            $product->info()->save($productInfo);
            // 对多规格操作
            if ($product->is_multiple_spec === BoolIntEnum::YES) {

                $skus = collect($skus)->map(function ($sku) use ($product, $builder) {
                    $skuModel     = new ($this->model)();
                    $skuModel->id = $skuModel->id ?? $builder->generateID();
                    $this->copyProductAttributeToSku($product, $skuModel);
                    $this->linkageTime($product);
                    if (blank($skuModel->creator_type)) {
                        $skuModel->creator = $this->getOperator();
                    }
                    $skuModel->updater = $this->getOperator();
                    foreach ($sku as $key => $value) {
                        $skuModel->setAttribute($key, $value);
                    }
                    return $skuModel;
                })->keyBy('properties');
                $product->skus()->saveMany($skus);
            }

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $product;
    }


    /**
     *
     * @param int   $id
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    protected function updateSave(int $id, array $data = []) : Product
    {
        $builder     = $this->productBuilder();
        $product     = $this->find($id);
        $productInfo = $product->info;

        try {
            DB::beginTransaction();
            $product->spu_id = 0;
            if (blank($product->owner_type)) {
                $product->owner = $this->getOwner();
            }

            if (blank($product->creator_type)) {
                $product->creator = $this->getOperator();
            }
            $product->updater = $this->getOperator();

            $product->is_multiple_spec = $data['is_multiple_spec'] ?? $product->is_multiple_spec;
            $product->is_sku           = ($product->is_multiple_spec === BoolIntEnum::YES) ? BoolIntEnum::NO : BoolIntEnum::YES;
            // 生成商品ID
            $info = $data['info'] ?? [];
            unset($data['info']);
            $skus = $data['skus'] ?? [];
            unset($data['skus']);
            // 如果更变了 多规格类型
            // 库存服务

            // 如果是改变了规格类型的情况下 那么就 重置库存
            if ($product->isDirty('is_multiple_spec')) {
                // 重置库存 库存为 传入数据
                $product->stock         = 0;
                $product->lock_stock    = 0;
                $product->channel_stock = 0;
                // TODO
                // 清空渠道库存
            }
            if ($product->isDirty('is_multiple_spec') === false) {
                // 如果没有改变规格类型
                // 如果是 单规格商品 那么通过 设置库存操作完成
                if ($product->is_sku === BoolIntEnum::YES && filled($data['stock'] ?? null)) {
                    $this->setStock($product, $data['stock']);

                }
            }
            unset($data['stock']);
            // 设置其他属性
            foreach ($data as $key => $value) {
                $product->setAttribute($key, $value);
            }
            foreach ($info as $infoKey => $infoValue) {
                $productInfo->setAttribute($infoKey, $infoValue);
            }

            $this->linkageTime($product);

            // 如果 如果修改了多规格类型, 如果是多规格商品 那么传了 skus 那么就进行更新
            if ($product->isDirty('is_multiple_spec') || ($product->is_multiple_spec === BoolIntEnum::YES && filled($skus))) {
                // 获取数据库中所有的SKU
                /**
                 * @var array|Product[] $skuModelList
                 */
                $skuModelList = $product->skus()->withTrashed()->get()->keyBy('properties');
                /**
                 * @var array|\Illuminate\Support\Collection|Product[] $skus
                 */
                $skus = collect($skus)->map(function ($sku, $index) use ($product, $builder, $skuModelList) {


                    $skuModel = $skuModelList[$sku['properties']] ?? new ($this->model)();
                    $isNew    = false;
                    if (blank($skuModel->id)) {
                        $isNew = true;
                    }
                    $skuModel->id = $skuModel->id ?? $builder->generateID();
                    $this->copyProductAttributeToSku($product, $skuModel);
                    $this->linkageTime($product);

                    if (blank($skuModel->creator_type)) {
                        $skuModel->creator = $this->getOperator();
                    }
                    $skuModel->updater = $this->getOperator();
                    if ($isNew === false) {
                        // 如果 是新创建的
                        $this->setStock($skuModel, $sku['stock'], 'skus.' . $index . '.stock');
                        // 去除库存设置
                        unset($sku['stock']);
                    }
                    foreach ($sku as $key => $value) {
                        $skuModel->setAttribute($key, $value);
                    }

                    return $skuModel;
                });
                $skus = $skus->keyBy('properties');
                // 保存所有规格
                $product->skus()->saveMany($skus);
                if ($product->is_multiple_spec === BoolIntEnum::YES) {
                    $product->stock = $skus->sum('stock');
                }
                // 无效的SKU 进行关闭
                $keys = $skus->keys()->all();
                foreach ($skuModelList as $properties => $sku) {
                    if ($sku->status !== ProductStatusEnum::DELETED && !in_array($properties, $keys, true)) {
                        $sku->status     = ProductStatusEnum::DELETED;
                        $sku->deleted_at = $sku->deleted_at ?? now();
                        $sku->updater    = $this->getOperator();
                        $sku->save();
                    }
                }
            }
            $product->modified_time = now();
            $product->save();
            $product->info()->save($productInfo);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }

        return $product;
    }

    /**
     * 联动设置时间
     *
     * @param Product $product
     *
     * @return void
     */
    public function linkageTime(Product $product) : void
    {

        if (!$product->isDirty('status')) {
            return;
        }
        switch ($product->status) {
            case ProductStatusEnum::ON_SALE: // 在售
                $product->on_sale_time = now();
                break;
            case ProductStatusEnum::OUT_OF_STOCK: // 缺货
                $product->sold_out_time = now();
                break;
            case ProductStatusEnum::SOLD_OUT: // 售停
                $product->sold_out_time = now();
                break;
            case ProductStatusEnum::OFF_SHELF: // 下架
                $product->off_sale_time = now();
                break;
            case ProductStatusEnum::DELETED:
            case ProductStatusEnum::PRE_SALE:

                break;
            case ProductStatusEnum::FORBID:// 强制下架
                $product->on_sale_time  = null;
                $product->sold_out_time = null;
                $product->off_sale_time = $product->off_sale_time ?? now();
                break;

        }
    }

    /**
     * 复用字段
     *
     * @param Product $product
     * @param Product $sku
     *
     * @return void
     */
    public function copyProductAttributeToSku(Product $product, Product $sku) : void
    {
        $sku->owner_type = $product->owner_type;
        $sku->owner_id   = $product->owner_id;

        $sku->is_multiple_spec   = BoolIntEnum::NO;
        $sku->is_sku             = BoolIntEnum::YES;
        $sku->spu_id             = $product->id;
        $sku->title              = $product->title;
        $sku->product_type       = $product->product_type;
        $sku->shipping_type      = $product->shipping_type;
        $sku->title              = $product->title;
        $sku->category_id        = $product->category_id;
        $sku->seller_category_id = $product->seller_category_id;
        $sku->freight_payer      = $product->freight_payer;
        $sku->postage_id         = $product->postage_id;
        $sku->sub_stock          = $product->sub_stock;
        $sku->delivery_time      = $product->delivery_time;
        $sku->vip                = (int)($product->vip ?? 0);
        $sku->points             = (int)($product->points ?? 0);
        $sku->status             = $product->status;

        $sku->deleted_at = null;
    }


    /**
     * 设置库存
     *
     * @param Product $product
     * @param int     $stock
     * @param string  $field
     *
     * @return void
     * @throws ProductStockException
     */
    protected function setStock(Product $product, int $stock, string $field = 'stock') : void
    {
        $stockService = $this->stock();
        try {
            $stockService->setStock($product->id, $stock, ProductStockChangeTypeEnum::SELLER);
            $product->stock = $stock;
        } catch (Throwable $throwable) {
            throw new ProductStockException($throwable->getMessage(), 432,
                [
                    $field => [ $throwable->getMessage() ],
                ], 422
            );
        }
    }


}
