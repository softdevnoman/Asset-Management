<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id ?? $this->route('employee');

        return [
            'user_id'       => 'nullable|integer|exists:users,id|unique:employee,user_id,' . ($employeeId ?? 'NULL'),
            'employee_id'   => 'required|string|max:50|unique:employee,employee_id,' . ($employeeId ?? 'NULL'),
            'position'      => 'required|string|max:100',
            'department'    => 'required|string|max:100',
            'status'        => 'required|string|in:active,inactive,on_leave',
            'phone'         => 'required|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'join_date'     => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists'         => 'Selected user is invalid.',
            'user_id.unique'         => 'An employee profile already exists for this user.',
            'employee_id.required'   => 'Employee ID is required.',
            'employee_id.unique'     => 'Employee ID has already been taken.',
            'position.required'      => 'Position is required.',
            'department.required'    => 'Department is required.',
            'status.required'        => 'Status is required.',
            'phone.required'         => 'Phone is required.',
            'profile_photo.image'    => 'Profile photo must be an image.',
            'profile_photo.max'      => 'Profile photo must not exceed 2MB.',
            'join_date.required'     => 'Join date is required.',
        ];
    }
}
