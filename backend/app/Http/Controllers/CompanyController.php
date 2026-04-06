<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        return response()->json(
            $this->companyService->list()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cnpj' => 'required',
        ]);

        $company = $this->companyService->create($request->all());

        return response()->json($company, 201);
    }

    public function show(string $id)
    {
        return response()->json($this->companyService->getCompanyByIdWithEmployees($id));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'  => 'sometimes|required|string',
            'cnpj' => 'sometimes|required|unique:companies,cnpj,' . $id,
        ]);

        $company = $this->companyService->update($id, $request->all());

        if (!$company) {
            return response()->json([
                'message' => 'Empresa não encontrada.'
            ], 404);
        }

        return response()->json($company);
    }

    public function destroy(string $id)
    {
        $delete = $this->companyService->delete($id);

        if (!$delete) {
            return response()->json([
                'message' => 'Empresa não encontrada.'
            ], 404);
        }

        return response()->json([
            'message' => 'Empresa removida com sucesso.'
        ]);
    }
}
