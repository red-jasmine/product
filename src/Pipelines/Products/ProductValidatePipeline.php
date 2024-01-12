<?php

namespace RedJasmine\Product\Pipelines\Products;

use Closure;
use RedJasmine\Product\DataTransferObjects\ProductModifyDTO;
use RedJasmine\Product\DataTransferObjects\ProductPropDTO;
use RedJasmine\Product\DataTransferObjects\ProductSkuDTO;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Validators\ProductValidator;


class ProductValidatePipeline
{

    public function handle(Product $product, Closure $next)
    {
        /**
         *
         */
        $productDTO = $product->getDTO();

        $validatorService = new ProductValidator($productDTO);
        if ($productDTO instanceof ProductModifyDTO) {
            $value = $validatorService->validateOnly();
        } else {
            $value = $validatorService->validate();
        }

        if (filled($value['info']['sale_props'] ?? null)) {
            $productDTO->info->saleProps = ProductPropDTO::collection($value['info']['sale_props']);
        }
        if (filled($value['info']['sale_props'] ?? null)) {
            $productDTO->info->basicProps = ProductPropDTO::collection($value['info']['basic_props']);
        }

        if (filled($value['skus'] ?? null)) {
            $productDTO->skus = ProductSkuDTO::collection($value['skus']);
        }

        return $next($product);
    }

}
