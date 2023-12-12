<?php

namespace RedJasmine\Product\Services\Product;

use BadMethodCallException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Macroable;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductChannelStock;
use RedJasmine\Product\Models\ProductStockLog;
use RedJasmine\Product\Services\Product\Stock\ProductStockChannel;
use RedJasmine\Product\Services\Product\Stock\StockChannelInterface;
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

    use ServiceExtends;

    public function __construct(protected ?ProductService $service)
    {


    }


    public function channel() : ProductStockChannel
    {
        return new ProductStockChannel($this->service, $this);
    }


    public function set(ProductStockChangeTypeEnum $changeTypeEnum, int $skuID = 0, int $stock)
    {

    }

    /**
     * 获取库存单位
     *
     * @param int $skuID
     *
     * @return Product
     * @throws ProductStockException
     */
    public function getSKU(int $skuID) : Product
    {
        $sku = Product::lockForUpdate()
                      ->withTrashed()
                      ->select([ 'owner_type', 'owner_uid', 'id', 'spu_id', 'stock', 'lock_stock', 'channel_stock', 'is_sku' ])
                      ->findOrFail($skuID);
        if ($sku->is_sku === BoolIntEnum::NO) {
            throw new ProductStockException('只能操作库存单位');
        }
        return $sku;
    }

    /**
     * 获取渠道库存
     *
     * @param int                   $skuID
     * @param StockChannelInterface $channel
     *
     * @return ProductChannelStock
     */
    public function getChannelStock(int $skuID, StockChannelInterface $channel) : ProductChannelStock
    {
        return ProductChannelStock::lockForUpdate()
                                  ->channel($channel)
                                  ->where('sku_id', $skuID)
                                  ->firstOrFail();
    }


    /**
     * @param int $quantity
     *
     * @return int
     * @throws ProductStockException
     */
    public function validateQuantity(int $quantity) : int
    {
        // 核心操作 $quantity 都为 正整数
        if (bccomp($quantity, 0, 0) <= 0) {
            throw new ProductStockException('增加库存 数量必须大于 0');
        }
        return $quantity;
    }

    /**
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function add(int $skuID, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum) : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($quantity);
        try {
            DB::beginTransaction();
            $sku         = $this->getSKU($skuID);
            $beforeStock = $sku->stock;
            // 更新库存
            $sku->stock = bcadd($sku->stock, $quantity, 0);
            $sku->save();
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $beforeStock, $quantity, $sku->stock);
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
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelInterface|null $channel
     * @param bool                       $lock
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function sub(int $skuID, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, ?StockChannelInterface $channel = null, bool $lock = false) : ?ProductStockLog
    {

        $quantity = $this->validateQuantity($quantity);

        try {
            DB::beginTransaction();
            $sku = $this->getSKU($skuID);

            $beforeStock = $sku->stock;

            // 可售库存
            // 1、渠道扣减: 可售库存 = 辑渠可售库存
            //    前提条件  规格可售库存 必须大于等级 逻辑渠道可售库存
            // 2、普通扣减: 可售库存 = 规格可售库存 - 总逻辑可售库存
            if ($channel instanceof StockChannelInterface) {
                // 渠道扣减
                $channelStock  = $this->getChannelStock($skuID, $channel);
                $saleableStock = $channelStock->channel_stock;
                if (bccomp($sku->stock, $channelStock->channel_stock, 0) < 0) {
                    throw new ProductStockException('实际库存小于逻辑库存');
                }
            } else {
                // 普通扣减
                $saleableStock = bcsub($sku->stock, $sku->channel_stock, 0);
            }
            // 可售库存 - 数量  必须 > 0
            if (bccomp(bcsub($saleableStock, $quantity, 0), 0, 0) < 0) {
                throw new ProductStockException('库存不足');
            }
            // 物理库存 = 原库存 - 数量
            $sku->stock = bcsub($sku->stock, $quantity, 0);

            if ($lock) {
                // 如果是锁定操作
                // 锁定库存 = 原锁定库存 + 数量
                $sku->lock_stock = bcadd($sku->lock_stock, $quantity, 0);
            }

            // 逻辑库存操作
            if ($channel instanceof StockChannelInterface) {
                // 总逻辑库存 = 原逻辑库存 - 数量
                $sku->channel_stock = bcsub($sku->channel_stock, $quantity, 0);
                // 逻辑可售库存 = 原逻辑可售库存 - 数量
                $channelStock->channel_stock = bcsub($channelStock->channel_stock, $quantity, 0);
                if ($lock) {
                    // 逻辑锁定库存 = 原逻辑锁定库存 + 数量
                    $channelStock->channel_lock_stock = bcadd($channelStock->channel_lock_stock, $quantity, 0);
                }
                $channelStock->save();
            }

            $sku->save();
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $beforeStock, -$quantity, $sku->stock);
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
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelInterface|null $channel
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function lock(int $skuID, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, ?StockChannelInterface $channel = null) : ?ProductStockLog
    {
        return $this->sub($skuID, $quantity, $changeTypeEnum, $channel, true);
    }


    /**
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelInterface|null $channel
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function unlock(int $skuID, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, ?StockChannelInterface $channel = null) : ?ProductStockLog
    {

        $quantity = $this->validateQuantity($quantity);

        try {
            DB::beginTransaction();
            $sku = $this->getSKU($skuID);
            // 如果是普通扣减 还是渠道扣减
            $beforeStock = $sku->stock;

            // 判断 总锁定库存 是否充足
            if (bccomp(bcsub($sku->lock_stock, $quantity, 0), 0, 0) < 0) {
                throw new ProductStockException('锁定库存不足');
            }

            if ($channel instanceof StockChannelInterface) {
                // 渠道扣减
                $channelStock = $this->getChannelStock($skuID, $channel);
                // 判断渠道锁定库存是否充足
                if (bccomp(bcsub($channelStock->channel_lock_stock, $quantity, 0), 0, 0) < 0) {
                    throw new ProductStockException('渠道锁定库存不足');
                }
            }

            // 实物库存操作
            $sku->stock      = bcadd($sku->stock, $quantity, 0); // 反向操作
            $sku->lock_stock = bcsub($sku->lock_stock, $quantity, 0);
            // 逻辑库存操作
            if ($channel instanceof StockChannelInterface) {
                // 总渠道库存
                $sku->channel_stock               = bcadd($sku->channel_stock, $quantity, 0);
                $channelStock->channel_stock      = bcadd($channelStock->channel_stock, $quantity, 0);
                $channelStock->channel_lock_stock = bcsub($channelStock->channel_lock_stock, $quantity, 0);
                $channelStock->save();
            }

            $sku->save();
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $beforeStock, $quantity, $sku->stock);
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

    public function subLock()
    {

    }

    // TODO 冻结库存处理

    public function holdStock(ProductStockChangeTypeEnum $changeTypeEnum, int $skuID = 0, int $quantity)
    {
        // 查询库存
        // 添加冻结记录
        //  更变冻结库存
        //  更变
        // 同步 SPU
        // 记录冻结 日志
    }

    public function repayStock(ProductStockChangeTypeEnum $changeTypeEnum, int $skuID = 0, int $quantity)
    {
        // 查询冻结库存

    }

    // 更变冻结库存
    public function changeHoldStock(ProductStockChangeTypeEnum $changeTypeEnum, int $skuID = 0, int $quantity)
    {

    }


    /**
     * 全量更新
     *
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
     * 增量变更
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
     * 记录变更
     *
     * @param Product                    $sku
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param int                        $beforeStock
     * @param int                        $changeStock
     * @param int                        $resultStock
     *
     * @return ProductStockLog|null
     * @throws Exception
     */
    public function log(Product $sku, ProductStockChangeTypeEnum $changeTypeEnum, int $beforeStock, int $changeStock, int $resultStock) : ?ProductStockLog
    {
        if (bccomp($beforeStock, $resultStock, 0) === 0) {
            return null;
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
        return $productStockLog;
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
            $sku = Product::lockForUpdate()->withTrashed()->select([ 'owner_type', 'owner_uid', 'id', 'stock', 'is_sku', 'spu_id' ])->findOrFail($skuID);
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
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $beforeStock, $changeStock, $resultStock);
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
