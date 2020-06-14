<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        // If the User has atleast one Product then he is a Seller
        // $sellers = Seller::has('products')->paginate(20);
        
        // ? Seller::has('products') is being handled in the SellerScope
        $sellers = $seller->paginate(20);
        return UserCollection::collection($sellers);
    }

    /**
     * Display the specified resource.
     *
     * @param  Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        // $seller = Seller::has('products')->findOrFail($id);

        // ? Seller::has('products') is being handled in the SellerScope
        return new UserResource($seller);
    }
}
