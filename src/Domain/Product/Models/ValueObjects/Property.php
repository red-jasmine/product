<?php

namespace RedJasmine\Product\Domain\Product\Models\ValueObjects;

use Illuminate\Support\Collection;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class Property extends ValueObject
{

    /**
     * 属性ID
     * @var int
     */
    public int $pid;

    public string $name;
    /**
     * @var Collection<PropValue>
     */
    public Collection $values;


}
