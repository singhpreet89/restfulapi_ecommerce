<?php

namespace Tests\Unit\Model\User;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     *
     * @return void
     */
    public function testCreate()
    {
        $user = factory(User::class)->create();
        $savedUser = User::find($user->id);
        $savedUserArray = $savedUser->toArray();

        $this->assertEquals(
            [
                $user->id,
                $user->name,
                $user->email,
                $user->email_verified_at,
                $user->verified,
                $user->admin,
                $user->created_at,
                $user->updated_at,
            ],
            [
                $savedUser->id,
                $savedUser->name,
                $savedUser->email,
                $savedUser->email_verified_at,
                $savedUser->verified,
                $savedUser->admin,
                $savedUser->created_at,
                $savedUser->updated_at,
            ]
        );

        $this->assertEquals(null, $savedUser->deleted_at);
        $this->assertArrayNotHasKey("password", $savedUserArray);
        $this->assertArrayNotHasKey("remember_token", $savedUserArray);
        $this->assertArrayNotHasKey("verification_token", $savedUserArray);

        // Because 'password', 'remember_token', 'verification_token' will not be returned and 'deleted_at' will be returned
        $this->assertCount(9, $savedUserArray);
    }
}
