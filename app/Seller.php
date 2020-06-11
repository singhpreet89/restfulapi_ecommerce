<?php

namespace App;

use App\Product;

class Seller extends User
{
    /**
     * To eliminate the error 'sellers table not found' while seeding the database. 
     * And to use the 'users' table to add a new Seller through API POST request. 
     * Because the 'sellers' table does not exist 
     * */ 
    protected $table = 'users';

    public function products() {
        return $this->hasMany(Product::class);
    }
}
