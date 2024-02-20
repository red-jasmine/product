<?php

namespace RedJasmine\Product\Services\Series;


use Illuminate\Support\Facades\DB;
use RedJasmine\Product\DataTransferObjects\Series\ProductSeriesDTO;
use RedJasmine\Product\DataTransferObjects\Series\ProductSeriesProductDTO;
use RedJasmine\Product\Models\ProductSeries;
use RedJasmine\Product\Models\ProductSeriesProduct;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Service;

class ProductSeriesService extends Service
{

    /**
     * @param ProductSeriesDTO $productSeriesDTO
     *
     * @return ProductSeries
     * @throws AbstractException
     * @throws \Throwable
     */
    public function create(ProductSeriesDTO $productSeriesDTO) : ProductSeries
    {
        try {
            DB::beginTransaction();
            $productSeries          = new ProductSeries();
            $productSeries->name    = $productSeriesDTO->name;
            $productSeries->owner   = $this->getOwner();
            $productSeries->creator = $this->getOperator();
            $productSeries->remarks = $productSeriesDTO->remaks;
            $productSeries->save();
            $productSeriesDTO->products->each(function (ProductSeriesProductDTO $productSeriesProductDTO) use ($productSeries) {
                $values = [
                    'series_id'  => $productSeries->id,
                    'product_id' => $productSeriesProductDTO->productId,
                    'name'       => $productSeriesProductDTO->name,
                ];
                ProductSeriesProduct::updateOrCreate([ 'product_id' => $productSeriesProductDTO->productId ], $values);
            });
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $productSeries;
    }

    /**
     * @param int              $id
     * @param ProductSeriesDTO $productSeriesDTO
     *
     * @return ProductSeries
     * @throws AbstractException
     * @throws \Throwable
     */
    public function update(int $id, ProductSeriesDTO $productSeriesDTO) : ProductSeries
    {
        try {
            DB::beginTransaction();
            $productSeries          = ProductSeries::find($id);
            $productSeries->name    = $productSeriesDTO->name;
            $productSeries->remarks = $productSeriesDTO->remaks;
            $productSeries->owner   = $this->getOwner();
            $productSeries->updater = $this->getOperator();
            $productSeries->save();
            $productSeries->products()->delete();
            $productSeriesDTO->products->each(function (ProductSeriesProductDTO $productSeriesProductDTO) use ($productSeries) {
                $values = [
                    'series_id'  => $productSeries->id,
                    'product_id' => $productSeriesProductDTO->productId,
                    'name'       => $productSeriesProductDTO->name,
                ];
                ProductSeriesProduct::updateOrCreate([ 'product_id' => $productSeriesProductDTO->productId ], $values);
            });
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $productSeries;
    }


    /**
     * @param int $id
     *
     * @return bool
     * @throws AbstractException
     * @throws \Throwable
     */
    public function delete(int $id) : bool
    {
        try {
            DB::beginTransaction();
            ProductSeries::destroy($id);
            ProductSeriesProduct::where('series_id', $id)->delete();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;
    }

}
