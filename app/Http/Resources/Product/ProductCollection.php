<?php

namespace App\Http\Resources\Product;

use Illuminate\Support\Facades\Route;
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
        $endUrl = explode('.', Route::currentRouteName())[0];
        return [
            'id' => $this->id,
            'seller_id' => $this->seller_id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'link' => [
                'rel' => 'self',
                'href' => route($endUrl . '.show', $this->id),
            ],
        ];
    }
}
