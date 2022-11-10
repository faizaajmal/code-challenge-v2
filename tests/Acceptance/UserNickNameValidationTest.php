<?php

namespace Tests\Acceptance;

use App\Models\User\User;
use App\Repositories\UserRepository;
use Hash;
use Tests\FrameworkTest;

class UserNickNameValidationTest extends FrameworkTest
{
    /** @var UserRepository */
    private $repository;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
        $this->user       = User::factory()->create([
            'name'      => $this->faker->name,
            'nick_name' => 'FaizaAjmal',
            'email'     => $this->faker->unique()->email,
            'password'  => Hash::make('hen rooster chicken duck'),
        ]);
    }

    public function testNickNameIsValid()
    {
        /** @var User $user */
        $user = $this->user;
        $this->get("/api/users/{$user->id}");
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

    public function testNickNameIsNotUnique()
    {
        $result = $this->post('/api/users', [
            'name'      => $this->faker->name,
            'nick_name' => 'FaizaAjmal',
            'email'     => $this->faker->unique()->email,
            'password'  => 'hen rooster chicken duck123',
        ]);

        $result->assertSessionHasErrors(['nick_name']);
    }

    public function testNickNameIsUnique()
    {
        $result = $this->post('/api/users', [
            'name'      => $this->faker->name,
            'nick_name' => 'Fayeza',
            'email'     => $this->faker->unique()->email,
            'password'  => 'hen rooster chicken duck123',
        ]);

        $result->AssertSessionHasNoErrors(['nick_name']);
    }
}
