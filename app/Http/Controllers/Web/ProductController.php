<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService){
        $this->productService = $productService;
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
}
