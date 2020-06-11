<?php

namespace App\Http\Resources\Buyer;

use Illuminate\Http\Resources\Json\JsonResource;

class BuyerCollection extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'verified' => $this->verified,
            'href' => [
                'user' => route('buyers.show', $this->id),
            ],
        ];
    }
}