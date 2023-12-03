<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;

class PriceRule extends AbstractRule
{
    /**
     *
     * @param bool $isAllowZero 是否允许为0
     */
    public function __construct(protected bool $isAllowZero = false)
    {
    }


    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {

        if ($this->checkPrice($value, $this->isAllowZero) === false) {
            $fail(':attribute 不正确');
        }
    }


    protected string $max = '100000000.00';
    protected string $min = '0.00';

    /**
     * 校验价格
     *
     * @param string|float|int $price
     * @param bool             $isAllowZero
     *
     * @return bool
     */
    public function checkPrice(string|float|int $price, bool $isAllowZero = false) : bool
    {
        try {
            $price = bcadd($price, 0, 2);
            if (bccomp($price, $this->max, 2) >= 0) {
                return false;
            }
            if ($isAllowZero) {
                if (bccomp($price, $this->min, 2) < 0) {
                    return false;
                }
            } else if (bccomp($price, $this->min, 2) <= 0) {
                return false;
            }

        } catch (\Throwable) {
            return false;
        }

        return true;
    }

}
