<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all($arrayParameters = null);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);

    public function random();

    //for JWT object custom
    public function findByUserId($id, $userId);

    //for JWT object custom
    public function deleteByUserId($id, $userId);
}
