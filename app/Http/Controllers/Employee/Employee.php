<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee as EmployeeModel;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class Employee extends Controller
{

    public function index()
    {
        return view('admin.employees.index');
    }

    public function store(EmployeeRequest $request)
    {
        try{

        $data = $request->validated();
        $Employee = EmployeeModel::create($data);

        return response()->json([
            'message' => 'Employee created successfully.',
            'employee' => $Employee
        ], 201);
        }catch (\Exception $e) {
            Log::error('Create employee error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(EmployeeRequest $request , EmployeeModel $Employee)
    {
        try{
            $data = $request->validated();
            $Employee->update($data);
            return response()->json([
                'message' => 'Employee updated successfully.',
                'employee' => $Employee
            ]);
        }catch (\Exception $e) {
            Log::error('Update employee error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(EmployeeModel $Employee)
    {
        return response()->json($Employee);
    }

    public function destroy(EmployeeModel $Employee)
    {
        $Employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully.'
        ]);
    }

}
