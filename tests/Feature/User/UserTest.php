<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
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
    public function testindex()
    {
        $numberOfUsers = 5;
        factory(User::class, $numberOfUsers)->create();

        $response = $this->getJson(route('users.index'));
        // Log::info($response->getContent());

        $response->assertStatus(200);
        $response->assertJsonCount($numberOfUsers, $key = "data");
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "email",
                    "verified",
                    "admin",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                    "link" => [
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
    public function testUserStore()
    {
        $payload = [
            "name" => "Jack Daniels",
            "email" => "something@example.com",
            "password" => "random",
            "password_confirmation" => "random"
        ];

        $this->withoutExceptionHandling();
        $response = $this->postJson(route('users.store'), $payload);

        $response->assertCreated();
        $response->assertJson([
            "data" => [
                "id" => 1,
                "name" => "Jack Daniels",
                "email" => "something@example.com",
                "verified" => 0,
                "admin" => false,
                "created_at" => !null,
                "updated_at" => !null,
                "deleted_at" => null,
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testShow()
    {
        $user = factory(User::class)->make();
        $user->save();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertOk();
        $response->assertJson([
            "data" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "verified" => (int) $user->verified,
                "admin" => $user->admin === "true" ? true : false,
                "created_at" => (string) $user->created_at,
                "updated_at" => (string) $user->updated_at,
                "deleted_at" => $user->deleted_at,
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = factory(User::class)->create([
            "verified" => "0",
            "admin" => "false",
        ]);

        $payload = [
            "name" => "Jack Daniels",
            "email" => "jack@example.com",
        ];

        $response = $this->putJson(route('users.update', $user->id), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "data" => [
                "id" => $user->id,
                "name" => $payload['name'],
                "email" => $payload['email'],
                "verified" => (int) $user->verified,
                "admin" => $user->admin === "true" ? true : false,
                "created_at" => (string) $user->created_at,
                "updated_at" => (string) $user->updated_at,
                "deleted_at" => $user->deleted_at,
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = factory(User::class)->create();

        $response = $this->deleteJson(route('users.destroy', $user->id));

        $response->assertOk(200);
        $this->assertSoftDeleted($user);
        $response->assertJson([
            "data" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "verified" => (int) $user->verified,
                "admin" => $user->admin === "true" ? true : false,
                "created_at" => (string) $user->created_at,
                "updated_at" => (string) $user->updated_at,
                "deleted_at" => !null,
            ]
        ]);
    }
}
