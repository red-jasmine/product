<?php

namespace RedJasmine\Product\Services\Product\Data;

use RedJasmine\Support\DataTransferObjects\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

class ProductPropData extends Data
{

    public int             $pid;
    public string|Optional $name;
    /**
     * 属性值
     * @var int|array
     */
    public int|array $vid;

    /**
     * 可选项
     * @var DataCollection<ProductPropValueData>|null
     */
    public ?DataCollection $values = null;
    /**
     * 可选项
     * @var DataCollection<ProductPropValueData>|null
     */
    public ?DataCollection $options = null;


}
