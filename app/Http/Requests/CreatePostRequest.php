<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /*if (Auth::user()->hasAnyRole(['admin', 'Chief-editor'])) {
            return true;
        }*/ // todo add roles moderate

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
                'unique:posts,title'
            ],
            'slug' => [
                'string',
                'min:3',
                'max:255',
                'nullable',
                'unique:posts,slug'
            ],
            'category_id' => 'integer|required|exists:\App\Models\Category,id',
            'excerpt' => 'string|min:3|max:400|required',
            'content' => 'string|min:3|max:10000|required',
            'image' => 'image|nullable',
        ];
    }
}
