<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;


class Brand extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use WithOperatorModel;


    protected $casts = [
        'extends' => 'array'
    ];
}
