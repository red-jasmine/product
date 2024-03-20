<?php

namespace RedJasmine\Product\Http\Buyer\Controllers\Api;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Http\Buyer\Resources\CategoryResource;
use RedJasmine\Product\Services\Category\ProductCategoryService;

class CategoryController extends Controller
{
    public function __construct(protected ProductCategoryService $service)
    {
        $this->service->withQuery(function ($query) {
            $query->enable();
        });
    }


    public function index() : AnonymousResourceCollection
    {
        $tree = $this->service->query->tree();

        return CategoryResource::collection($tree);
    }

    public function show($id) : CategoryResource
    {
        return new CategoryResource($this->service->query()->findOrFail($id));
    }


}
