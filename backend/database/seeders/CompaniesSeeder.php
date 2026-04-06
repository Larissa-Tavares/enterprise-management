<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesSeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'Apple', 'cnpj' => '55.555.555/0005-55', 'status' => false],
            ['name' => 'Amazon', 'cnpj' => '11.111.111/0001-11', 'status' => true],
            ['name' => 'Netflix', 'cnpj' => '22.222.222/0002-22', 'status' => true],
            ['name' => 'Meta', 'cnpj' => '33.333.333/0003-33', 'status' => true],
            ['name' => 'Microsoft', 'cnpj' => '44.444.444/0004-44', 'status' => true],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
