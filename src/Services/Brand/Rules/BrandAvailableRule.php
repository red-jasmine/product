<?php

namespace RedJasmine\Product\Services\Brand\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Services\Brand\BrandService;
use RedJasmine\Support\Exceptions\AbstractException;

class BrandAvailableRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (blank($value)) {
            return;
        }

        try {
            app(BrandService::class)->isAllowUse($value);
        } catch (AbstractException|ModelNotFoundException $exception) {
            $fail('品牌不可使用');
        }
    }
}
