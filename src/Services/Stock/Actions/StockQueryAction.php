<?php

namespace RedJasmine\Product\Services\Stock\Actions;

use RedJasmine\Support\Foundation\Service\Actions\QueryAction;

class StockQueryAction extends QueryAction
{
    protected function includes() : array
    {
        return [ 'skus' ];
    }


    protected function select() : array
    {

        return [
            'id',
            'stock',
            'title',
            'lock_stock',
            'safety_stock'
        ];
    }

    protected function fields() : array
    {
        return [
            'id',
            'stock',
            'lock_stock',
            'safety_stock',
            'skus.id',
            'skus.product_id',
            'skus.stock',
            'skus.lock_stock',
            'skus.safety_stock',
        ];
    }


}
