<?php

use App\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $repository;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = factory(User::class, 5)->make()->toArray();
        foreach ($datos as $subdatos) {
            $this->repository->create($subdatos);
        }
    }
}
