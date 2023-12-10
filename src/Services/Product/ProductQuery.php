<?php

namespace RedJasmine\Product\Services\Product;

use RedJasmine\Product\Models\Product;
use RedJasmine\Support\Traits\WithUserService;
use Spatie\QueryBuilder\AllowedFilter;

class ProductQuery
{

    public string $model = Product::class;

    use WithUserService;




}
