<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryPaginateQuery;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\CategoryResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class CategoryController
{
    public function __construct(
        protected ProductCategoryQueryService   $queryService,
        protected ProductCategoryCommandService $commandService,

    )
    {
    }


    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductCategoryTreeQuery::from($request));
        return CategoryResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(ProductCategoryPaginateQuery::from($request));
        return CategoryResource::collection($result);
    }

    public function store(Request $request)
    {
    }

    public function show($id, Request $request) : CategoryResource
    {


        $result = $this->queryService->find($id, FindQuery::from($request));
        return CategoryResource::make($result);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
