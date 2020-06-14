<?php

namespace App;

use App\Transaction;
use App\Scopes\BuyerScope;

class Buyer extends User
{
    /**
     * To eliminate the error 'buyers table not found' while seeding the database. 
     * And to use the 'users' table to add a new Buyer through API POST request. 
     * Because the 'buyers' table does not exist 
     * */ 
    protected $table = 'users';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
