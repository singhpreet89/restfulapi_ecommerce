<?php

namespace Tests\Feature\Buyer;

use App\User;
use App\Product;
use Tests\TestCase;
use App\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuyerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();


    }

    /**
     *
     * @return void
     */
    public function testIndex()
    { 
        $numberOfSellers = 20;
        $numberOfProducts = 10;
        $numberOfBuyers = 15;
        $numberOfTransactions = 10;

        $seller = factory(User::class, $numberOfSellers)->create();
        $product = factory(Product::class, $numberOfProducts)->create();
        $buyers = factory(User::class, $numberOfBuyers)->create();
        $transactions = factory(Transaction::class, $numberOfTransactions)->create();
   
        $response = $this->getJson(route('buyers.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "email",
                    "verified",
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
        $seller = factory(User::class)->create();
        $product = factory(Product::class, 2)->create();
        $buyer = factory(User::class)->create();
        $transaction = factory(Transaction::class)->create([
            "buyer_id" => $buyer->id,
        ]);
   
        $response = $this->getJson(route('buyers.show', $buyer->id));

        $response->assertOk();
        $response->assertJson([
            "data" => [
                "id" => $buyer->id,
                "name" => $buyer->name,
                "email" => $buyer->email,
                "verified" => (int) $buyer->verified,
                'created_at' => isset($buyer->created_at) ? (string) $buyer->created_at : null,
                'updated_at' => isset($buyer->updated_at) ? (string) $buyer->updated_at : null,
                'deleted_at' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
            ]
        ]);
    }
}
