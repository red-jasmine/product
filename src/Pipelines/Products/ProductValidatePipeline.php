<?php

namespace RedJasmine\Product\Pipelines\Products;

use Closure;
use JsonException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Services\Product\ProductValidate;

class ProductValidatePipeline
{
    /**
     * @param Product $product
     * @param Closure $next
     *
     * @return mixed
     * @throws JsonException
     */
    public function handle(Product $product, Closure $next)
    {
        $validatorService           = new ProductValidate($product->toArray());
        $value                      = $validatorService->validate();
        $product->info->sale_props  = $value['info']['sale_props'] ?? null;
        $product->info->basic_props = $value['info']['basic_props'] ?? null;
        return $next($product);
    }

}
