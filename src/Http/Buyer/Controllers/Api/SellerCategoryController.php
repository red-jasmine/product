<?php

namespace RedJasmine\Product\Http\Buyer\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Http\Buyer\Resources\SellerCategoryResource;
use RedJasmine\Product\Services\Category\ProductSellerCategoryService;
use RedJasmine\Support\Helpers\User\UserObject;

class SellerCategoryController extends Controller
{
    public function __construct(protected ProductSellerCategoryService $service)
    {
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $owner = new UserObject([
                                           'type' => $request->input('owner_type'),
                                           'id'  => $request->input('owner_id'),
                                       ]);
        $this->service->setOwner($owner);
        return SellerCategoryResource::collection($this->service->tree());
    }

    /**
     * @param         $id
     *
     * @return SellerCategoryResource
     */
    public function show($id) : SellerCategoryResource
    {
        $result = $this->service->find($id);
        return new  SellerCategoryResource($result);
    }


}
