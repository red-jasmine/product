<?php

namespace RedJasmine\Product\Domain\Brand\Repositories;



use RedJasmine\Product\Domain\Brand\Models\Brand;

interface BrandRepositoryInterface
{
    public function find(int $id) : Brand;

    public function store(Brand $brand) : Brand;

    public function update(Brand $brand) : void;
}
