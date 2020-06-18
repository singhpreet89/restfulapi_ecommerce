<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
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
            'verified' => (int) $this->verified,
            'admin' => $this->admin === "true" ? true : false,
            'link' => [
                'rel' => 'self',
                'href' => route('users.show', $this->id),
            ],
        ];
    }
}
