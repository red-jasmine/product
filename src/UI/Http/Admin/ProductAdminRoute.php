<?php

namespace RedJasmine\Product\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\BrandController;

class ProductAdminRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::apiResource('brands', BrandController::class)->names('admin.product.brands');

        });
    }
}
