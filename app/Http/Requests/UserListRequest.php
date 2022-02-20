<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserListRequest extends FormRequest
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
            'role' => 'nullable|integer|exists:roles,id|max:255',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
        ];
    }
}
