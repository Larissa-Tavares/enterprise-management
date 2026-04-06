<?php

namespace App\Services;

use App\Repositories\EmployeeRepository;

class EmployeeService
{
    private EmployeeRepository $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function list()
    {
        return $this->repository->all();
    }

    public function getEmployeeById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}