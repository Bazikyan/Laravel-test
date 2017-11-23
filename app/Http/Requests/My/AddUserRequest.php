<?php

namespace App\Http\Requests\My;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
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
            'firstname' => 'required|alpha|max:20',
            'lastname' => 'required|alpha|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|alpha_dash|min:6'
        ];
    }
}
