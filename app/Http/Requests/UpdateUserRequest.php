<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Gate;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route('user');

        return Gate::allows('update-user', [$user]);
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
        ];
    }
}
