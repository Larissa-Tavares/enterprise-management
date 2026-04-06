<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository
{
    public function create(array $data)
    {
        return Company::create($data);
    }

    public function all()
    {
        return Company::get();  
    }

    public function findById(int $id): ?Company
    {
        return Company::find($id);
    }

    public function findByIdWithEmployees(int $id)
    {
        return Company::with('employees')->find($id);
    }

    public function update(int $id, array $data)
    {
        $company = $this->findById($id);

        if (!$company) {
            return null;
        }

        return $company->update($data);
    }

    public function delete(int $id)
    {
        $company = $this->findById($id);

        if (!$company) {
            return false;
        }

        return $company->delete();
    }

}




