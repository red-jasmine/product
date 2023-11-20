<?php

namespace RedJasmine\Product\Http\Seller\Controllers;

use Illuminate\Support\Facades\Route;

class Routes
{

    public static function web() : void
    {
    }

    public static function api() : void
    {
        Route::group(
            [
                'prefix'    => 'product',
                'namespace' => 'RedJasmine\Product\Http\Seller\Controllers\Api'
            ],
            function () {


                Route::group([ 'prefix' => 'product-categories' ], function () {

                    Route::get('{id}', 'ProductCategoryController@show');
                    Route::get('/', 'ProductCategoryController@index');
                });

            });

    }

}
