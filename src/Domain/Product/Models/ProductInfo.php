<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;

class ProductInfo extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;


    protected $casts = [
        'basic_props' => 'array',
        'sale_props'  => 'array',
        'images'      => 'array',
        'videos'      => 'array',
        'tools'       => 'array',
        'expands'     => 'array',
    ];

}
