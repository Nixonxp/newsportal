<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()?->hasAnyRole('admin')) {
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
                'max:255',
            ],
            'link' => [
                'string',
                'required',
                'min:3',
                'max:255',
            ],
            'type' => [
                'string',
                'required',
                'min:3',
                'max:255',
            ],
            'showdate_start' => [
                'date'
            ],
            'showdate_end' => [
                'date',
                'after:showdate_start',
            ],
            'image' => 'image|nullable',
        ];
    }
}
