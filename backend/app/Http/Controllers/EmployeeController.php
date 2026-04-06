<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmployeeService;

class EmployeeController extends Controller
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index()
    {
        return response()->json(
            $this->employeeService->list()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'position' => 'required',
        ]);

        $employee = $this->employeeService->create($request->all());

        return response()->json($employee, 201);
    }

    public function show(string $id)
    {
        return response()->json($this->employeeService->getEmployeeById($id));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'     => 'sometimes|required|string',
            'email'    => 'sometimes|required|email',
            'position' => 'sometimes|required|string',
            'company_id' => 'required|exists:companies,id',
        ]);

        $employee = $this->employeeService->update($id, $request->all());

        if (!$employee) {
            return response()->json([
                'message' => 'Funcionário não encontrado.'
            ], 404);
        }

        return response()->json($employee);
    }

    public function destroy(string $id)
    {
        $delete = $this->employeeService->delete($id);

        if (!$delete) {
            return response()->json([
                'message' => 'Funcionário não encontrado.'
            ], 404);
        }

        return response()->json([
            'message' => 'Funcionário removido com sucesso.'
        ]);
    }

}
