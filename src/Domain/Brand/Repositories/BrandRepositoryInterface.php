<?php

namespace RedJasmine\Product\Doamin\Brand\Repositories;

use RedJasmine\Product\Models\Brand;

interface BrandRepositoryInterface
{
    public function find(int $id) : Brand;

    public function store(Brand $brand) : Brand;

    public function update(Brand $brand) : void;
}
