<?php

namespace RedJasmine\Product\Actions\Products;


use Exception;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\Events\ProductCreatedEvent;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Pipelines\Products\ProductFillPipeline;
use RedJasmine\Product\Pipelines\Products\ProductValidatePipeline;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Helpers\ID\Snowflake;
use Throwable;

/**
 * 创建
 */
class ProductCreateAction extends AbstractProductAction
{

    protected ?string $pipelinesConfigKey = 'red-jasmine.product.pipelines.create';


    protected static array $commonPipes = [
        ProductValidatePipeline::class,
        ProductFillPipeline::class
    ];

    /**
     * 创建操作
     *
     * @param ProductDTO $productDTO
     *
     * @return Product
     * @throws Throwable
     */
    public function execute(ProductDTO $productDTO) : Product
    {
        $product = $this->initProduct();
        $product->setDTO($productDTO);
        $pipelines = $this->pipelines($product);
        $pipelines->before();
        try {
            DB::beginTransaction();
            $pipelines->then(fn($product) => $this->save($product));
            DB::commit();

        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        $pipelines->after();
        ProductCreatedEvent::dispatch($product);
        return $product;
    }

    protected function initProduct() : Product
    {
        $product = new Product();
        $product->setRelation('info', new ProductInfo());
        $product->setRelation('skus', collect([]));
        return $product;
    }

    /**
     * @param Product $product
     *
     * @return Product
     * @throws Exception
     */
    protected function save(Product $product) : Product
    {
        $this->service->linkageTime($product);
        $product->id      = $this->service->generateID();
        $product->creator = $this->service->getOperator();
        // 保存所有 SKU
        $product->skus->each(function (ProductSku $sku) {
            $sku->id         = $this->service->generateID();
            $sku->creator    = $this->service->getOperator();
            $sku->deleted_at = null;
        });
        $product->skus()->saveMany($product->skus);
        // 统计值
        $this->service->productCountFields($product);
        $product->info()->save($product->info);
        $product->save();
        return $product;
    }


}
