<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
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
            'seller_id' => $this->seller_id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'image' => url("img/{$this->image}"),
            'created_at' => isset($this->created_at) ? (string) $this->created_at : null,
            'updated_at' => isset($this->updated_at) ? (string) $this->updated_at : null,
            'deleted_at' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
            'link' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $this->id),
                ],
                [
                    'rel' => 'products.buyers',
                    'href' => route('products.buyers.index', $this->id),
                ],
                [
                    'rel' => 'products.categoriess',
                    'href' => route('products.categories.index', $this->id),
                ],
                [
                    'rel' => 'products.sellers',
                    'href' => route('sellers.show', $this->seller_id),
                ],
                [
                    'rel' => 'products.transactions',
                    'href' => route('products.transactions.index', $this->id),
                ],
            ],
        ];
    }
}
