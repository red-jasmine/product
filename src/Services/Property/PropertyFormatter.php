<?php

namespace RedJasmine\Product\Services\Property;

use Illuminate\Support\Arr;
use RedJasmine\Item\Exceptions\ItemPropertyException;
use RedJasmine\Item\Services\Items\ItemPropertyToolsService;

class PropertyFormatter
{


    public function formatString(string $value = null) : string
    {
        if (blank($value)) {
            return '';
        }
        return $this->toString($this->toArray($value));
    }

    public function formatArray(array $value = []) : array
    {
        if (blank($value)) {
            return [];
        }
        return $this->toArray($this->toString($value));
    }


    /**
     * 属性笛卡尔积
     *
     * @param array{pid:int,vid:int|int[]} $props
     *
     * @return string[]
     */
    public function crossJoinToString(array $props = []) : array
    {
        $crossJoin = $this->crossJoinToArray($props);

        $crossJoinTextList = [];
        foreach ($crossJoin as $item) {
            $crossJoinTextList[] = $this->toString($item);
        }
        return $crossJoinTextList;
    }

    /**
     * 计算规格属性的属性
     *
     * @param array $props
     *
     * @return array
     */
    public function crossJoinToArray(array $props = []) : array
    {
        $skuProperties = [];
        foreach ($props as $item) {
            $pid            = (int)$item['pid'];
            $values         = $item['vid'] ?? [];
            $propertyValues = [];
            foreach ($values as $vid) {
                $propertyValues[] = [ 'pid' => $pid, 'vid' => (int)$vid ];
            }
            $skuProperties[] = $propertyValues;
        }
        return Arr::crossJoin(...$skuProperties);
    }


    /**
     * @param array{pid:int,vid:int|int[]} $props
     *
     * @return string
     */
    public function toString(array $props = []) : string
    {
        if (blank($props)) {
            return '';
        }
        $properties = [];
        foreach ($props as $item) {
            $pid    = (int)$item['pid'];
            $values = $item['vid'] ?? null;
            if (!is_array($values)) {
                $values = [ $values ];
            }
            $newValues = [];
            foreach ($values as &$vid) {
                if (filled($vid)) {
                    $newValues[] = (int)$vid;
                }
            }
            asort($newValues);
            $properties[$pid] = $pid . ':' . implode(',', $values);
        }
        asort($properties);
        return implode(';', $properties);

    }


    public function toArray(string $propsString = '') : array
    {

        if (blank($propsString)) {
            return [];
        }
        $properties = [];
        $props      = explode(';', $propsString);

        try {
            foreach ($props as $property) {
                if (blank($property)) {
                    continue;
                }
                [ $pid, $values ] = explode(':', $property);
                $propertyItem        = [];
                $pid                 = (int)$pid;
                $propertyItem['pid'] = (int)$pid;
                $propertyItem['vid'] = [];

                $itemPropValues = explode(',', $values);
                foreach ($itemPropValues as $itemPropValue) {
                    if (blank($itemPropValue)) {
                        continue;
                    }
                    $propertyItem['vid'][] = (int)$itemPropValue;
                }
                $propertyItem['vid'] = array_unique($propertyItem['vid']);
                asort($propertyItem['vid']);

                $properties[$pid] = $propertyItem;

            }
            asort($properties);
        } catch (\Throwable $throwable) {
            throw new ItemPropertyException('属性格式错误', ItemPropertyException::WRONG_FORMAT);
        }


        return array_values($properties);


    }

}
