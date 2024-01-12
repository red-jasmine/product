<?php

namespace RedJasmine\Product\Actions\Products;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Pipelines\Products\ProductFillPipeline;
use RedJasmine\Product\Services\Product\Stock\ProductStockService;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 更新
 */
class ProductUpdateAction extends AbstractProductAction
{
    public function __construct(protected ProductStockService $productStockService)
    {
    }


    protected static array $commonPipes = [
        ProductFillPipeline::class,
    ];


    public function execute(int $id, ProductDTO $productDTO) : Product
    {

        $product = $this->service->find($id);
        $product->setDTO($productDTO);
        $product = $this->pipelines($product)
                        ->then(function ($product) {
                            return $this->update($product);
                        });
        //
        return $product;
        try {
            DB::beginTransaction();
            $product = $this->service->find($id);
            $product->setDTO($productDTO);
            // TODO
            DB::commit();

        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


        return $product;
    }

    /**
     * @param Product $product
     *
     * @return Product
     * @throws Exception|Throwable
     */
    public function update(Product $product) : Product
    {
        $this->updateStock($product);
        $this->updateSkus($product);
        $product->save();
        $product->info->save();
        return $product;
    }


    /**
     * @param Product $product
     *
     * @return void
     * @throws Throwable
     * @throws ProductStockException
     * @throws AbstractException
     */
    protected function updateStock(Product $product) : void
    {
        // 如果是改变了规格类型的情况下 那么就 重置库存
        if ($product->isDirty('is_multiple_spec')) {
            // 重置库存 库存为 传入数据
            $product->stock         = 0;
            $product->lock_stock    = 0;
            $product->channel_stock = 0;
            //  TODO 清空渠道库存
        }
        // 如果没有改变规格类型
        // 如果是 单规格商品 那么通过 设置库存操作完成
        if ($product->is_multiple_spec === BoolIntEnum::NO) {
            $this->productStockService->setStock($product->id, $product->stock, ProductStockChangeTypeEnum::SELLER);
        }
    }

    /**
     * @param Product $product
     *
     * @return void
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function updateSkus(Product $product) : void
    {
        if (!$this->isNeedUpdateSkus($product)) {
            return;
        }

        // 获取数据库中所有的SKU
        /**
         * @var Collection|array|Product[] $all
         */
        $all = $product->skus()->withTrashed()->get()->keyBy('properties');

        $product->skus->values()->each(function (Product $sku, $index) use ($product) {
            $isNew   = !$sku->id;
            $sku->id = $sku->id ?? $this->service->generateID();
            $this->service->copyProductAttributeToSku($product, $sku);
            $this->service->linkageTime($sku);
            if ($isNew === false) {
                $this->productStockService->setStock($sku->id, $sku->stock, ProductStockChangeTypeEnum::SELLER);
            }
        });
        $product->stock = $product->skus->sum('stock');
        $product->skus()->saveMany($product->skus);
        $all->each(function (Product $sku, $properties) use ($product) {
            if ($this->isCloseSku($sku, $product)) {
                $this->closeSku($sku);
            }
        });


    }


    protected function closeSku(Product $sku) : void
    {
        $sku->status     = ProductStatusEnum::DELETED;
        $sku->deleted_at = $sku->deleted_at ?? now();
        $sku->save();
    }

    protected function isCloseSku(Product $sku, Product $product) : bool
    {
        if ($sku->status === ProductStatusEnum::DELETED) {
            return false;
        }

        return !in_array($sku->properties, $product->skus->pluck('properties')->toArray(), true);

    }

    protected function isNeedUpdateSkus(Product $product) : bool
    {
        // 如果 如果修改了多规格类型, 如果是多规格商品 那么传了 skus 那么就进行更新
        return $product->is_multiple_spec === BoolIntEnum::YES || $product->isDirty('is_multiple_spec');
    }
}
