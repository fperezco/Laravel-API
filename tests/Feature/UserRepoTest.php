<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRepoTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make('App\Interfaces\UserRepositoryInterface');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRepo()
    {
        $user = $this->repo->random();
        $this->assertInstanceOf(User::class, $user);
        //dd($user);
    }
}
