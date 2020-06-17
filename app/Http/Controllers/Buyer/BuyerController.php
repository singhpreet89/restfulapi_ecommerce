<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\Controller;
use App\Http\Resources\Buyer\BuyerResource;
use App\Http\Resources\Buyer\BuyerCollection;
use App\Services\PaginationService;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Buyer $buyer
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer, PaginationService $paginationService)
    {
        // ! If the User has atleast one Transaction then he is a Buyer
        // $buyers = Buyer::has('transactions')->paginate(20);
        
        // ? Buyer::has('transactions') is being handled in the BuyerScope
        $buyers = $buyer->all();
        $paginatedBuyers = $paginationService->paginate($buyers);

        return BuyerCollection::collection($paginatedBuyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  Buyer $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        // $buyer = Buyer::has('transactions')->findOrFail($id);

        // ? Buyer::has('transactions') is being handled in the BuyerScope
        return new BuyerResource($buyer);
    }
}
