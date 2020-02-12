<?php

use App\Video;
use App\Interfaces\VideoRepositoryInterface;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    private $repository;

    public function __construct(VideoRepositoryInterface $repo)
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
        $datos = factory(Video::class, 15)->make()->toArray();
        foreach ($datos as $subdatos) {
            $this->repository->create($subdatos);
        }
    }
}
