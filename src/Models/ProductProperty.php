<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;

class ProductProperty extends Model
{

    use HasDateTimeFormatter;

    use WithOperatorModel;


    protected $casts = [
        'extends' => 'array'
    ];
}
