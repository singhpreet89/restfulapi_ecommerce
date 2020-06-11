<?php

namespace App;

use App\Transaction;

class Buyer extends User
{
    /**
     * To eliminate the error 'buyers table not found' while seeding the database. 
     * And to use the 'users' table to add a new Buyer through API POST request. 
     * Because the 'buyers' table does not exist 
     * */ 
    protected $table = 'users';

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
