<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee as EmployeeModel;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $query = EmployeeModel::query();

        // 1. Add Search capability
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // 2. Paginate employees
        $employees = $query->paginate(10)->withQueryString();
        $users = \App\Models\User::where('role', 'employee')
            ->whereDoesntHave('employee')
            ->get();

        // 3. Handle AJAX request (for dynamic tables)
        if ($request->ajax()) {
            return view('admin.employees.table', compact('employees'));
        }

        // 4. Return regular view
        return view('admin.employees.index', compact('employees', 'users'));
    }


    public function store(EmployeeRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
            }
            $employee = EmployeeModel::create($data);

            return response()->json([
                'message' => 'Employee created successfully.',
                'employee' => $employee
            ], 201);
        } catch (\Exception $e) {
            Log::error('Create employee error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(EmployeeRequest $request, EmployeeModel $employee)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('profile_photo')) {
                if ($employee->profile_photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->profile_photo);
                }
                $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
            } else {
                unset($data['profile_photo']); // Retain existing photo
            }
            $employee->update($data);
            return response()->json([
                'message' => 'Employee updated successfully.',
                'employee' => $employee
            ]);
        } catch (\Exception $e) {
            Log::error('Update employee error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(EmployeeModel $employee)
    {
        return response()->json($employee->load('user'));
    }

    public function destroy(EmployeeModel $employee)
    {
        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully.'
        ]);
    }
}
