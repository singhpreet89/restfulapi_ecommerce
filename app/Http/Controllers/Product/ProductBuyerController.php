<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;
use App\Product;
use Illuminate\Http\Request;

class ProductBuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $transactionsWithBuyers = $product->transactions()->with('buyer')->get();
        $buyers = $transactionsWithBuyers->pluck('buyer')->unique('id')->values();

        return UserCollection::collection($buyers);
    }
}
