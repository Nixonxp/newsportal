<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdListRequest extends FormRequest
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
            'type' => 'nullable|string|max:255',
            'activity' => 'nullable|string|max:255',
            'sort' => 'nullable|string|max:255',
        ];
    }
}
