<?php

namespace RedJasmine\Product\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\BrandRepository;

class ProductApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
    }

    public function boot() : void
    {
    }
}
