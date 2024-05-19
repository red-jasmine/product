<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Optional;

class Property extends Data
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
     * @var Collection<PropertyValue>|null
     */
    public ?Collection $values = null;
    /**
     * 可选项
     * @var Collection<PropertyValue>|null
     */
    public ?Collection $options = null;


}
