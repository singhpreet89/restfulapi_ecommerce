<?php

namespace App\Http\Controllers\Seller;

use App\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;

class SellerProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return ProductCollection::collection($products);
    }
}
