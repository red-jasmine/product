<?php

namespace RedJasmine\Product\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

class ProductPropDTO extends Data
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
     * @var DataCollection<ProductPropValueDTO>|null
     */
    public ?DataCollection $values = null;
    /**
     * 可选项
     * @var DataCollection<ProductPropValueDTO>|null
     */
    public ?DataCollection $options = null;


}
