<?php

namespace Tests\Feature\Transaction;

use App\User;
use App\Product;
use Tests\TestCase;
use App\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();
    }

    /**
     * 
     * @return void
     */
    public function testIndex()
    {
        $numberOfSellers = 5;
        $numberOfBuyers = 10;

        // Creating 3 products per seller, so a total of 15 products
        factory(User::class, $numberOfSellers)->create()
            ->each(function ($user) {
                factory(Product::class, 3)->create([
                    "seller_id" => $user->id,
                    "quantity" => 5,
                ]);
            });

        // Creating 2 transactions per Buyers, transalting to a total of 20 transactions 
        factory(User::class, $numberOfBuyers)->create()
            ->each(function ($buyer) {
                factory(Transaction::class, 2)->create([
                    "buyer_id" => $buyer->id,
                    "product_id" => rand(1, 15),
                ]);
            });

        $response = $this->getJson(route('transactions.index'));

        $response->assertOk();
        $response->assertJsonCount(20, $key = "data");
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "quantity",
                    "buyer_id",
                    "product_id",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                    "links" => [
                        "*" => [
                            "rel",
                            "href",
                        ]
                    ]
                ]
            ],
            "links" => [
                "first",
                "last",
                "prev",
                "next"
            ],
            "meta" => [
                "current_page",
                "from",
                "last_page",
                "path",
                "per_page",
                "to",
                "total"
            ]
        ]);
    }

    /**
     * 
     * @return void
     */
    public function testShow()
    {
        // Creating 1 product of 1 Seller
        $seller = factory(User::class)->create();
        $product =  factory(Product::class)->create([
            "seller_id" => $seller->id,
        ]);

        // Creating 1 transactions of 1 Buyers 
        $buyer = factory(User::class)->create();
        $transaction = factory(Transaction::class)->create([
            "buyer_id" => $buyer->id,
            "quantity" => 1,
            "product_id" => $product->id,
        ]);

        $response = $this->getJson(route('transactions.show', $transaction->id));

        $response->assertOk();
        $response->assertJson([
            "data" => [
                "id" => $transaction->id,
                "quantity" => $transaction->quantity,
                "buyer_id" => $buyer->id,
                "product_id" => $product->id,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
                'deleted_at' => null,
            ]
        ]);
    }
}
