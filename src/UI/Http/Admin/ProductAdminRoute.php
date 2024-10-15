<?php

namespace RedJasmine\Product\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\BrandController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\CategoryController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\ProductController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\PropertyController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\PropertyGroupController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\PropertyValueController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\GroupController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\SeriesController;
use RedJasmine\Product\UI\Http\Admin\Api\Controllers\SkuController;

class ProductAdminRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::apiResource('brands', BrandController::class)->names('admin.product.brands');

            Route::get('categories/tree', [ CategoryController::class, 'tree' ])->name('admin.product.categories.tree');
            Route::apiResource('categories', CategoryController::class)->names('admin.product.categories');


            Route::get('groups/tree', [ GroupController::class, 'tree' ])->name('admin.product.groups.tree');
            Route::apiResource('groups', GroupController::class)->names('admin.product.groups');


            Route::apiResource('property/properties', PropertyController::class)->names('admin.product.property.properties');
            Route::apiResource('property/values', PropertyValueController::class)->names('admin.product.property.values');
            Route::apiResource('property/groups', PropertyGroupController::class)->names('admin.product.property.groups');


            Route::apiResource('products', ProductController::class)->names('admin.product.products');

            Route::get('skus/logs', [ SkuController::class, 'logs' ])->name('admin.product.skus.logs');
            Route::post('skus/{id}', [ SkuController::class, 'action' ])->name('admin.product.skus.action');
            Route::apiResource('skus', SkuController::class)->names('admin.product.skus');


            Route::apiResource('series', SeriesController::class)->names('admin.product.series');

        });
    }
}
