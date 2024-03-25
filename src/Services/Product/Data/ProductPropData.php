<?php

namespace RedJasmine\Product\Services\Product\Data;

use Illuminate\Support\Collection;
use RedJasmine\Support\DataTransferObjects\Data;
use Spatie\LaravelData\Optional;

class ProductPropData extends Data
{

    public int $pid;

    public string|Optional $name;
    /**
     * 属性值
     * @var int|array
     */
    public int|array $vid;

    /**
     * 可选项
     * @var Collection<ProductPropValueData>|null
     */
    public ?Collection $values = null;
    /**
     * 可选项
     * @var Collection<ProductPropValueData>|null
     */
    public ?Collection $options = null;


}
