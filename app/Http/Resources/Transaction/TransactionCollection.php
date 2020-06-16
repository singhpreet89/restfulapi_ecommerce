<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $endUrl = Route::currentRouteName() === 'buyers.transactions.index' || Route::currentRouteName() === 'categories.transactions.index' 
            || Route::currentRouteName() === 'sellers.transactions.index' || Route::currentRouteName() === 'products.transactions.index'
                ? explode('.', Route::currentRouteName())[1] : explode('.', Route::currentRouteName())[0];
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'buyer_id' => $this->buyer_id,
            'product_id' => $this->product_id,
            'link' => [
                'rel' => 'self',
                'href' => route($endUrl . '.show', $this->id),
            ],
        ];
    }
}
