<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email,' . $this->user->id,
            'phone' => 'required|numeric|regex:^9[6,7,8][0-9]{8}$^|min:0|digits:10',
            'address' => 'required|string|min:3|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',

            'faculty' => 'required|integer|min:0',
            'program' => 'required|integer|min:0',
            'semester' => 'required|integer|min:0',
            'roll_number' => 'nullable|numeric|min:0|digits_between:5,20',
            'registration_number' => 'nullable|string|min:1|max:30',
            'guardian_name' => 'nullable|string|min:3|max:255',
            'guardian_phone' => 'nullable|numeric|regex:^9[6,7,8][0-9]{8}$^|min:0|digits:10',
            'joined_at' => 'nullable|date|before_or_equal:today',
        ];
    }
}
