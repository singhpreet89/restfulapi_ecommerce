<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;

class CategorySellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Category $category)
    {
        $productsWithSeller = $category->products()->with('seller')->get();
        
        $sellers = "";
        if($request->query('unique') === "true") {
            $sellers = $productsWithSeller->pluck('seller')->unique('id')->values();
        } else {
            $sellers = $productsWithSeller->pluck('seller');
        }

        return UserCollection::collection($sellers);
    }
}
