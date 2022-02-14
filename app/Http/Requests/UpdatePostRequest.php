<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()?->hasAnyRole(['admin', 'Chief-editor', 'Editor'])) {
            return true;
        }

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
            'title' => [
                'string',
                'required',
                'min:3',
                'max:255',
                Rule::unique('posts')->ignore($this->route()->post)
            ],
            'slug' => [
                'string',
                'required',
                'min:3',
                'max:255',
                'nullable',
                Rule::unique('posts')->ignore($this->route()->post)
            ],
            'category_id' => 'integer|required|exists:\App\Models\Category,id',
            'excerpt' => 'string|min:3|max:400|required',
            'content' => 'string|min:3|max:10000|required',
            'is_published' => 'in:0,1',
            'popular' => 'in:0,1',
            'main_slider' => 'in:0,1',
            'image' => 'image|nullable',
        ];
    }
}
