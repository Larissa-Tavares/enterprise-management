<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function create(array $data)
    {
        return Employee::create($data);
    }

    public function all()
    {
        return Employee::get();
    }

    public function findById(int $id) : ?Employee
    {
        return Employee::find($id);
    }

    public function update(int $id, array $data)
    {
        $employee = $this->findById($id);

        if (!$employee) {
            return null;
        }

        $employee->fill($data);

        $employee->save();

        return $employee;
    }

    public function delete(int $id)
    {
        $employee = $this->findById($id);

        if (!$employee) {
            return false;
        }

        return $employee->delete();
    }
}