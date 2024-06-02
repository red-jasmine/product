<?php

namespace RedJasmine\Product\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\BrandController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\CategoryController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\ProductController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\PropertyController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\PropertyGroupController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\PropertyValueController;

class ProductAdminRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::apiResource('brands', BrandController::class)->names('admin.product.brands');
            Route::apiResource('products', ProductController::class)->names('admin.product.products');


            Route::get('categories/tree', [ CategoryController::class, 'tree' ])->name('admin.product.categories.tree');
            Route::get('categories', [ CategoryController::class, 'index' ])->name('admin.product.categories.index');
            Route::get('categories/{category}', [ CategoryController::class, 'show' ])->name('admin.product.categories.show');


            Route::apiResource('property/properties', PropertyController::class)->names('admin.product.property.properties');
            Route::apiResource('property/values', PropertyValueController::class)->names('admin.product.property.values');
            Route::apiResource('property/groups', PropertyGroupController::class)->names('admin.product.property.groups');

        });
    }
}
