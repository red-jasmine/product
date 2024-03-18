<?php

namespace RedJasmine\Product\Actions\Products;

use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\DataTransferObjects\ProductModifyDTO;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Pipelines\Products\ProductModifyFillPipeline;
use RedJasmine\Product\Pipelines\Products\ProductValidatePipeline;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ProductModifyAction extends ProductUpdateAction
{
    protected static array $commonPipes = [
        ProductValidatePipeline::class,
        ProductModifyFillPipeline::class,
    ];


    /**
     * @param int                         $id
     * @param ProductModifyDTO|ProductDTO $productDTO
     *
     * @return Product
     * @throws AbstractException
     * @throws Throwable
     */
    public function execute(int $id, ProductModifyDTO|ProductDTO $productDTO) : Product
    {
        return $this->executeCore($id, $productDTO); // TODO: Change the autogenerated stub
    }


}