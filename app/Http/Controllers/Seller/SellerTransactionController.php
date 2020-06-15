<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionCollection;

class SellerTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $productWithTransactions = $seller->products()->whereHas('transactions')->with('transactions')->get();
        $transactions = $productWithTransactions->pluck('transactions')->collapse();

        return TransactionCollection::collection($transactions);
    }
}
