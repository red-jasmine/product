<?php

namespace RedJasmine\Product\Services\Stock\Actions;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\ProductStockLog;
use RedJasmine\Product\Services\Stock\Data\StockActionData;
use RedJasmine\Product\Services\Stock\Enums\ProductStockTypeEnum;
use RedJasmine\Product\Services\Stock\StockService;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;
use Throwable;

/**
 * @property StockService $service
 */
abstract class AbstractStockAction extends ResourceAction
{

    /**
     * 验证库存
     *
     * @param int $quantity
     *
     * @return int
     * @throws ProductStockException
     */
    public function validateQuantity(int $quantity) : int
    {
        // 核心操作 $quantity 都为 正整数
        if (bccomp($quantity, 0, 0) < 0) {
            throw new ProductStockException('操作库存 数量必须大于 0');
        }
        return $quantity;
    }

    /**
     * @param StockActionData $data
     * @param bool            $onlyLog
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function initStock(StockActionData $data, bool $onlyLog = false) : ?ProductStockLog
    {
        // 设置库存采用悲观锁
        $stock = $this->validateQuantity($data->stock);
        try {

            DB::beginTransaction();
            if ($onlyLog === false) {
                $stockUpdate = DB::raw("stock + $stock");
                Product::where('id', $data->productId)->update([ 'stock' => $stockUpdate ]);
            }
            $productStockLog = $this->service->log(
                ProductStockTypeEnum::INIT,
                $data->productId,
                $data->skuId,
                $stock,
                0,
                $data->changeType,
                $data->changeDetail,
                $data->channel,
            );
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $productStockLog;
    }


    /**
     * @param StockActionData $data
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function reset(StockActionData $data) : ?ProductStockLog
    {

        // 设置库存采用悲观锁
        $stock = $this->validateQuantity($data->stock);

        try {
            DB::beginTransaction();
            $sku = $this->service->getSKU($data->skuId);
            // 如果没有改变库存 那么不进行操作
            if (bccomp($sku->stock, $stock, 0) === 0) {
                DB::commit();
                return null;
            }
            // 最小要求库存 = 当前库存  - 渠道占用库存
            $minStock = $sku->channel_stock; // 渠道占用
            $quantity = bcsub($stock, $sku->stock, 0);
            // 如果调整后的库存 小于渠道占用
            if (bccomp($stock, $minStock, 0) < 0) {
                throw new ProductStockException('渠道库存占用:' . $sku->channel_stock, 0, [ 'channel_stock' => $sku->channel_stock ]);
            }
            $stockUpdate = DB::raw("stock + $quantity");
            ProductSku::withTrashed()->where('id', $sku->id)->update([ 'stock' => $stockUpdate ]);
            Product::withTrashed()->where('id', $sku->product_id)->update([ 'stock' => $stockUpdate ]);
            // 添加更变日志
            // 添加更变日志
            $productStockLog = $this->service->log(
                ProductStockTypeEnum::RESET,
                $data->productId,
                $data->skuId,
                $quantity,
                0,
                $data->changeType,
                $data->changeDetail,
                $data->channel,
                ['stock'=>$stock]
            );

            DB::commit();

        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $productStockLog;

    }

    /**
     * @param StockActionData $data
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException|Throwable
     */
    protected function add(StockActionData $data) : ?ProductStockLog
    {

        $quantity = $this->validateQuantity($data->stock);

        try {
            DB::beginTransaction();
            $update = [ 'stock' => DB::raw("stock + $quantity") ];
            ProductSku::where('id', $data->skuId)->update($update);
            Product::where('id', $data->productId)->update($update);
            // 添加更变日志
            $productStockLog = $this->service->log(
                ProductStockTypeEnum::ADD,
                $data->productId,
                $data->skuId,
                +$quantity,
                0,
                $data->changeType,
                $data->changeDetail,
                $data->channel,
            );
            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

    }


    /**
     * @param StockActionData $data
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function sub(StockActionData $data) : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($data->stock);
        try {
            DB::beginTransaction();
            $lockStock = 0;
            $update    = [ 'stock' => DB::raw("stock - $quantity") ];
            if ($data->isLock) {
                $lockStock            = $quantity;
                $update['lock_stock'] = DB::raw("lock_stock + $quantity");
            }
            $rows = ProductSku::where('id', $data->skuId)->where('stock', '>=', $quantity)->update($update);
            if ($rows <= 0) {
                throw new ProductStockException('库存不足');
            }
            Product::where('id', $data->productId)->update($update);
            // 添加更变日志
            $productStockLog = $this->service->log(
                $data->isLock === true ? ProductStockTypeEnum::LOCK : ProductStockTypeEnum::SUB,
                $data->productId,
                $data->skuId,
                -$quantity,
                +$lockStock,
                $data->changeType,
                $data->changeDetail,
                $data->channel,
            );


            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
    }


    /**
     * @param StockActionData $data
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function lock(StockActionData $data) : ?ProductStockLog
    {
        $data->isLock = true;
        return $this->sub($data);
    }

    /**
     * 释放
     *
     * @param StockActionData $data
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function unlock(StockActionData $data) : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($data->stock);
        try {
            DB::beginTransaction();
            $update['stock']      = DB::raw("stock + $quantity");
            $update['lock_stock'] = DB::raw("lock_stock - $quantity");

            $rows = ProductSku::where('id', $data->skuId)->where('lock_stock', '>=', $quantity)->update($update);
            if ($rows <= 0) {
                throw new ProductStockException('锁定库存不足');
            }
            Product::where('id', $data->productId)->update($update);
            // 添加更变日志
            $productStockLog = $this->service->log(
                ProductStockTypeEnum::UNLOCK,
                $data->productId,
                $data->skuId,
                +$quantity,
                -$quantity,
                $data->changeType,
                $data->changeDetail,
                $data->channel,
            );
            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

    }


    /**
     * @param StockActionData $data
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function confirm(StockActionData $data) : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($data->stock);
        try {
            DB::beginTransaction();
            $update['lock_stock'] = DB::raw("lock_stock - $quantity");

            $rows = ProductSku::where('id', $data->skuId)
                              ->where('lock_stock', '>=', $quantity)
                              ->update($update);
            if ($rows <= 0) {
                throw new ProductStockException('锁定库存不足');
            }
            Product::where('id', $data->productId)->update($update);
            //  添加确认操作
            $productStockLog = $this->service->log(
                ProductStockTypeEnum::CONFIRM,
                $data->productId,
                $data->skuId,
                0,
                -$quantity,
                $data->changeType,
                $data->changeDetail,
                $data->channel,
            );
            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
    }
}
