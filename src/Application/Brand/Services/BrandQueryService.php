<?php

namespace RedJasmine\Product\Application\Brand\Services;


use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Spatie\QueryBuilder\AllowedFilter;


class BrandQueryService extends ApplicationQueryService
{


    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.brand.query';

    public function __construct(protected BrandReadRepositoryInterface $repository)
    {

    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('initial'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('status'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('english_name'),
            AllowedFilter::callback('search', static function (Builder $builder, $value) {
                return $builder->where(function (Builder $builder) use ($value) {
                    $builder->where('name', 'like', '%'.$value.'%')->orWhere('english_name', 'like', '%'.$value.'%');
                });
            })

        ];
    }

    public function isAllowUse(int $id) : bool
    {
        return (bool) ($this->findById(FindQuery::make($id))?->isAllowUse());
    }


    public function onlyShow() : void
    {
        $this->getRepository()->withQuery(function ($query) {
            $query->show();
        });
    }
}
