<?php

namespace RedJasmine\Product\Services\Category\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Services\Category\ProductCategoryService;
use RedJasmine\Support\Exceptions\AbstractException;

class CategoryAvailableRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (blank($value)) {
            return;
        }
        try {
            app(ProductCategoryService::class)->isAllowUse($value);
        } catch (AbstractException|ModelNotFoundException) {
            $fail('类目不可使用');
        }

    }
}
