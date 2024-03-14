<?php

namespace RedJasmine\Product\Services\Property\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class PropertyTitleRule implements ValidationRule
{
    /**
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {

        // 不能包含特殊字符
        if (Str::contains($value, [ ':', ';', ',' ])) {
            $fail('属性名称不支持特殊符号');
        }
    }
}
