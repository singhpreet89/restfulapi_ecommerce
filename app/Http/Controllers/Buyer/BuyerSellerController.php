<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\SellerCollection;

class BuyerSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Buyer $buyer)
    {
        /**
         * ! EAGER LOAGING
         * ? SQL Query: 
         *      select s.* from users u 
         *          left join transactions t on u.id = t.buyer_id
         *          left join products p on t.product_id = p.id
         *          left join users s on p.seller_id = s.id
         *      where u.id = 5;
         * The result of $buyer->transactions is a collection as a Buyer has many Transactions
         * And each Transaction belogs to a Product
         * Further each Product belongs to a Seller
         * ? The Buyer must have purchased multiple items from a Seller, so we need to ding the unique sellers
         * ? This leaves a null object in the collection, so values() recreates the collection index and removes the null index
         */
        $transactionsWithProductsAndSellers = $buyer->transactions()->with('product.seller')->get();

        $sellers = "";
        if($request->query('unique') === "true") {
            $sellers = $transactionsWithProductsAndSellers->pluck('product.seller')->unique('id')->values();
        } else {
            $sellers = $transactionsWithProductsAndSellers->pluck('product.seller');
        }
  
        return SellerCollection::collection($sellers);
    }
}
