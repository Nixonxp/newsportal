<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NewsCategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->hasAnyRole(['admin', 'Chief-editor'])) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'min:3',
                Rule::unique('categories')->ignore($this->route()->category)
            ],
            'slug' => [
                'string',
                'required',
                'max:500',
                Rule::unique('categories')->ignore($this->route()->category)
            ],
            'description' => 'string'
        ];
    }
}
