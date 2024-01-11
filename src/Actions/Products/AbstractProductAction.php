<?php

namespace RedJasmine\Product\Actions\Products;

use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Support\Foundation\Service\Action;

abstract class AbstractProductAction extends Action
{
    protected ?ProductService $service;


}
