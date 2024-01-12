<?php

namespace RedJasmine\Product\Actions\Products;


use Exception;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Pipelines\Products\ProductFillPipeline;
use RedJasmine\Product\Pipelines\Products\ProductValidatePipeline;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Helpers\ID\Snowflake;
use Throwable;

class ProductCreateAction extends AbstractProductAction
{

    protected ?string $pipelinesConfigKey = 'red-jasmine.product.pipelines.create';


    protected static array $commonPipes  = [
        ProductFillPipeline::class,
        ProductValidatePipeline::class
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

        try {
            DB::beginTransaction();
            $product = $this->initProduct();
            $product->setDTO($productDTO);
            $this->pipelines($product)->then(fn($product) => $this->save($product));
            DB::commit();
            return $product;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

        return Product::make();
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
        $product->id = $this->generateID();
        $product->save();
        $product->info()->save($product->info);
        if ($product->is_multiple_spec === BoolIntEnum::YES) {
            $product->skus->each(function (Product $sku) use ($product) {
                $sku->id = $this->generateID();
                $this->service->copyProductAttributeToSku($product, $sku);
                $this->service->linkageTime($sku);
            });
            $product->stock = $product->skus->sum('stock');
            $product->skus()->saveMany($product->skus);
        }

        return $product;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function generateID() : int
    {
        return Snowflake::getInstance()->nextId();
    }

}
