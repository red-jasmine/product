<?php

namespace RedJasmine\Product\Actions\Products;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Product\Events\ProductUpdatedEvent;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Pipelines\Products\ProductFillPipeline;
use RedJasmine\Product\Pipelines\Products\ProductValidatePipeline;
use RedJasmine\Product\Services\Product\Stock\ProductStockService;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 更新
 */
class ProductUpdateAction extends AbstractProductAction
{

    /**
     * 基础管道
     * @var array|string[]
     */
    protected static array $commonPipes = [
        ProductValidatePipeline::class,
        ProductFillPipeline::class,
    ];

    public function __construct(protected ProductStockService $productStockService)
    {
    }

    /**
     * @param int        $id
     * @param ProductDTO $productDTO
     *
     * @return Product
     * @throws AbstractException
     * @throws Throwable
     */
    public function execute(int $id, ProductDTO $productDTO) : Product
    {
        return $this->executeCore($id, $productDTO);
    }

    /**
     * @param int $id
     * @param     $productDTO
     *
     * @return mixed
     * @throws AbstractException
     * @throws Throwable
     */
    protected function executeCore(int $id, $productDTO) : Product
    {
        $product = $this->service->find($id);

        $product->setDTO($productDTO);
        $pipelines = $this->pipelines($product);
        $pipelines->before();
        try {
            DB::beginTransaction();
            $pipelines->then(fn($product) => $this->update($product));
            DB::commit();
            $product->skus = $product->skus->values();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        $pipelines->after();
        if ($product->isDirty()) {
            ProductUpdatedEvent::dispatch($product);
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
        if ($product->isDirty() || $product->info->isDirty()) {
            $product->updater       = $this->service->getOperator();
            $product->modified_time = now();
        }
        $this->service->linkageTime($product);
        $this->updateSkus($product);
        $product->save();
        $product->info->save();
        return $product;
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

        // 如果是改变了规格类型的情况下 那么就 重置库存
        if ($product->isDirty('is_multiple_spec')) {
            $product->lock_stock = 0;
        }
        // 获取数据库中所有的SKU
        /**
         * @var Collection|array|Product[] $all
         */
        $all = $product->skus()->withTrashed()->get()->keyBy('properties');

        if ($product->is_multiple_spec === BoolIntEnum::NO) {
            $product->info->sale_props = null;
        }

        // 设置 正常的SKU
        $product->skus->values()->each(function (ProductSku $sku, $index) use ($product) {
            $isNew           = !$sku->id;
            $sku->id         = $sku->id ?? $this->service->generateID();
            $sku->deleted_at = null;
            if ($isNew === false) {
                try {
                    $this->productStockService->setStock($sku->id, $sku->stock, ProductStockChangeTypeEnum::SELLER);
                } catch (\Throwable $throwable) {
                    throw new ProductStockException($throwable->getMessage(), 432, [ "skus.{$index}.stock" => [ $throwable->getMessage() ], ], 422);
                }
            } else {
                $sku->creator = $this->service->getOperator();
            }
            if ($sku->isDirty()) {
                $product->modified_time = now();
                $sku->updater           = $this->service->getOperator();
            }
        });

        $product->skus()->saveMany($product->skus);
        // 统计值
        $this->service->productCountFields($product);
        $all->each(function (ProductSku $sku, $properties) use ($product) {
            if ($this->isCloseSku($sku, $product)) {
                $this->closeSku($sku);
            }
        });
    }

    protected function isCloseSku(ProductSku $sku, Product $product) : bool
    {
        if ($sku->status === ProductStatusEnum::DELETED) {
            return false;
        }
        if ($product->is_multiple_spec === BoolIntEnum::YES) {
            return !in_array($sku->properties, $product->skus->pluck('properties')->toArray(), true);
        }
        // 如果是单规格
        return filled($sku->properties);
    }

    protected function closeSku(ProductSku $sku) : void
    {
        $sku->status     = ProductStatusEnum::DELETED;
        $sku->deleted_at = $sku->deleted_at ?? now();
        $sku->save();
    }
}
