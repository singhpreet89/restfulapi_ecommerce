<?php

namespace App\Http\Resources\Transaction;

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
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'buyer_id' => $this->buyer_id,
            'product_id' => $this->product_id,
            'link' => [
                'rel' => 'self',
                'href' => route('transactions.show', $this->id),
            ],
        ];
    }
}
