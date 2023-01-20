<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:0|max:255',
            'email' => 'required|unique:users,email|email:rfc,dns',
            'phone' => 'required|numeric|regex:^9[6,7,8][0-9]{8}$^|min:0|digits:10',
            'address' => 'required|string|min:3|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'role' => 'required|integer|min:0',
            'faculty' => 'nullable|integer|min:0',
            'program' => 'nullable|integer|min:0',
            'job_title' => 'nullable|string|min:1|max:255',
            'job_description' => 'nullable|string|min:3|max:255',
            'joined_at' => 'nullable|date|before_or_equal:today',
        ];
    }
}
