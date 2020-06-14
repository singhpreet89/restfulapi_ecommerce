<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;

class BuyerProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {  
        /**
         * ! EAGER LOAGING
         * ? SQL Query: 
         *      select p.* from users u 
         *          left join transactions t on u.id = t.buyer_id
         *          left join products p on t.product_id = p.id
         *      where u.id = 5;
         * The result of $buyer->transactions is a collection as a Buyer has many Transactions
         * And each Transaction belogs to a Product
         */
        // $products = $buyer->transactions->product;
        $transactionsWithProducts = $buyer->transactions()->with('product')->get();
        $products = $transactionsWithProducts->pluck('product');
        
        return ProductCollection::collection($products);
    }
}
