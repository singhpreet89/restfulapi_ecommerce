<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'description' => $this->description,
            'createdAt' => (string)$this->created_at,
            'updatedAt' => (string)$this->updated_at,
            'deletedAt' => isset($this->deleted_at) ? (string)$this->deleted_at : null,
        ];
    }
}
