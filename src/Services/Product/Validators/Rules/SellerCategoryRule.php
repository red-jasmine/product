<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Services\Category\ProductSellerCategoryService;
use RedJasmine\Support\DataTransferObjects\UserDTO;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Helpers\User\UserObject;

/**
 * 卖家分类验证规则
 */
class SellerCategoryRule extends AbstractRule
{


    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (blank($value)) {
            return;
        }

        $service = new ProductSellerCategoryService;
        $service->disableRequest();
        $service->setOwner(UserDTO::from([ 'type' => $this->data['owner_type'], 'id' => $this->data['owner_id'], ]));
        try {
            $sellerCategory = $service->isAllowUse($value);
        } catch (AbstractException|ModelNotFoundException $throwable) {
            $fail('当前分类不允许使用');
        }


    }


}
