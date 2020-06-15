<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;

class CategoryBuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Category $category)
    {
        $transactionsWithBuyers = $category->products()->whereHas('transactions')->with('transactions.buyer')->get();
        $transactions = $transactionsWithBuyers->pluck('transactions')->collapse();
        
        $buyers = '';
        if($request->query('unique') === "true") {
            $buyers = $transactions->pluck('buyer')->unique('id')->values();
        } else {
            $buyers = $transactions->pluck('buyer');
        }
        
        return UserCollection::collection($buyers);
    }
}
