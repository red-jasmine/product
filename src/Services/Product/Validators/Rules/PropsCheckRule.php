<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;

class PropsCheckRule extends AbstractRule
{

    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        $saleProps  = $this->data['info']['sale_props'] ?? [];
        $basicProps = $this->data['info']['basic_props'] ?? [];

        $basicPid  = collect($basicProps)->pluck('pid')->values();
        $salePid   = collect($saleProps)->pluck('pid')->values();
        $intersect = $salePid->intersect($basicPid);
        if ($intersect->count()) {
            foreach ($intersect as $pid) {
                $fail('info.basic_props.' . $pid, '基础属性和销售属性不能有重复');
            }

        }
    }

}
