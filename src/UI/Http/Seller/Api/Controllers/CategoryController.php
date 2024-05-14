<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\CategoryResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class CategoryController extends Controller
{

    public function __construct(
        protected ProductCategoryQueryService $queryService,

    )
    {
    }

    public function tree(Request $request) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductCategoryTreeQuery::from($request));
        return CategoryResource::collection($tree);
    }

    public function show(Request $request, $id) : CategoryResource
    {
        $result = $this->queryService->find($id, FindQuery::from($request));

        return CategoryResource::make($result);

    }

}
