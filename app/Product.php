<?php

namespace App;

use App\Seller;
use App\Category;
use App\Events\CheckProductAvailabilityEvent;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

    protected $fillable = [
        'name', 'description', 'quantity', 'status', 'image', 'seller_id',
    ];

    protected $hidden = [
        'pivot',
    ];

    // ! Event emitter to change the product 'status' to 'unavailable' when the 'quantity' is reduced to 0 after performing a 'Transaction'
    protected $dispatchesEvents = [
        'updated' => CheckProductAvailabilityEvent::class,
    ];

    public function isAvailable() {
        // ! This returns a true or false
        return $this->status == Product::AVAILABLE_PRODUCT;
        // Or
        // return $this->status == self::AVAILABLE_PRODUCT;
    }

    public function seller() {
        return $this->belongsTo(Seller::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
