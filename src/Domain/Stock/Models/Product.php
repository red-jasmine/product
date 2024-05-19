<?php

namespace RedJasmine\Product\Domain\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Traits\HasDateTimeFormatter;


class Product extends Model
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    public $incrementing = false;


}
