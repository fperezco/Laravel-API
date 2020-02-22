<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepoTest extends TestCase
{
    //use RefreshDatabase;
    use DatabaseTransactions; //estoy corriendolo contra la BD de mysql
    private $apiRoute;
    private $repo;

    public function setUp(): void
    {
        parent::setUp();
        //$this->repo = $this->app->make('App\Interfaces\CocheRepositoryInterface');
        $this->repo = $this->app->make('App\Interfaces\UserRepositoryInterface');
        $this->apiRoute = '/api/v1/';
        //$this->artisan('db:seed');
    }

    /**
     * Invalid route test
     * @test
     * @return void
     */
    public function invalidRouteTest()
    {
        $this->get($this->apiRoute . 'aboutinvalidte')
            ->assertStatus(404);
    }

    /**
     * Invalid method to a valid route test
     * @test
     * @return void
     */
    public function invalidMethodRouteTest()
    {
        $this->delete($this->apiRoute . 'videos')
            ->assertStatus(401);
    }

    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function aboutTest()
    {
        $this->get('/api/v1/about')
            ->assertStatus(200);
        //->assertSee('REST vacio con info del servidor accesible para todos');
    }

    /**
     * Login test
     * @test
     * @return void
     */
    public function LoginTest()
    {
        //creamos un user ficticio
        $user = new User();
        $user->name = 'testuser';
        $user->surname = 'testuser';
        $user->email = 'test@user.com';
        $user->password = Hash::make('password');
        //lo almacenamos via repositorio
        $this->repo->create($user->toArray());

        return $this->post($this->apiRoute . 'login', ['email' => $user->email, 'password' => 'password'])
        ->assertStatus(200);
    }

    /**
    * Login fail test
    * @test
    * @return void
    */
    public function LoginFailTest()
    {
        //creamos un user ficticio
        $user = new User();
        $user->name = 'testuser';
        $user->surname = 'testuser';
        $user->email = 'test@user.com';
        $user->password = Hash::make('password');
        //lo almacenamos via repositorio
        $this->repo->create($user->toArray());

        return $this->post($this->apiRoute . 'login', ['email' => $user->email, 'password' => 'passwordersaefaesf'])
        ->assertStatus(401);
    }
}
