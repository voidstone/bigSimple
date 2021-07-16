<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogBostCreateRequest extends FormRequest
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
            'title' => 'required|min:5|max:200|unique:blog_posts',
            'slug' => 'max:200',
            'content_raw' => 'required|string|min:5|max:10000',
            'category_id' => 'required|integer|exists:blog_categories,id'
        ];
    }

    /**для перевода ошибки
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'введите заголовок статьи',
            'content_raw.min' => 'Минимальная длина статьи [:min] символов',
        ];
    }

    //для перевода атрибута

    public function attributes() {
        return [
            'title' => 'Заголовок'
        ];
    }

}
