<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    use APIResponseTrait;
    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }

    public function store(ProductStoreRequest $request)
    {
        $Code = $request->productCode ?? null;
        $exist = $this->productService->productInfoByID($Code)->first();

        if ($exist) {

            $updated = $this->productService->updateProduct($Code, $request->all());

            return $this->successResponseWeb('', 'Product updated successfully',200);
        } else {
            $created = $this->productService->createProduct($request->all());
            return $this->successResponseWeb('', 'Product created successfully',200);
        }
    }
   public function index(Request $request){
       return $this->productService->productIndex($request);
   }
   public function marketPriceIndex(Request $request){
       return $this->productService->getMarketPriceIndex($request);
   }
   public function wholesaleMarketPriceIndex(Request $request){
       return $this->productService->getWholesaleMarketPriceIndex($request);
   }
    public function getProductInfo($prodCode){

        return $this->productService->productInfoByID($prodCode)->first();
    }
}
