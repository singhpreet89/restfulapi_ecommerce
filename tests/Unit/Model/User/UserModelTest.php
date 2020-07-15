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
    public function testAll()
    {
        $numberOfUsers = 10;
        $users = factory(User::class, $numberOfUsers)->create();
        $createdUsers = User::all(); 

        $this->assertEquals($users->count(), $createdUsers->count());
        
        for($i = 0; $i < $numberOfUsers; $i++) {
            $this->assertEquals(
                [
                    $users[$i]->id,
                    $users[$i]->name,
                    $users[$i]->email,
                    $users[$i]->email_verified_at,
                    $users[$i]->verified,
                    $users[$i]->admin,
                    $users[$i]->created_at,
                    $users[$i]->updated_at,
                ],
                [
                    $createdUsers[$i]->id,
                    $createdUsers[$i]->name,
                    $createdUsers[$i]->email,
                    $createdUsers[$i]->email_verified_at,
                    $createdUsers[$i]->verified,
                    $createdUsers[$i]->admin,
                    $createdUsers[$i]->created_at,
                    $createdUsers[$i]->updated_at,
                ]
            );
        }
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

    /**
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = factory(User::class)->create();
        
        $user->name = "Jack Sparrow";
        $user->email = "jack@sparrow.com"; 
        $user->save();

        $savedUser = User::find($user->id);

        $this->assertEquals(
            [
                $user->id,
                $user->name,
                $user->email,
            ],
            [
                $savedUser->id,
                $savedUser->name,
                $savedUser->email,
            ]
        );
        $this->assertEquals(null, $savedUser->deleted_at);
    }

    /**
     *
     * @return void
     */
    public function testDelete()
    {
        $user = factory(User::class)->create();
        $deletedUser = $user->delete($user->id);

        $totalUsers = User::all();

        $this->assertTrue($deletedUser);
        $this->assertEquals(0, $totalUsers->count());
    }
}
