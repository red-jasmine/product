<?php

namespace RedJasmine\Product\Services\Product;

use BadMethodCallException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Macroable;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductStockLog;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Helpers\ID\Snowflake;
use Throwable;

/**
 *
 * 库存服务
 * @mixin ProductService
 *
 */
class ProductStock
{

    use Macroable {
        __call as macroCall;
    }


    public function __construct(protected ?ProductService $service)
    {


    }


    public function holdSockt(int $productID, int $skuID = 0, int $number = 1)
    {

    }


    /**
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param int                        $skuID
     * @param int                        $resultStock
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function setStock(ProductStockChangeTypeEnum $changeTypeEnum, int $skuID = 0, int $resultStock) : ?ProductStockLog
    {
        return $this->updateCore($changeTypeEnum, $skuID, $resultStock, true);
    }

    /**
     * 更新库存
     *
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param int                        $skuID
     * @param int                        $changeStock
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function changeStock(ProductStockChangeTypeEnum $changeTypeEnum, int $skuID = 0, int $changeStock) : ?ProductStockLog
    {
        return $this->updateCore($changeTypeEnum, $skuID, $changeStock);
    }

    /**
     * 更新库存
     *
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param int                        $skuID
     * @param int                        $number
     * @param bool                       $isCoverage
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function updateCore(ProductStockChangeTypeEnum $changeTypeEnum, int $skuID = 0, int $number, bool $isCoverage = false) : ?ProductStockLog
    {

        try {
            DB::beginTransaction();
            $sku = Product::lockForUpdate()->select([ 'owner_type', 'owner_uid', 'id', 'stock', 'is_sku', 'spu_id' ])->findOrFail($skuID);
            if ($sku->is_sku === BoolIntEnum::NO) {
                throw new ProductStockException('只能操作库存单位');
            }
            $beforeStock = $sku->stock;
            //  是否覆盖库存
            if ($isCoverage === false) {
                $changeStock = $number;
                $resultStock = bcadd($sku->stock, $number, 0);
                if (bccomp($resultStock, 0, 0) < 0) {
                    throw new ProductStockException('库存不足');
                }
            } else {
                $resultStock = $number;
                $changeStock = bcsub($resultStock, $sku->stock);
                if (bccomp($resultStock, 0, 0) < 0) {
                    throw new ProductStockException('库存错误');
                }
            }

            if (bccomp($sku->stock, $resultStock, 0) === 0) {
                return null;
            }

            //  对库存 单位进行处理
            $sku->stock = (int)$resultStock;
            $sku->save();

            if ($sku->spu_id) {
                // 对 商品级 同步
                Product::where('id', $sku->spu_id)->increment('stock', (int)$changeStock);
            }
            $productStockLog     = new ProductStockLog();
            $productStockLog->id = Snowflake::getInstance()->nextId();
            $productStockLog->withOwner($sku->getOwner());
            $productStockLog->withCreator($this->getOperator());
            $productStockLog->sku_id       = $sku->id;
            $productStockLog->product_id   = $sku->spu_id === 0 ? $sku->id : $sku->spu_id;
            $productStockLog->change_type  = $changeTypeEnum;
            $productStockLog->before_stock = $beforeStock;
            $productStockLog->change_stock = $changeStock;
            $productStockLog->result_stock = $resultStock;
            $productStockLog->save();

            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            throw  $throwable;
        }

    }

    /**
     * Magically call the JWT instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->service, $method)) {
            return call_user_func_array([ $this->service, $method ], $parameters);
        }

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
