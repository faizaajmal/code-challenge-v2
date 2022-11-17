<?php

namespace Tests\Acceptance;

use App\Models\User\User;
use App\Repositories\UserRepository;
use Tests\FrameworkTest;

class NicknameControllerTest extends FrameworkTest
{
    private $user;
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);

        $this->user = $this->userFactory->create();
    }

    public function testGetRequestFindsUserByNickName()
    {
        $result = $this->json('GET', "/api/user?nickname={$this->user->nick_name}");
        $result->assertStatus(200);
        $this->assertEquals(
            [
                'id'        => $this->user->id,
                'name'      => $this->user->name,
                'nick_name' => $this->user->nick_name,
                'email'     => $this->user->email,
            ],
            json_decode($result->getContent(), true)
        );
    }

    public function testPutRequestUpdatesValidNickname()
    {
        /** @var User $user */
        $user = $this->user;
        $this->put("/api/update-nickname/{$user->id}");

        $this->assertLessThan(
            30,
            strlen($user->nickname),
            'NickName is not valid, It should be less than 30'
        );
        $user->nickname = 'long nick name string that is greater than 30 characters.';
        $this->assertGreaterThan(
            30,
            strlen($user->nickname),
            'NickName is not valid, It should be less than 30'
        );
    }

    public function testPutRequestAcceptsUniqueNickname()
    {
        $result = $this->put("/api/update-nickname/{$this->user->id}", [
            'nick_name' => $this->faker->unique()->name,
        ]);
        $result->assertSessionHasNoErrors(['nick_name']);
    }

    public function testPutRequestRejectsNotUniqueNickname()
    {
        $testUser = $this->userFactory->create([
            'name'      => $this->faker->unique()->name,
            'nick_name' => $this->faker->unique()->name,
            'email'     => $this->faker->unique()->email,
            'password'  => 'qwerty123',
        ]);
        $result = $this->put("/api/update-nickname/{$this->user->id}", [
            'nick_name' => $testUser->nick_name,
        ]);
        $result->assertSessionHasErrors(['nick_name']);
    }
}
