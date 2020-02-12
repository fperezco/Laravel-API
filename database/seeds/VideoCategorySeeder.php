<?php

use App\VideoCategory;
use App\Interfaces\VideoCategoryRepositoryInterface;
use Illuminate\Database\Seeder;

class VideoCategorySeeder extends Seeder
{
    private $repository;

    public function __construct(VideoCategoryRepositoryInterface $repo)
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
        $datos = factory(VideoCategory::class, 5)->make()->toArray();
        foreach ($datos as $subdatos) {
            $this->repository->create($subdatos);
        }
    }
}
