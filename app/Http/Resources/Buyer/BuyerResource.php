<?php

namespace App\Http\Resources\Buyer;

use Illuminate\Http\Resources\Json\JsonResource;

class BuyerResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'isVerified' => (int)$this->verified,
            'createdAt' => (string)$this->created_at,
            'updatedAt' => (string)$this->updated_at,
            'deletedAt' => isset($this->deleted_at) ? (string)$this->deleted_at : null,
        ];
    }
}
