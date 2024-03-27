<?php

namespace RedJasmine\Product\Services\Category\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Services\Category\ProductSellerCategoryService;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Exceptions\AbstractException;

class SellerCategoryAvailableRule implements ValidationRule, DataAwareRule
{

    protected $data;

    public function setData(array $data)
    {
        $this->data = $data;
    }


    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {

        if (isset($this->data['owner'])) {
            $owner = UserData::from($this->data['owner']);
        } else {
            $owner = UserData::from([ 'type' => $this->data['owner_type'], 'id' => $this->data['owner_id'], ]);
        }

        try {
            app(ProductSellerCategoryService::class)->withQuery(function ($query) use ($owner) {
                $query->onlyOwner($owner);
            })->isAllowUse((int)$value);
        } catch (AbstractException|ModelNotFoundException $throwable) {
            $fail('当前分类不允许使用');
        }

    }
}
