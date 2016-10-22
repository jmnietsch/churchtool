<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Gate;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sex' => 'required|in:m,f',
            'firstName' => 'required|string|between:3,255',
            'lastName' => 'required|string|between:3,255',
            'email' => 'required|email|unique:users,email',
            'dateOfBirth' => 'date|before:today',
        ];
    }
}
