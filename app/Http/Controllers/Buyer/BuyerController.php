<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Buyer $buyer
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // If the User has atleast one Transaction then he is a Buyer
        // $buyers = Buyer::has('transactions')->paginate(20);
        
        // ? Buyer::has('transactions') is being handled in the BuyerScope
        $buyers = $buyer->paginate(20);
        return UserCollection::collection($buyers);
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
        return new UserResource($buyer);
    }
}
