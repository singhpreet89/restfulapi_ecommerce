<?php

namespace App\Http\Resources\User;

use Illuminate\Support\Facades\Route;
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
        $endUrl = Route::currentRouteName() === 'buyers.sellers.index' || Route::currentRouteName() === 'categories.sellers.index' || Route::currentRouteName() === 'categories.buyers.index'
            ? explode('.', Route::currentRouteName())[1] : explode('.', Route::currentRouteName())[0];
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'verified' => $this->verified,
            'link' => [
                'rel' => 'self',
                'href' => route($endUrl . '.show', $this->id),
            ],
        ];
    }
}
