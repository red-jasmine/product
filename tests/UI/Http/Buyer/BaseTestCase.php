<?php

namespace RedJasmine\Product\Tests\UI\Http\Buyer;

use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Product\UI\Http\Buyer\ProductBuyerApiRoute;

class BaseTestCase extends TestCase
{

    protected function defineRoutes($router)
    {
        $router->group([ 'prefix' => 'api/buyer' ], function () {
            ProductBuyerApiRoute::routes();
        });
    }

}
