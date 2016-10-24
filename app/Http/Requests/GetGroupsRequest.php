<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Gate;

class GetGroupsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // if members and/or admins shall be included, the requester needs to be capable of
        // seeing them..
        if ($this->has('include') and (!Gate::allows('get-user'))) {
            return false;
        }

        return Gate::allows('get-group');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
