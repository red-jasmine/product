<?php

namespace RedJasmine\Product\Business\Seller;


use Illuminate\Database\Eloquent\Builder;


class ProductService extends \RedJasmine\Product\Services\Product\ProductService
{


    public function queries() : ProductQuery
    {
        return new ProductQuery($this);
    }

    public function query() : Builder
    {
        $query = parent::query();

        $query->onlyOwner($this->getOwner());

        return $query;
    }


}
