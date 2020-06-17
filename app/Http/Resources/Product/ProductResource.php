<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
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
            'createdAt' => (string)$this->created_at,
            'updatedAt' => (string)$this->updated_at,
            'deletedAt' => isset($this->deleted_at) ? (string)$this->deleted_at : null,
        ];
    }
}
