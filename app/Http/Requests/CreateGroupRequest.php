<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Gate;

class CreateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-group');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|between:3,100|unique:groups,name',
            'memberCapabilities' => 'required|array|distinct',
            'adminCapabilities' => 'required|array|distinct',
        ];
    }
}
