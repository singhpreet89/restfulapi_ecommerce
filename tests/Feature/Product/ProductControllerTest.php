<?php

namespace Tests\Feature\Product;

use App\User;
use App\Product;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void 
    {
        parent::setup();
    }

    /**
     * @return void
     */
    public function testIndex()
    {   
        $numberOfUsers = 5;
        $numberOfProducts = 19;

        $users = factory(User::class, $numberOfUsers)->create();
        $products = factory(Product::class, $numberOfProducts)->create([
            "seller_id" => rand(1, $numberOfUsers),
        ]);

        $response = $this->getJson(route('products.index'));

        $response->assertOk();
        $response->assertJsonCount($numberOfProducts, $key = "data");
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "seller_id",
                    "name",
                    "description",
                    "quantity",
                    "status",
                    "image",
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
     * @return void
     */
    public function testShow()
    {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create([
            "seller_id" => $user->id,
        ]);

        $response = $this->getJson(route('products.show', $product->id));

        $response->assertOk();
        $response->assertJson([
            "data" => [
                'id' => $product->id,
                'seller_id' => $product->seller_id,
                'name' => $product->name,
                'description' => $product->description,
                'quantity' => $product->quantity,
                'status' => $product->status,
                'image' => url("img/{$product->image}"),
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
                'deleted_at' => null,
            ]
        ]);
    }
}
