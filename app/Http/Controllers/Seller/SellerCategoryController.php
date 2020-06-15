<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryCollection;

class SellerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Seller $seller)
    {
        $productsWithcategories = $seller->products()->whereHas('categories')->with('categories')->get();

        $categories = '';
        if($request->query('unique') === "true") {
            $categories = $productsWithcategories->pluck('categories')->collapse()->unique('id')->values();
        } else {
            $categories = $productsWithcategories->pluck('categories')->collapse();
        }
        
        return CategoryCollection::collection($categories);
    }
}
