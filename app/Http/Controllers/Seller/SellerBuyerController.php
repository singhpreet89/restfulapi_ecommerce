<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;

class SellerBuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Seller $seller)
    {
        $productsWithTransactionsAndBuyer = $seller->products()->whereHas('transactions')->with('transactions.buyer')->get();
        $transactions = $productsWithTransactionsAndBuyer->pluck('transactions')->collapse();
        
        $buyers = '';
        if($request->query('unique') === "true") {
            $buyers = $transactions->pluck('buyer')->unique('id')->values();
        } else {
            $buyers = $transactions->pluck('buyer');
        }

        return UserCollection::collection($buyers);
    }
}
