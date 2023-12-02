<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Services\Category\ProductCategoryService;
use RedJasmine\Support\Exceptions\AbstractException;

class CategoryRule extends AbstractRule
{
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if(blank($value)){
            return;
        }
        $service = app(ProductCategoryService::class);
        try {
            $service->isAllowUse($value);
        } catch (AbstractException|ModelNotFoundException) {
            $fail('类目不可使用');
        }
    }


}
