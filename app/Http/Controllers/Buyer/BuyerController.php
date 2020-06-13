<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Buyer\BuyerResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\Buyer\BuyerCollection;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // If the User has atleast one Transaction then he is a Buyer
        $buyers = Buyer::has('transactions')->paginate(20);
        return UserCollection::collection($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buyer = Buyer::has('transactions')->findOrFail($id);
        return new UserResource($buyer);
    }
}
