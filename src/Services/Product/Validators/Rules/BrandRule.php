<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Services\Brand\BrandService;
use RedJasmine\Support\Exceptions\AbstractException;

class BrandRule extends AbstractRule
{
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (blank($value)) {
            return;
        }
        $service = app(BrandService::class);
        $service->disableRequest();
        try {
            $service->isAllowUse($value);
        } catch (AbstractException|ModelNotFoundException $exception) {
            $fail('品牌不可使用');
        }

    }


}
