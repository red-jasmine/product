<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RedJasmine\Product\Services\Product\ProductService;

class ProductController extends Controller
{
    public function __construct(public ProductService $service)
    {
    }

    public function index()
    {
        $result =  $this->service->lists();

        dd($result);
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
