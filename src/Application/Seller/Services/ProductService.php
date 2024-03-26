<?php

namespace RedJasmine\Product\Application\Seller\Services;

class ProductService extends \RedJasmine\Product\Services\Product\ProductService
{
    protected function actions() : array
    {
        $actions = parent::actions();

        // TODO 重写部分配置

        return $actions;

    }


}
