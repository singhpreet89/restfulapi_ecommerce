<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    // TODO: VERIFIED_USER & UNVERIFIED_USER logic can be handled inside the ProductFactory
    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

    protected $fillable = [
        'name', 'description', 'quantity', 'status', 'image', 'seller_id',
    ];

    protected $hidden = [
        'pivot',
    ];

    // TODO: isAvailable logic can be handled inside the Factory
    public function isAvailable() {
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
